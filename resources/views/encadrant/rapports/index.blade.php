@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Rapports des étudiants</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Demande</th>
                <th>Titre</th>
                <th>Fichier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rapports as $rapport)
            <tr>
                <td>{{ $rapport->demande->etudiant->prenom ?? 'Inconnu' }}</td>
                <td>{{ $rapport->demande->statut }}</td>
                <td>{{ $rapport->titre }}</td>
                <td>
                    <a href="{{ route('encadrant.rapports.download', $rapport->id) }}" class="btn btn-primary btn-sm">
                        Télécharger
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Aucun rapport pour le moment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
