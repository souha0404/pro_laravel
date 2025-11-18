@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Rapports des étudiants</h2>

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
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Date de dépôt</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rapports as $rapport)
                    <tr>
                        <td>
                            {{ $rapport->demande->etudiant->prenom ?? 'Inconnu' }} {{ $rapport->demande->etudiant->nom ?? '' }}
                            <br>
                            <small class="text-muted">{{ $rapport->demande->etudiant->email ?? '' }}</small>
                        </td>
                        <td>
                            {{ $rapport->titre }}
                            @if($rapport->commentaire_encadrant)
                                <br><small class="text-muted">💬 {{ $rapport->commentaire_encadrant }}</small>
                            @endif
                        </td>
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
                            <div class="btn-vertical" role="group">
                                {{-- Télécharger toujours disponible --}}
                                <form action="{{ route('encadrant.rapports.download', $rapport->id) }}" 
                                    method="GET" class="mb-1">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-download"></i> Télécharger
                                    </button>
                                </form>
                                
                                {{-- Si statut = déposé : seulement "en_revision" --}}
                                @if($rapport->statut === 'déposé')
                                    <form action="{{ route('encadrant.rapports.update', $rapport->id) }}" 
                                          method="POST" class="mb-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="statut" value="en_revision">
                                        <button type="submit" class="btn btn-info btn-sm w-100">
                                            <i class="fas fa-eye"></i> Mettre en révision
                                        </button>
                                    </form>
                                @endif

                                {{-- Si statut = en_revision : validé, rejeté, correction_requise --}}
                                @if($rapport->statut === 'en_revision')
                                    <form action="{{ route('encadrant.rapports.update', $rapport->id) }}" 
                                          method="POST" class="mb-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="statut" value="validé">
                                        <button type="submit" class="btn btn-success btn-sm w-100" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir valider ce rapport ?');">
                                            <i class="fas fa-check"></i> Valider
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('encadrant.rapports.update', $rapport->id) }}" 
                                          method="POST" class="mb-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="statut" value="rejeté">
                                        <button type="submit" class="btn btn-danger btn-sm w-100"
                                                onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce rapport ?');">
                                            <i class="fas fa-times"></i> Rejeter
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('encadrant.rapports.comment', $rapport->id) }}" 
                                       class="btn btn-warning btn-sm w-100">
                                        <i class="fas fa-pencil-alt"></i> Demander correction
                                    </a>
                                @endif

                                {{-- Statuts finaux : plus d'actions possibles --}}
                                @if(in_array($rapport->statut, ['validé', 'rejeté', 'correction_requise']))
                                    <span class="badge bg-secondary mt-1">Statut final</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun rapport pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection