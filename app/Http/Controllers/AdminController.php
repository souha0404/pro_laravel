<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Demande;
use App\Models\Rapport;
use App\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques générales
        $etudiants = User::whereHas('role', fn($q) => $q->where('nom', 'Étudiant'))->count();
        $encadrants = User::whereHas('role', fn($q) => $q->where('nom', 'Encadrant'))->count();
        $admins = User::whereHas('role', fn($q) => $q->where('nom', 'Admin'))->count();
        $demandes = Demande::count();
        $rapports = Rapport::count();

        // Données pour le graphique (répartition des utilisateurs par rôle)
        $usersByRole = [
            'Admin' => User::whereHas('role', fn($q) => $q->where('nom', 'Admin'))->count(),
            'Encadrant' => User::whereHas('role', fn($q) => $q->where('nom', 'Encadrant'))->count(),
            'Étudiant' => User::whereHas('role', fn($q) => $q->where('nom', 'Étudiant'))->count(),
        ];

        return view('admin.dashboard', compact(
            'etudiants', 
            'encadrants', 
            'admins',
            'demandes', 
            'rapports',
            'usersByRole'
        ));
    }
}