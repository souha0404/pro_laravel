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

// Page d'accueil
Route::get('/encadrant/test', function () {
    return view('encadrant.dashboard'); // ou 'encadrant.demandes' si c’est ton view
});

Route::get('/', function () {
    return view('welcome');
});

// Auth Laravel (Login + Register)
Auth::routes();

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
            return redirect('/encadrant/demandes');
        case 3: // Étudiant
            return redirect('/etudiant/demandes');
        default:
            return redirect('/login');
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
    Route::get('/encadrant/demandes', [DemandeController::class, 'index'])
        ->name('encadrant.demandes.index');
});


// ========================
// ETUDIANT
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':3'])->group(function () {

    Route::get('/etudiant/demandes', [DemandeController::class, 'index'])
        ->name('etudiant.demandes.index');

    Route::get('/etudiant/demandes/create', [DemandeController::class, 'create'])
        ->name('etudiant.demandes.create');

    Route::post('/etudiant/demandes', [DemandeController::class, 'store'])
        ->name('etudiant.demandes.store');

    Route::post('/etudiant/rapports', [RapportController::class, 'store'])
        ->name('etudiant.rapports.store');
});
