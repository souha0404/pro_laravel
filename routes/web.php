<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role_id) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('encadrant.dashboard'),
            3 => redirect()->route('etudiant.dashboard'),
            default => redirect()->route('login'),
        };
    }
    
    // Afficher la page welcome pour les visiteurs non connectés
    return view('welcome');
})->name('welcome');


// ========================
// ADMIN
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':1'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::resource('/admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Dans la section ADMIN, ajoute :
    Route::delete('/admin/demandes/{id}', [DemandeController::class, 'destroy'])
        ->name('admin.demandes.destroy');

    Route::get('/admin/demandes', [DemandeController::class, 'adminIndex'])
        ->name('admin.demandes.index');

    Route::get('/admin/rapports', [RapportController::class, 'adminIndex'])
        ->name('admin.rapports.index');

    Route::delete('/admin/rapports/{id}', [RapportController::class, 'destroy'])
        ->name('admin.rapports.destroy');

    Route::get('/admin/rapports/{rapport}/download', [RapportController::class, 'download'])
        ->name('admin.rapports.download');
});


// ========================
// ENCADRANT
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':2'])->group(function () {
    Route::get('/encadrant/dashboard', [DemandeController::class, 'dashboard'])
        ->name('encadrant.dashboard');

    Route::get('/encadrant/demandes', [DemandeController::class, 'index'])
        ->name('encadrant.demandes.index');

    Route::put('/encadrant/demandes/{id}', [DemandeController::class, 'update'])
        ->name('encadrant.demandes.update');

    Route::get('/encadrant/rapports', [RapportController::class, 'index'])
        ->name('encadrant.rapports.index');

    Route::get('/encadrant/rapports/{rapport}/download', [RapportController::class, 'download'])
        ->name('encadrant.rapports.download');

    Route::put('/encadrant/rapports/{id}', [RapportController::class, 'update'])
        ->name('encadrant.rapports.update');

    Route::get('/encadrant/rapports/{id}/comment', [RapportController::class, 'showCommentForm'])
        ->name('encadrant.rapports.comment');
});


// ========================
// ETUDIANT
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':3'])->group(function () {

    Route::get('/etudiant/dashboard', [DemandeController::class, 'dashboard'])
        ->name('etudiant.dashboard');

    Route::get('/etudiant/demandes', [DemandeController::class, 'index'])
        ->name('etudiant.demandes.index');

    Route::get('/etudiant/demandes/create', [DemandeController::class, 'create'])
        ->name('etudiant.demandes.create');

    Route::post('/etudiant/demandes', [DemandeController::class, 'store'])
        ->name('etudiant.demandes.store');

    Route::get('/etudiant/demandes/{demande}/edit', [DemandeController::class, 'edit'])
        ->name('etudiant.demandes.edit');

    Route::get('/etudiant/rapports', [RapportController::class, 'index'])
        ->name('etudiant.rapports.index');

    Route::get('/etudiant/rapports/create', [RapportController::class, 'create'])
        ->name('etudiant.rapports.create');

    Route::post('/etudiant/rapports', [RapportController::class, 'store'])
        ->name('etudiant.rapports.store');

    Route::get('/etudiant/rapports/{rapport}/download', [RapportController::class, 'download'])
        ->name('etudiant.rapports.download');

    Route::get('/etudiant/rapports/{rapport}/edit', [RapportController::class, 'edit'])
        ->name('etudiant.rapports.edit');

    Route::put('/etudiant/rapports/{rapport}', [RapportController::class, 'update'])
        ->name('etudiant.rapports.update');

    Route::delete('/etudiant/rapports/{rapport}', [RapportController::class, 'destroy'])
        ->name('etudiant.rapports.destroy');
});
