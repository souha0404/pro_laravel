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
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Retour au dashboard</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Étudiant</th>
                        <th>Encadrant</th>
                        <th>Titre</th>
                        <th>Fichier</th>
                        <th>Date de création</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rapports as $rapport)
                    <tr>
                        <td>{{ $rapport->id }}</td>
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
                            @if($rapport->fichier)
                                <a href="{{ Storage::url($rapport->fichier) }}" target="_blank" class="btn btn-primary btn-sm">
                                    Télécharger
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $rapport->created_at->format('d/m/Y H:i') }}</td>
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
@endsection