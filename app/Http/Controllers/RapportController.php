<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    // Afficher tous les rapports des étudiants pour l'encadrant connecté
    public function index()
    {
        $rapports = Rapport::whereHas('demande', function($query) {
            $query->where('encadrant_id', auth()->id());
        })->get();

        return view('encadrant.rapports.index', compact('rapports'));
    }

    // Télécharger un rapport
    public function download(Rapport $rapport)
    {
        if ($rapport->demande->encadrant_id != auth()->id()) {
            abort(403, 'Accès refusé');
        }

        return Storage::download($rapport->fichier); // Assure-toi que le champ fichier contient le chemin dans storage
    }
}
