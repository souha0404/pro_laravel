<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Redirection après login selon le rôle
    protected function authenticated($request, $user)
    {
        // Vérifie que le rôle existe
        $role = $user->role ? $user->role->nom : null;

        switch ($role) {
            case 'Admin':
                return redirect('/admin/dashboard');
            case 'Encadrant':
                return redirect('/encadrant/dashboard'); // Interface encadrant
            case 'Étudiant':
                return redirect('/etudiant/dashboard'); // Interface étudiant
            default:
                return redirect('/login')->with('error', 'Rôle non défini.');
        }
    }
}
