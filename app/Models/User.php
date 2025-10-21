<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Este es el modelo de Usuario - aquí guardo la info de todos los usuarios
 * tanto los que pueden recibir dispositivos como los admins
 * 
 * La tabla tiene:
 * - name: Nombre completo del usuario
 * - nickname: Un apodo o nombre corto (opcional)
 * - email: El correo que usan para entrar al sistema
 * - password: Su contraseña (encriptada)
 * - phone: Número de teléfono
 * - img: Foto de perfil
 * - role: Si es admin o usuario normal
 */
class User extends Authenticatable
{
    // Estas son características extra que tiene este modelo
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; // Para crear usuarios de prueba y mandar notificaciones

    // Estos son los campos que puedo llenar cuando creo o actualizo un usuario
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'phone',
        'img',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // Estos campos NUNCA se muestran cuando convierto el usuario a JSON o array
    // Es por seguridad, para no mostrar contraseñas ni tokens
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Aquí le digo a Laravel cómo debe tratar ciertos campos
     * - email_verified_at: que lo trate como fecha
     * - password: que lo encripte automáticamente
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Esta función me trae todas las asignaciones que tiene o ha tenido este usuario
    // Uso hasMany porque un usuario puede tener MUCHOS dispositivos asignados
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
