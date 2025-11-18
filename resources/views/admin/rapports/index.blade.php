@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Gestion des rapports</h1>
    
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
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rapports as $rapport)
                    <tr>
                        <td>
                            {{ $rapport->demande->etudiant->prenom ?? 'N/A' }} {{ $rapport->demande->etudiant->nom ?? '' }}
                            <br>
                            <small class="text-muted">{{ $rapport->demande->etudiant->email ?? '' }}</small>
                        </td>
                        <td>
                            {{ $rapport->demande->encadrant->prenom ?? 'N/A' }} {{ $rapport->demande->encadrant->nom ?? '' }}
                            <br>
                            <small class="text-muted">{{ $rapport->demande->encadrant->email ?? '' }}</small>
                        </td>
                        <td>{{ $rapport->titre }}</td>
                        <td>
                            @php
                                $badgeClass = match($rapport->statut ?? 'déposé') {
                                    'déposé' => 'warning',
                                    'en_revision' => 'info',
                                    'validé' => 'success',
                                    'rejeté' => 'danger',
                                    'correction_requise' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $rapport->statut ?? 'déposé')) }}
                            </span>
                        </td>
                        <td>{{ $rapport->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div role="group">
                                @if($rapport->fichier)
                                <form action="{{ route('admin.rapports.download', $rapport->id) }}" 
                                    method="GET" style="display:inline-block">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Télécharger
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.rapports.destroy', $rapport->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ? Cette action est irréversible.');">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucun rapport pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection