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

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['device', 'user', 'assignedBy'])->get();
        $devices = Device::all();
        $users = User::all();
        return view('admin.assignments.index', compact('assignments', 'devices', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $device = Device::findOrFail($request->device_id);
        
        if ($device->status !== 'available') {
            return back()->with('error', 'El dispositivo seleccionado no está disponible. Estado actual: ' . ucfirst($device->status));
        }

        $existingAssignment = Assignment::where('device_id', $device->id)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return back()->with('error', 'Este dispositivo ya está asignado a otro usuario.');
        }

        $existingAssignment = Assignment::where('user_id', $request->user_id)
            ->where('device_id', $request->device_id)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
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

        try {
            Storage::makeDirectory('public/power_of_attorney');

            $pdf = PDF::loadView('admin.assignments.power-of-attorney', [
                'assignment' => $assignment->load(['device', 'user', 'assignedBy'])
            ])->setOption('isRemoteEnabled', true)
              ->setOption('isHtml5ParserEnabled', true)
              ->setOption('enable_remote', true)
              ->setOption('enable_html5_parser', true)
              ->setPaper('a4');

            $filename = 'Carta Poder - Asignación ' . $assignment->id . '.pdf';
            $path = 'power_of_attorney/' . $filename;
            
            if (!Storage::disk('public')->exists('power_of_attorney')) {
                Storage::disk('public')->makeDirectory('power_of_attorney');
            }
            
            Storage::disk('public')->put($path, $pdf->output());
            
            $assignment->update(['power_of_attorney' => $path]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asignación creada exitosamente.'
                ]);
            }
            
            return redirect()->route('admin.assignments.index')
                ->with('success', 'Asignación creada exitosamente.');
        } catch (\Exception $e) {
            report($e);
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la asignación: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.assignments.index')
                ->with('error', 'La asignación se creó pero hubo un problema al generar el PDF. Por favor, contacta al administrador.');
        }
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
            
            $path = $request->file('power_of_attorney')->store('power_of_attorney', 'public');
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
