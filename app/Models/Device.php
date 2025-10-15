<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial',
        'brand',
        'model',
        'type',
        'imei',
        'status',
        'notes',
    ];
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }
}
