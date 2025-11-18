@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Demandes des étudiants</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('encadrant.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Objet</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandes as $demande)
                    <tr>
                        <td>
                            {{ $demande->etudiant->prenom ?? 'Inconnu' }} {{ $demande->etudiant->nom ?? '' }}
                            <br>
                            <small class="text-muted">{{ $demande->etudiant->email ?? '' }}</small>
                        </td>
                        <td>{{ $demande->objet }}</td>
                        <td>
                            <span class="badge bg-{{ $demande->statut === 'acceptée' ? 'success' : ($demande->statut === 'refusée' ? 'danger' : 'warning') }}">
                                {{ ucfirst($demande->statut) }}
                            </span>
                        </td>
                        <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($demande->statut === 'en_attente')
                                <form action="{{ route('encadrant.demandes.update', $demande->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="acceptée">
                                    <button type="submit" class="btn btn-success btn-sm" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir accepter cette demande ?');">
                                        Accepter
                                    </button>
                                </form>

                                <form action="{{ route('encadrant.demandes.update', $demande->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="refusée">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir refuser cette demande ?');">
                                        Refuser
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Demande {{ $demande->statut }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune demande pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection