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

// Page d'accueil
Route::get('/encadrant/test', function () {
    return view('encadrant.dashboard'); // ou 'encadrant.demandes' si c’est ton view
});

Route::get('/', function () {
    return view('welcome');
});

// Redirection après login selon rôle
Route::get('/home', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect('/login');
    }

    switch ($user->role_id) {
        case 1: // Admin
            return redirect('/admin/dashboard');
        case 2: // Encadrant
            return redirect('/encadrant/dashboard');
        case 3: // Étudiant
            return redirect('/etudiant/dashboard');
        default:
            return redirect('/home');
    }
})->name('home');


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
});


// ========================
// ENCADRANT
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':2'])->group(function () {
    Route::get('/encadrant/dashboard', [DemandeController::class, 'dashboard'])
        ->name('encadrant.dashboard');

    Route::get('/encadrant/demandes', [DemandeController::class, 'index'])
        ->name('encadrant.demandes.index');

    Route::get('/encadrant/rapports', [RapportController::class, 'index'])
        ->name('encadrant.rapports.index');
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

    Route::put('/etudiant/demandes/{demande}', [DemandeController::class, 'update'])
        ->name('etudiant.demandes.update');

    Route::delete('/etudiant/demandes/{demande}', [DemandeController::class, 'destroy'])
        ->name('etudiant.demandes.destroy');

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
