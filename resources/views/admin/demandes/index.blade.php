@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Gestion des demandes</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
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
                        <th>Encadrant</th>
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
                            {{ $demande->etudiant->prenom ?? 'N/A' }} {{ $demande->etudiant->nom ?? '' }}
                            <br>
                            <small class="text-muted">{{ $demande->etudiant->email ?? '' }}</small>
                        </td>
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
                        <td>
                            <form action="{{ route('admin.demandes.destroy', $demande->id) }}" method="POST" style="display:inline-block"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucune demande pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection