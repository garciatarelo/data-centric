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
        Route::resource('devices', \App\Http\Controllers\Admin\DeviceController::class);
        
        // Rutas para asignaciones
        Route::controller(\App\Http\Controllers\Admin\AssignmentController::class)->group(function () {
            Route::get('/assignments', 'index')->name('assignments.index');
            Route::post('/assignments', 'store')->name('assignments.store');
            Route::get('/assignments/{assignment}', 'show')->name('assignments.show');
            Route::get('/assignments/{assignment}/edit', 'edit')->name('assignments.edit');
            Route::put('/assignments/{assignment}', 'update')->name('assignments.update');
            Route::delete('/assignments/{assignment}', 'destroy')->name('assignments.destroy');
            Route::get('/assignments/{assignment}/download-pdf', 'downloadPdf')->name('assignments.download-pdf');
        });
        
        // Rutas para usuarios
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
});

require __DIR__.'/auth.php';
