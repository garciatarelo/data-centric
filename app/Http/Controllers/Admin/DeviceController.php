<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all();
        return view('admin.devices.index', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'serial' => ['required', 'string', 'max:255', 'unique:devices'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:laptop,desktop,tablet,phone,other'],
            'imei' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:available,assigned,maintenance,retired'],
            'notes' => ['nullable', 'string'],
        ]);

        $device = Device::create([
            'serial' => $request->serial,
            'brand' => $request->brand,
            'model' => $request->model,
            'type' => $request->type,
            'imei' => $request->imei,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.devices.index')->with('success', 'Dispositivo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return view('admin.devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Device $device)
    {
        // Si la petición espera JSON, retornar los datos del dispositivo
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'id' => $device->id,
                'serial' => $device->serial,
                'brand' => $device->brand,
                'model' => $device->model,
                'type' => $device->type,
                'imei' => $device->imei,
                'status' => $device->status,
                'notes' => $device->notes,
            ]);
        }
        
        return view('admin.devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $request->validate([
            'serial' => ['required', 'string', 'max:255', 'unique:devices,serial,' . $device->id],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:laptop,desktop,tablet,phone,other'],
            'imei' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:available,assigned,maintenance,retired'],
            'notes' => ['nullable', 'string'],
        ]);

        // Regla de negocio: No permitir establecer "available" si tiene una asignación activa
        if ($request->status === 'available' && $device->currentAssignment()->exists()) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede cambiar a Disponible: el dispositivo sigue asignado a un usuario. Elimina la asignación activa primero.'
                ], 422);
            }
            return back()
                ->with('error', 'No se puede cambiar a Disponible: el dispositivo sigue asignado a un usuario. Elimina la asignación activa primero.')
                ->withInput();
        }

        $device->serial = $request->serial;
        $device->brand = $request->brand;
        $device->model = $request->model;
        $device->type = $request->type;
        $device->imei = $request->imei;
        $device->status = $request->status;
        $device->notes = $request->notes;

        $device->save();

        return redirect()->route('admin.devices.index')->with('success', 'Dispositivo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('admin.devices.index')->with('success', 'Dispositivo eliminado correctamente');
    }
}
