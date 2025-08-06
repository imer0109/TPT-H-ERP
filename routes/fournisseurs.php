<?php

use App\Http\Controllers\FournisseurController;
use Illuminate\Support\Facades\Route;

// Routes pour la gestion des fournisseurs
Route::middleware(['auth'])->group(function () {
    // Routes principales pour les fournisseurs
    Route::get('/fournisseurs/export', [FournisseurController::class, 'export'])->name('fournisseurs.export');
    Route::resource('fournisseurs', FournisseurController::class);
    
    // Routes pour la gestion des documents
    Route::get('/fournisseurs/documents/{document}/download', [FournisseurController::class, 'downloadDocument'])->name('fournisseurs.documents.download');
    Route::get('/fournisseurs/documents/{document}/view', [FournisseurController::class, 'viewDocument'])->name('fournisseurs.documents.view');
    Route::delete('/fournisseurs/documents/{document}', [FournisseurController::class, 'deleteDocument'])->name('fournisseurs.documents.delete');
});