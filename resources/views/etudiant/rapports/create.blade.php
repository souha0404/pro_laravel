@extends('layouts.app')

@section('title', 'Déposer un rapport')

@section('content')
<div class="container mt-4">
    <h4>Déposer un nouveau rapport</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('etudiant.rapports.index') }}" class="btn btn-secondary">← Retour à la liste</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Demande associée</h6>
            <p class="mb-1"><strong>Demande #{{ $demandeAcceptee->id }}</strong> - {{ $demandeAcceptee->objet }}</p>
            <p class="mb-0 text-muted">
                <small>Encadrant : {{ $demandeAcceptee->encadrant->prenom ?? '' }} {{ $demandeAcceptee->encadrant->nom ?? '' }}</small>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('etudiant.rapports.store') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Champ caché pour la demande -->
                <input type="hidden" name="demande_id" value="{{ $demandeAcceptee->id }}">
                
                <div class="mb-3">
                    <label class="form-label">Titre du rapport :</label>
                    <input type="text" name="titre" class="form-control" value="{{ old('titre') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fichier (PDF/DOCX) :</label>
                    <input type="file" name="fichier" class="form-control" accept=".pdf,.doc,.docx" required>
                    <small class="text-muted">Taille maximale : 10 Mo</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Déposer le rapport</button>
                    <a href="{{ route('etudiant.rapports.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection