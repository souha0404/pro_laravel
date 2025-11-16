@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Demandes des étudiants</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Statut</th>
                <th>Actions</th>
                <th>Rapports</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandes as $demande)
            <tr>
                <td>{{ $demande->etudiant->prenom ?? 'Inconnu' }}</td>
                <td>{{ $demande->statut }}</td>
                <td>
                    <form action="{{ route('encadrant.demandes.update', $demande->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="statut" value="acceptée">
                        <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                    </form>

                    <form action="{{ route('encadrant.demandes.update', $demande->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="statut" value="refusée">
                        <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('encadrant.rapports.index') }}" class="btn btn-info btn-sm">Voir rapports</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Aucune demande pour le moment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
