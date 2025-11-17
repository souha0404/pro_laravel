<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    // Afficher tous les rapports des étudiants pour l'encadrant connecté
    public function index()
    {
        $rapports = Rapport::whereHas('demande', function($query) {
            $query->where('encadrant_id', auth()->id())
                  ->where('statut', 'acceptée'); // Seulement les demandes acceptées
        })->with('demande.etudiant')->get();

        return view('encadrant.rapports.index', compact('rapports'));
    }

    // Créer un rapport (étudiant)
    public function store(Request $request)
    {
        $request->validate([
            'demande_id' => 'required|exists:demandes,id',
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Vérifier que la demande appartient à l'étudiant connecté
        $demande = Demande::findOrFail($request->demande_id);
        
        if ($demande->etudiant_id !== auth()->id()) {
            abort(403, 'Cette demande ne vous appartient pas');
        }

        // Vérifier que la demande est acceptée
        if ($demande->statut !== 'acceptée') {
            abort(403, 'Vous ne pouvez déposer un rapport que pour une demande acceptée');
        }

        $fichier = $request->file('fichier')->store('rapports', 'public');

        Rapport::create([
            'demande_id' => $request->demande_id,
            'titre' => $request->titre,
            'fichier' => $fichier,
        ]);

        return redirect()->back()->with('success', 'Rapport déposé avec succès');
    }

    // Télécharger un rapport
    public function download(Rapport $rapport)
    {
        // Vérifier que l'encadrant a accès à ce rapport
        if ($rapport->demande->encadrant_id != auth()->id()) {
            abort(403, 'Accès refusé');
        }

        return Storage::disk('public')->download($rapport->fichier);
    }
}