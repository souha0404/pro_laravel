<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Rapport;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    // Dashboard (encadrant ou étudiant)
    public function dashboard()
    {
        $user = auth()->user();
        
        if ($user->role_id == 2) { // Encadrant
            // Statistiques pour les graphiques
            $demandesByStatut = [
                'En attente' => Demande::where('encadrant_id', $user->id)->where('statut', 'en_attente')->count(),
                'Acceptées' => Demande::where('encadrant_id', $user->id)->where('statut', 'acceptée')->count(),
                'Refusées' => Demande::where('encadrant_id', $user->id)->where('statut', 'refusée')->count(),
            ];
            
            $totalDemandes = Demande::where('encadrant_id', $user->id)->count();
            
            // Compter les rapports via les demandes acceptées de l'encadrant
            $totalRapports = Rapport::whereHas('demande', function($query) use ($user) {
                $query->where('encadrant_id', $user->id)
                      ->where('statut', 'acceptée');
            })->count();
            
            return view('encadrant.dashboard', compact(
                'demandesByStatut', 
                'totalDemandes', 
                'totalRapports'
            ));
        }
        
        if ($user->role_id == 3) { // Étudiant
            // Statistiques pour les graphiques
            $demandesByStatut = [
                'En attente' => Demande::where('etudiant_id', $user->id)->where('statut', 'en_attente')->count(),
                'Acceptées' => Demande::where('etudiant_id', $user->id)->where('statut', 'acceptée')->count(),
                'Refusées' => Demande::where('etudiant_id', $user->id)->where('statut', 'refusée')->count(),
            ];
            
            $totalDemandes = Demande::where('etudiant_id', $user->id)->count();
            
            // Compter les rapports de l'étudiant (via les demandes acceptées)
            $totalRapports = Rapport::whereHas('demande', function($query) use ($user) {
                $query->where('etudiant_id', $user->id)
                      ->where('statut', 'acceptée');
            })->count();
            
            return view('etudiant.dashboard', compact(
                'demandesByStatut', 
                'totalDemandes', 
                'totalRapports'
            ));
        }
        
        abort(403, 'Accès non autorisé');
    }

    // Liste des demandes (encadrant ou étudiant)
    public function index()
    {
        $user = auth()->user();

        if ($user->role_id == 2) { // Encadrant
            $demandes = Demande::where('encadrant_id', $user->id)
                ->with(['etudiant', 'encadrant'])
                ->get();
            return view('encadrant.demandes.index', compact('demandes'));
        }

        if ($user->role_id == 3) { // Étudiant
            $demandes = Demande::where('etudiant_id', $user->id)
                ->with(['etudiant', 'encadrant'])
                ->get();
            return view('etudiant.demandes.index', compact('demandes'));
        }
        
        abort(403, 'Accès non autorisé');
    }
}