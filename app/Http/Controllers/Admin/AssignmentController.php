<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['device', 'user', 'assignedBy'])->get();
        $devices = Device::all();
        // Solo dispositivos disponibles y sin asignación activa para el modal de crear
        $availableDevices = Device::where('status', 'available')
            ->whereDoesntHave('currentAssignment')
            ->get();
        $users = User::all();
        return view('admin.assignments.index', compact('assignments', 'devices', 'availableDevices', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:devices,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $device = Device::findOrFail($request->device_id);
        
        if ($device->status !== 'available') {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El dispositivo seleccionado no está disponible. Estado actual: ' . ucfirst($device->status)
                    ], 422);
                }
                return back()->with('error', 'El dispositivo seleccionado no está disponible. Estado actual: ' . ucfirst($device->status));
        }

        $existingAssignment = Assignment::where('device_id', $device->id)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este dispositivo ya está asignado a otro usuario.'
                    ], 422);
                }
                return back()->with('error', 'Este dispositivo ya está asignado a otro usuario.');
        }

        $existingAssignment = Assignment::where('user_id', $request->user_id)
            ->where('device_id', $request->device_id)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este usuario ya tiene una asignación activa de este dispositivo.'
                    ], 422);
                }
                return back()->with('error', 'Este usuario ya tiene una asignación activa de este dispositivo.');
        }

        $assignment = Assignment::create([
            'device_id' => $request->device_id,
            'user_id' => $request->user_id,
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
            'status' => 'active',
            'qr_data' => Str::random(32),
        ]);

        $device->update(['status' => 'assigned']);

        // Recargar la asignación con todas las relaciones necesarias
        $assignment->load(['device', 'user', 'assignedBy']);

        try {
            $pdf = PDF::loadView('admin.assignments.power-of-attorney', [
                'assignment' => $assignment
            ])->setOption('isRemoteEnabled', true)
              ->setOption('isHtml5ParserEnabled', true)
              ->setOption('enable_remote', true)
              ->setOption('enable_html5_parser', true)
              ->setPaper('a4');

            $filename = 'carta_poder_asignacion_' . $assignment->id . '.pdf';
            
            Storage::disk('public')->put($filename, $pdf->output());
            
            $assignment->update(['power_of_attorney' => $filename]);

        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage(), [
                'assignment_id' => $assignment->id,
                'trace' => $e->getTraceAsString()
            ]);
            // Continuar sin PDF, no fallar la asignación
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asignación creada exitosamente.'
            ]);
        }
        
        return redirect()->route('admin.assignments.index')
            ->with('success', 'Asignación creada exitosamente.');
    }

    public function edit(Assignment $assignment)
    {
        return response()->json([
            'user_id' => $assignment->user_id,
            'device_id' => $assignment->device_id,
            'assigned_date' => $assignment->assigned_at,
            'return_date' => $assignment->returned_at,
            'status' => $assignment->status,
            'has_power_of_attorney' => !empty($assignment->power_of_attorney),
        ]);
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'user_id' => 'required|exists:users,id',
            'returned_at' => 'nullable|date',
            'status' => 'required|in:active,returned,cancelled',
        ]);

        if ($assignment->status !== $request->status) {
            if ($request->status === 'returned' || $request->status === 'cancelled') {
                $assignment->device->update(['status' => 'available']);
            } else if ($request->status === 'active') {
                $assignment->device->update(['status' => 'assigned']);
            }
        }

        $assignment->update([
            'device_id' => $request->device_id,
            'user_id' => $request->user_id,
            'returned_at' => $request->returned_at,
            'status' => $request->status,
        ]);

        if ($request->hasFile('power_of_attorney')) {
            if ($assignment->power_of_attorney) {
                Storage::delete('public/' . $assignment->power_of_attorney);
            }
            
            $path = $request->file('power_of_attorney')->store('/', 'public');
            $assignment->update(['power_of_attorney' => $path]);
        }

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Asignación actualizada exitosamente.');
    }

    public function show(Assignment $assignment)
    {
        return view('admin.assignments.show', compact('assignment'));
    }

    public function destroy(Assignment $assignment)
    {
        if ($assignment->status === 'active') {
            $assignment->device->update(['status' => 'available']);
        }

        if ($assignment->power_of_attorney) {
            Storage::delete('public/' . $assignment->power_of_attorney);
        }

        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Asignación eliminada exitosamente.');
    }

    public function downloadPdf(Assignment $assignment)
    {
        if (!$assignment->power_of_attorney || !Storage::exists('public/' . $assignment->power_of_attorney)) {
            $pdf = PDF::loadView('admin.assignments.power-of-attorney', [
                'assignment' => $assignment->load(['device', 'user', 'assignedBy'])
            ])->setOption('isRemoteEnabled', true)
              ->setOption('isHtml5ParserEnabled', true)
              ->setOption('enable_remote', true)
              ->setOption('enable_html5_parser', true)
              ->setPaper('a4');
            
            return $pdf->download('Carta Poder - Asignación ' . $assignment->id . '.pdf');
        }

        return Storage::download('public/' . $assignment->power_of_attorney, 'Carta Poder - Asignación ' . $assignment->id . '.pdf');
    }
}
