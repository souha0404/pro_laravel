<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    // Dashboard (encadrant ou étudiant)
    public function dashboard()
    {
        $user = auth()->user();
        
        if ($user->isEncadrant()) { // Encadrant
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
            
            $rapportsByStatut = [
                'Déposé' => Rapport::whereHas('demande', fn($q) => $q->where('encadrant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'déposé')->count(),
                'En révision' => Rapport::whereHas('demande', fn($q) => $q->where('encadrant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'en_revision')->count(),
                'Validé' => Rapport::whereHas('demande', fn($q) => $q->where('encadrant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'validé')->count(),
                'Rejeté' => Rapport::whereHas('demande', fn($q) => $q->where('encadrant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'rejeté')->count(),
                'Correction requise' => Rapport::whereHas('demande', fn($q) => $q->where('encadrant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'correction_requise')->count(),
            ];
            
            return view('encadrant.dashboard', compact(
                'demandesByStatut', 
                'totalDemandes', 
                'totalRapports',
                'rapportsByStatut'
            ));
        }
        
        if ($user->isEtudiant()) { // Étudiant
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
            
            $rapportsByStatut = [
                'Déposé' => Rapport::whereHas('demande', fn($q) => $q->where('etudiant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'déposé')->count(),
                'En révision' => Rapport::whereHas('demande', fn($q) => $q->where('etudiant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'en_revision')->count(),
                'Validé' => Rapport::whereHas('demande', fn($q) => $q->where('etudiant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'validé')->count(),
                'Rejeté' => Rapport::whereHas('demande', fn($q) => $q->where('etudiant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'rejeté')->count(),
                'Correction requise' => Rapport::whereHas('demande', fn($q) => $q->where('etudiant_id', $user->id)->where('statut', 'acceptée'))
                    ->where('statut', 'correction_requise')->count(),
            ];
            
            return view('etudiant.dashboard', compact(
                'demandesByStatut', 
                'totalDemandes', 
                'totalRapports',
                'rapportsByStatut'
            ));
        }
        
        // Si le rôle n'est pas reconnu, rediriger vers home
        return redirect()->route('/')->with('error', 'Accès non autorisé');
    }

    // Liste des demandes (encadrant ou étudiant)
    public function index()
    {
        $user = auth()->user();

        if ($user->isEncadrant()) { // Encadrant
            $demandes = Demande::where('encadrant_id', $user->id)
                ->with(['etudiant', 'encadrant'])
                ->latest()
                ->get();
            return view('encadrant.demandes.index', compact('demandes'));
        }

        if ($user->isEtudiant()) { // Étudiant
            $demandes = Demande::where('etudiant_id', $user->id)
                ->with(['etudiant', 'encadrant'])
                ->latest()
                ->get();
            
            // Vérifier si l'étudiant peut créer une nouvelle demande
            $peutCreerDemande = $this->peutCreerDemande($user->id);
            
            return view('etudiant.demandes.index', compact('demandes', 'peutCreerDemande'));
        }
        
        abort(403, 'Accès non autorisé');
    }

    // Formulaire de création d'une demande (étudiant)
    public function create()
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un étudiant
        if (!$user->isEtudiant()) {
            abort(403, 'Seuls les étudiants peuvent créer des demandes');
        }

        // Vérifier si l'étudiant peut créer une demande
        if (!$this->peutCreerDemande($user->id)) {
            return redirect()->route('etudiant.demandes.index')
                ->with('error', 'Vous avez déjà une demande en attente ou acceptée. Vous ne pouvez pas créer une nouvelle demande.');
        }

        // Récupérer tous les encadrants disponibles
        $encadrants = User::whereHas('role', function($query) {
            $query->where('nom', 'Encadrant');
        })->get();

        return view('etudiant.demandes.create', compact('encadrants'));
    }

    // Enregistrer une demande (étudiant)
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un étudiant
        if (!$user->isEtudiant()) {
            abort(403, 'Seuls les étudiants peuvent créer des demandes');
        }

        // Vérifier si l'étudiant peut créer une demande
        if (!$this->peutCreerDemande($user->id)) {
            return redirect()->route('etudiant.demandes.index')
                ->with('error', 'Vous avez déjà une demande en attente ou acceptée. Vous ne pouvez pas créer une nouvelle demande.');
        }

        $request->validate([
            'encadrant_id' => 'required|exists:users,id',
            'objet' => 'required|string|max:255',
        ]);

        // Vérifier que l'encadrant sélectionné est bien un encadrant
        $encadrant = User::findOrFail($request->encadrant_id);
        if (!$encadrant->isEncadrant()) {
            return back()->withErrors(['encadrant_id' => 'L\'utilisateur sélectionné n\'est pas un encadrant'])->withInput();
        }

        Demande::create([
            'etudiant_id' => $user->id,
            'encadrant_id' => $request->encadrant_id,
            'objet' => $request->objet,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('etudiant.demandes.index')->with('success', 'Demande envoyée avec succès');
    }

    // Mettre à jour une demande (encadrant - accepter/refuser)
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $demande = Demande::findOrFail($id);

        // Vérifier que l'utilisateur est un encadrant
        if (!$user->isEncadrant()) {
            abort(403, 'Seuls les encadrants peuvent modifier les demandes');
        }

        // Vérifier que la demande appartient à cet encadrant
        if ($demande->encadrant_id !== $user->id) {
            abort(403, 'Cette demande ne vous appartient pas');
        }

        $request->validate([
            'statut' => 'required|in:acceptée,refusée',
        ]);

        $demande->update([
            'statut' => $request->statut,
        ]);

        return redirect()->route('encadrant.demandes.index')
            ->with('success', 'Demande ' . ($request->statut === 'acceptée' ? 'acceptée' : 'refusée') . ' avec succès');
    }

    // Supprimer une demande (admin uniquement)
    public function destroy($id)
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un admin
        if (!$user->isAdmin()) {
            abort(403, 'Seuls les administrateurs peuvent supprimer des demandes');
        }

        $demande = Demande::findOrFail($id);
        $demande->delete();

        return redirect()->back()->with('success', 'Demande supprimée avec succès');
    }

    // Méthode helper pour vérifier si un étudiant peut créer une demande
    private function peutCreerDemande($etudiantId): bool
    {
        // Vérifier s'il existe une demande en attente ou acceptée
        $demandeActive = Demande::where('etudiant_id', $etudiantId)
            ->whereIn('statut', ['en_attente', 'acceptée'])
            ->exists();

        return !$demandeActive; // Peut créer seulement s'il n'y a pas de demande active
    }

    public function adminIndex()
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un admin
        if (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $demandes = Demande::with(['etudiant', 'encadrant'])
            ->latest()
            ->get();

        return view('admin.demandes.index', compact('demandes'));
    }
}