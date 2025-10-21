<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Este es el modelo de Dispositivo - aquí guardo todos los datos de los dispositivos
 * que voy a poder asignar a los usuarios
 * 
 * La tabla tiene:
 * - serial: El número de serie del dispositivo
 * - brand: La marca (Apple, Samsung, etc)
 * - model: El modelo específico
 * - type: Qué tipo de dispositivo es (celular, tablet, laptop)
 * - imei: El IMEI si es un celular o tablet
 * - status: Si está disponible, asignado, en reparación, etc
 * - notes: Notas adicionales sobre el dispositivo
 */
class Device extends Model
{
    // Estos son los campos que puedo llenar cuando creo o actualizo un dispositivo
    protected $fillable = [
        'serial',
        'brand',
        'model',
        'type',
        'imei',
        'status',
        'notes',
    ];
    // Esta función me trae todas las asignaciones que ha tenido este dispositivo
    // Uso hasMany porque un dispositivo puede tener MUCHAS asignaciones (historial)
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // Esta función me trae solo la asignación actual del dispositivo (si está asignado)
    // Uso hasOne porque solo puede tener UNA asignación activa a la vez
    // El where es para que solo me traiga la que está activa
    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }
}
