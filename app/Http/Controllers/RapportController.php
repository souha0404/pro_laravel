<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isEncadrant()) { // Encadrant
            // Récupérer les rapports des demandes acceptées de cet encadrant
            $rapports = Rapport::whereHas('demande', function($query) use ($user) {
                $query->where('encadrant_id', $user->id)
                      ->where('statut', 'acceptée');
            })->with(['demande.etudiant', 'demande.encadrant'])->latest()->get();

            return view('encadrant.rapports.index', compact('rapports'));
        } 
        elseif ($user->isEtudiant()) {
            $rapports = Rapport::whereHas('demande', function($query) use ($user) {
                $query->where('etudiant_id', $user->id)
                      ->where('statut', 'acceptée');
            })->with(['demande.etudiant', 'demande.encadrant'])->latest()->get();
        
            $dernierRapport = $rapports->first();
            // L'étudiant peut déposer si : aucun rapport OU statut final (correction_requise, rejeté)
            $canSubmitRapport = is_null($dernierRapport) 
                || in_array($dernierRapport->statut, ['correction_requise', 'rejeté']);
        
            return view('etudiant.rapports.index', compact('rapports', 'canSubmitRapport'));
        } else {
            abort(403, 'Accès non autorisé');
        }
    }

    // Afficher le formulaire de création (étudiant)
    public function create()
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un étudiant
        if (!$user->isEtudiant()) {
            abort(403, 'Seuls les étudiants peuvent créer des rapports');
        }

        // Récupérer les rapports de cet étudiant
        $rapports = Rapport::whereHas('demande', function($query) use ($user) {
            $query->where('etudiant_id', $user->id)
                ->where('statut', 'acceptée');
        })->latest()->get();

        // Vérifier si l'étudiant peut déposer un rapport
        $dernierRapport = $rapports->first();
        $canSubmitRapport = is_null($dernierRapport) || $dernierRapport->statut === 'correction_requise';

        if (!$canSubmitRapport) {
            return redirect()->route('etudiant.rapports.index')
                ->with('error', 'Vous ne pouvez pas déposer de nouveau rapport pour le moment.');
        }

        // Récupérer la dernière demande acceptée
        $demandeAcceptee = Demande::where('etudiant_id', $user->id)
            ->where('statut', 'acceptée')
            ->latest()
            ->first();

        if (!$demandeAcceptee) {
            return redirect()->route('etudiant.rapports.index')
                ->with('error', 'Aucune demande acceptée trouvée.');
        }

        return view('etudiant.rapports.create', compact('demandeAcceptee'));
    }

    // Créer un rapport (étudiant)
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isEtudiant()) {
            abort(403, 'Seuls les étudiants peuvent créer des rapports');
        }

        $request->validate([
            'demande_id' => 'required|exists:demandes,id',
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $demande = Demande::findOrFail($request->demande_id);
        
        if ($demande->etudiant_id !== $user->id || $demande->statut !== 'acceptée') {
            abort(403, 'Accès refusé');
        }

        // Vérifier s'il existe un ancien rapport pour cette demande
        $ancienRapport = Rapport::where('demande_id', $request->demande_id)->first();
        
        // Supprimer l'ancien fichier si un nouveau rapport est déposé
        if ($ancienRapport && $ancienRapport->fichier) {
            Storage::disk('public')->delete($ancienRapport->fichier);
            $ancienRapport->delete();
        }

        $fichier = $request->file('fichier')->store('rapports', 'public');

        Rapport::create([
            'demande_id' => $request->demande_id,
            'titre' => $request->titre,
            'fichier' => $fichier,
            'statut' => 'déposé',
        ]);

        return redirect()->route('etudiant.rapports.index')
            ->with('success', 'Rapport déposé avec succès');
    }

    // Mettre à jour le statut d'un rapport (encadrant uniquement)
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $rapport = Rapport::findOrFail($id);

        if (!$user->isEncadrant()) {
            abort(403, 'Seuls les encadrants peuvent modifier les statuts des rapports');
        }

        if ($rapport->demande->encadrant_id !== $user->id) {
            abort(403, 'Ce rapport ne vous appartient pas');
        }

        // Vérifier que le statut actuel permet la modification
        if (in_array($rapport->statut, ['validé', 'rejeté', 'correction_requise'])) {
            return redirect()->back()
                ->with('error', 'Ce rapport a un statut final. L\'étudiant doit déposer un nouveau rapport.');
        }

        $request->validate([
            'statut' => 'required|in:en_revision,validé,rejeté,correction_requise',
            'commentaire' => 'required_if:statut,correction_requise|nullable|string|max:1000',
        ]);

        $rapport->update([
            'statut' => $request->statut,
            'commentaire_encadrant' => $request->commentaire,
        ]);

        return redirect()->route('encadrant.rapports.index')
            ->with('success', 'Statut du rapport mis à jour avec succès');
    }

    // Télécharger un rapport (admin, encadrant ou étudiant)
    public function download($id)
    {
        $user = auth()->user();
        $rapport = Rapport::findOrFail($id);

        // Vérifier que l'utilisateur a accès à ce rapport
        if ($user->isAdmin()) {
            // Admin : accès à tous les rapports
            return Storage::disk('public')->download($rapport->fichier);
        } elseif ($user->isEncadrant()) {
            // Encadrant : seulement ses rapports
            if ($rapport->demande->encadrant_id !== $user->id) {
                abort(403, 'Accès refusé');
            }
        } elseif ($user->isEtudiant()) {
            // Étudiant : seulement ses rapports
            if ($rapport->demande->etudiant_id !== $user->id) {
                abort(403, 'Accès refusé');
            }
        } else {
            abort(403, 'Accès refusé');
        }

        return Storage::disk('public')->download($rapport->fichier);
    }

    // Supprimer un rapport (admin uniquement)
    public function destroy($id)
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un admin
        if (!$user->isAdmin()) {
            abort(403, 'Seuls les administrateurs peuvent supprimer des rapports');
        }

        $rapport = Rapport::findOrFail($id);
        
        // Supprimer le fichier du stockage
        if ($rapport->fichier && Storage::disk('public')->exists($rapport->fichier)) {
            Storage::disk('public')->delete($rapport->fichier);
        }
        
        // Supprimer le rapport de la base de données
        $rapport->delete();

        return redirect()->back()->with('success', 'Rapport supprimé avec succès');
    }

    public function adminIndex()
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un admin
        if (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $rapports = Rapport::with(['demande.etudiant', 'demande.encadrant'])
            ->latest()
            ->get();

        return view('admin.rapports.index', compact('rapports'));
    }

    // Afficher le formulaire de commentaire (encadrant)
    public function showCommentForm($id)
    {
        $user = auth()->user();
        $rapport = Rapport::with(['demande.etudiant'])->findOrFail($id);

        if (!$user->isEncadrant()) {
            abort(403, 'Seuls les encadrants peuvent accéder à cette page');
        }

        if ($rapport->demande->encadrant_id !== $user->id) {
            abort(403, 'Ce rapport ne vous appartient pas');
        }

        if ($rapport->statut !== 'en_revision') {
            return redirect()->route('encadrant.rapports.index')
                ->with('error', 'Vous ne pouvez demander une correction que pour un rapport en révision');
        }

        return view('encadrant.rapports.comment', compact('rapport'));
    }
}