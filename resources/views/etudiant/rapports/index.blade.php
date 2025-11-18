@extends('layouts.app')

@section('title', 'Mes rapports')

@section('content')
<div class="container mt-4">
    <h4>Mes rapports</h4>

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

        <a @class(['btn','btn-primary','disabled' => empty($canSubmitRapport)]) 
           @if(!empty($canSubmitRapport))
                href="{{ route('etudiant.rapports.create') }}"
           @else
                role="button" aria-disabled="true" tabindex="-1"
           @endif>
            + Ajouter un rapport
        </a>
    </div>

    @if(empty($canSubmitRapport))
        <div class="alert alert-info">
            <strong>Info :</strong> Vous pourrez déposer un nouveau rapport uniquement si aucun rapport n'a encore été soumis
            ou si votre dernier rapport est en correction.
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Mes rapports déposés</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Demande</th>
                        <th>Statut</th>
                        <th>Date de dépôt</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rapports as $r)
                    <tr>
                        <td>{{ $r->titre }}</td>
                        <td>Demande #{{ $r->demande_id }}</td>
                        <td>
                            @php
                                $badgeClass = match($r->statut ?? 'déposé') {
                                    'déposé' => 'warning',
                                    'en_revision' => 'info',
                                    'validé' => 'success',
                                    'rejeté' => 'danger',
                                    'correction_requise' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $r->statut ?? 'déposé')) }}
                            </span>
                            
                            @if($r->statut === 'correction_requise' && $r->commentaire_encadrant)
                                <div class="alert alert-warning mt-2 p-2">
                                    <small><strong>Commentaire de l'encadrant :</strong></small>
                                    <br>
                                    <small>{{ $r->commentaire_encadrant }}</small>
                                </div>
                            @endif
                        </td>
                        <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($r->fichier)
                            <form action="{{ route('etudiant.rapports.download', $r->id) }}" 
                                method="GET" style="display:inline-block">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Télécharger
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun rapport déposé pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection