@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Demander une correction</h2>

    <div class="mb-3">
        <a href="{{ route('encadrant.rapports.index') }}" class="btn btn-secondary">← Retour à la liste</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Rapport de l'étudiant</h6>
            <p class="mb-1">
                <strong>Étudiant :</strong> 
                {{ $rapport->demande->etudiant->prenom }} {{ $rapport->demande->etudiant->nom }}
            </p>
            <p class="mb-1"><strong>Titre :</strong> {{ $rapport->titre }}</p>
            <p class="mb-0"><strong>Statut actuel :</strong> 
                <span class="badge bg-info">{{ ucfirst($rapport->statut) }}</span>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Commentaire pour l'étudiant</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('encadrant.rapports.update', $rapport->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="statut" value="correction_requise">

                <div class="mb-3">
                    <label class="form-label">
                        Expliquez les corrections à apporter : <span class="text-danger">*</span>
                    </label>
                    <textarea name="commentaire" 
                              class="form-control @error('commentaire') is-invalid @enderror" 
                              rows="6" 
                              placeholder="Décrivez les modifications nécessaires..."
                              required>{{ old('commentaire') }}</textarea>
                    @error('commentaire')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Maximum 1000 caractères</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        📝 Envoyer la demande de correction
                    </button>
                    <a href="{{ route('encadrant.rapports.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection