<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Rapport;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role_id == 2) { // Encadrant
            $demandes = Demande::where('encadrant_id', $user->id)->get();
            $rapports = Rapport::where('encadrant_id', $user->id)->get();
            return view('encadrant.dashboard', compact('demandes', 'rapports'));
        }

        if ($user->role_id == 3) { // Étudiant
            $demandes = Demande::where('etudiant_id', $user->id)->get();
            return view('etudiant.demandes.index', compact('demandes'));
        }
    }
}
