<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

// Rutas del perfil de usuario (protegidas por autenticación)
Route::middleware(['auth'])->group(function () {
    // Ver perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('user.profile.show');
    
    // Editar perfil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    
    // Actualizar perfil
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    
    // Eliminar foto de perfil
    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePhoto'])->name('user.profile.delete-photo');
});