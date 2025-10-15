<?php

use Illuminate\Support\Facades\Route;

// Redirige automáticamente al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas protegidas (solo para usuarios logueados)
Route::middleware(['auth'])->group(function () {
    // Ruta de dashboard principal - redirige al dashboard admin
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
    
    // Ruta de perfil de usuario
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');

    // Rutas del panel de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Rutas para dispositivos
        Route::get('/devices', function () {
            return view('admin.devices.index');
        })->name('devices.index');
        
        // Rutas para asignaciones
        Route::get('/assignments', function () {
            return view('admin.assignments.index');
        })->name('assignments.index');
        
        // Rutas para usuarios
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
});

require __DIR__.'/auth.php';
