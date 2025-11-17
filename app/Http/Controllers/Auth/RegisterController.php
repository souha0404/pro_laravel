<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request; // <-- Correct !

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home'; // après login

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role_id' => ['required', 'integer', 'in:2,3'], // Encadrant ou Étudiant
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);
    }

    // Après l'inscription, redirige vers login
    protected function registered(Request $request, $user)
    {
        return redirect()->route('login')->with('success', 'Inscription réussie. Connectez-vous !');
    }
}
