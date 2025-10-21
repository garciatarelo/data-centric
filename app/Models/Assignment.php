<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Este es el modelo de Asignación - aquí guardo toda la info de cuando asigno un dispositivo a alguien
 * 
 * La tabla tiene:
 * - device_id: El ID del dispositivo que estoy asignando
 * - user_id: El ID del usuario al que le estoy dando el dispositivo
 * - assigned_by: El ID del admin que hizo la asignación (o sea, yo u otro admin)
 * - assigned_at: Cuándo se hizo la asignación
 * - returned_at: Cuándo devolvieron el dispositivo (si ya lo devolvieron)
 * - power_of_attorney: La ruta donde guardo el PDF de la carta poder
 * - qr_data: El código único para el QR que va en el PDF
 * - status: Si está activa, devuelta o cancelada la asignación
 */
class Assignment extends Model
{
    // Estos son los campos que puedo llenar cuando creo o actualizo una asignación
    protected $fillable = [
        'device_id',
        'user_id',
        'assigned_by',
        'assigned_at',
        'returned_at',
        'power_of_attorney',
        'qr_data',
        'status',
    ];

    // Esta función me trae la info del dispositivo asignado
    // Uso belongsTo porque cada asignación es de UN solo dispositivo
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // Esta me trae la info del usuario al que le asigné el dispositivo
    // También es belongsTo porque cada asignación es para UN solo usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Esta me dice quién hizo la asignación (yo u otro admin)
    // Es belongsTo porque solo UN admin puede hacer la asignación
    // Le pongo 'assigned_by' para que sepa que es la columna que tiene el ID del admin
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
