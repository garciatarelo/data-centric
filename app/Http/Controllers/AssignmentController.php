<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    // Esta función es para mostrar la página principal de asignaciones
    // Aquí traigo toda la info que necesito:
    // - La lista de asignaciones con sus dispositivos y usuarios
    // - Todos los dispositivos que hay (ya sean disponibles o no)
    // - Todos los usuarios registrados
    public function index()
    {
        // Traigo las asignaciones y cargo su info relacionada de una vez
        $assignments = Assignment::with(['device', 'user', 'assignedBy'])->get();
        // Traigo TODOS los dispositivos, no solo los disponibles como antes
        $devices = Device::all();
        // Lista de usuarios para el select
        $users = User::all();
        // Mando todo a la vista principal
        return view('admin.assignments.index', compact('assignments', 'devices', 'users'));
    }

    // Esta función es para crear una nueva asignación
    // Cuando le doy click al botón de "Asignar" en el modal, viene aquí
    public function store(Request $request)
    {
        // Primero reviso que me mandaron toda la info necesaria
        // Si no mandaron el ID del dispositivo o del usuario, Laravel me avisa
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Busco el dispositivo que quieren asignar
        // Si no existe el ID que mandaron, Laravel tira error automático
        $device = Device::findOrFail($request->device_id);
        
        // Verificar si el dispositivo está disponible
        if ($device->status !== 'available') {
            return back()->with('error', 'El dispositivo seleccionado no está disponible. Estado actual: ' . ucfirst($device->status));
        }

        // Verificar si el usuario ya tiene una asignación activa de este dispositivo
        $existingAssignment = Assignment::where('device_id', $device->id)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return back()->with('error', 'Este dispositivo ya está asignado a otro usuario.');
        }

        // Verificar si el usuario ya tiene una asignación activa del mismo dispositivo
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
            // Me aseguro que exista la carpeta donde voy a guardar los PDFs
            // Si no existe, la creo
            Storage::makeDirectory('public/power_of_attorney');

            // Aquí genero el PDF usando la plantilla que hicimos
            // Le paso toda la info de la asignación (dispositivo, usuario, etc)
            // También configuro el PDF para que se vea bonito y pueda usar imágenes
            $pdf = PDF::loadView('admin.assignments.power-of-attorney', [
                'assignment' => $assignment->load(['device', 'user', 'assignedBy'])
            ])->setOption('isRemoteEnabled', true) // Para poder usar imágenes de internet (el QR)
              ->setOption('isHtml5ParserEnabled', true) // Para que entienda bien el HTML
              ->setOption('enable_remote', true) // También para las imágenes
              ->setOption('enable_html5_parser', true) // Lo mismo
              ->setPaper('a4'); // Tamaño de hoja normal

            $filename = 'Carta Poder - Asignación ' . $assignment->id . '.pdf';
            $path = 'power_of_attorney/' . $filename;
            
            // Asegúrate de que el directorio exista
            if (!Storage::disk('public')->exists('power_of_attorney')) {
                Storage::disk('public')->makeDirectory('power_of_attorney');
            }
            
            // Guarda el pdf
            Storage::disk('public')->put($path, $pdf->output());
            
            // Actualiza la asignación con la ruta del PDF
            $assignment->update(['power_of_attorney' => $path]);

            // Aquí reviso si la petición vino por AJAX (desde el modal)
            // Si es así, respondo con JSON para que el modal sepa qué hacer
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asignación creada exitosamente.'
                ]);
            }
            
            // Si no es AJAX, redirecciono normal con mensaje de éxito
            return redirect()->route('admin.assignments.index')
                ->with('success', 'Asignación creada exitosamente.');
        } catch (\Exception $e) {
            // Si algo salió mal, guardo el error en los logs
            report($e);
            // Si era petición AJAX, mando el error para que el modal lo muestre
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la asignación: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.assignments.index')
                ->with('error', 'La asignación se creó pero hubo un problema al generar el PDF. Por favor, contacta al administrador. Error: ' . $e->getMessage());
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
            // Generate new PDF if file doesn't exist
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