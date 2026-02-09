<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// Routes pour la gestion des utilisateurs
Route::prefix('user-management')->name('user-management.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/create', [UserManagementController::class, 'create'])->name('create');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    
    // Routes pour la gestion des rôles
    Route::get('/roles', [UserManagementController::class, 'roles'])->name('roles');
    Route::get('/roles/create', [UserManagementController::class, 'createRole'])->name('create-role');
    Route::post('/roles', [UserManagementController::class, 'storeRole'])->name('store-role');
    Route::get('/roles/{role}/edit', [UserManagementController::class, 'editRole'])->name('edit-role');
    Route::put('/roles/{role}', [UserManagementController::class, 'updateRole'])->name('update-role');
    Route::delete('/roles/{role}', [UserManagementController::class, 'destroyRole'])->name('destroy-role');
    
    // Routes pour la gestion des permissions
    Route::get('/permissions', [UserManagementController::class, 'permissions'])->name('permissions');
    Route::get('/permissions/create', [UserManagementController::class, 'createPermission'])->name('create-permission');
    Route::post('/permissions', [UserManagementController::class, 'storePermission'])->name('store-permission');
    Route::get('/permissions/{permission}/edit', [UserManagementController::class, 'editPermission'])->name('edit-permission');
    Route::put('/permissions/{permission}', [UserManagementController::class, 'updatePermission'])->name('update-permission');
    Route::delete('/permissions/{permission}', [UserManagementController::class, 'destroyPermission'])->name('destroy-permission');
        
        // Routes pour la gestion du profil
        Route::get('/profile/edit', [UserManagementController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');
});