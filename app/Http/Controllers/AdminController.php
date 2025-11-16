<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Demande;
use App\Models\Rapport;

class AdminController extends Controller
{
    public function dashboard()
    {
        $etudiants = User::whereHas('role', fn($q) => $q->where('nom', 'Étudiant'))->count();
        $encadrants = User::whereHas('role', fn($q) => $q->where('nom', 'Encadrant'))->count();
        $demandes = Demande::count();
        $rapports = Rapport::count();

        return view('admin.dashboard', compact('etudiants', 'encadrants', 'demandes', 'rapports'));
    }
}
