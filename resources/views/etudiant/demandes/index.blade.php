@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Mes demandes</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('etudiant.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
        @if($peutCreerDemande ?? true)
            <a href="{{ route('etudiant.demandes.create') }}" class="btn btn-success">
                Créer une demande
            </a>
        @else
            <button class="btn btn-success" disabled title="Vous avez déjà une demande en attente ou acceptée">
                Créer une demande
            </button>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Encadrant</th>
                        <th>Objet</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandes as $demande)
                    <tr>
                        <td>
                            {{ $demande->encadrant->prenom ?? 'N/A' }} {{ $demande->encadrant->nom ?? '' }}
                            <br>
                            <small class="text-muted">{{ $demande->encadrant->email ?? '' }}</small>
                        </td>
                        <td>{{ $demande->objet }}</td>
                        <td>
                            <span class="badge bg-{{ $demande->statut === 'acceptée' ? 'success' : ($demande->statut === 'refusée' ? 'danger' : 'warning') }}">
                                {{ ucfirst($demande->statut) }}
                            </span>
                        </td>
                        <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune demande pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection