@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mes demandes</h1>
    <a href="{{ route('etudiant.demandes.create') }}" class="btn btn-success mb-3">Créer une demande</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Statut</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demandes as $demande)
            <tr>
                <td>{{ $demande->id }}</td>
                <td>{{ $demande->titre }}</td>
                <td>{{ $demande->statut }}</td>
                <td>{{ $demande->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
