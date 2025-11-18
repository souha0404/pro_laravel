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

        // Données pour le graphique des demandes par statut
        $demandesByStatut = [
            'En attente' => Demande::where('statut', 'en_attente')->count(),
            'Acceptées' => Demande::where('statut', 'acceptée')->count(),
            'Refusées' => Demande::where('statut', 'refusée')->count(),
        ];

        // Données pour le graphique des rapports par statut
        $rapportsByStatut = [
            'Déposé' => Rapport::where('statut', 'déposé')->count(),
            'En révision' => Rapport::where('statut', 'en_revision')->count(),
            'Validé' => Rapport::where('statut', 'validé')->count(),
            'Rejeté' => Rapport::where('statut', 'rejeté')->count(),
            'Correction requise' => Rapport::where('statut', 'correction_requise')->count(),
        ];

        return view('admin.dashboard', compact(
            'etudiants', 
            'encadrants', 
            'admins',
            'demandes', 
            'rapports',
            'usersByRole',
            'demandesByStatut',
            'rapportsByStatut'
        ));
    }
}