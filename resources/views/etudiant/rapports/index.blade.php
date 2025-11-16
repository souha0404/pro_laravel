@extends('layouts.app')

@section('title', 'Mes rapports')

@section('content')
<h4>Mes rapports</h4>

<form method="POST" action="{{ route('etudiant.rapports.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Titre :</label>
        <input type="text" name="titre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Fichier (PDF/DOCX) :</label>
        <input type="file" name="fichier" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Demande associée :</label>
        <input type="number" name="demande_id" class="form-control" required>
    </div>
    <button class="btn btn-success">Déposer</button>
</form>

<hr>

<table class="table table-bordered mt-3">
    <thead class="table-dark">
        <tr>
            <th>Titre</th>
            <th>Statut</th>
            <th>Commentaires</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rapports as $r)
        <tr>
            <td>{{ $r->titre }}</td>
            <td>{{ $r->statut }}</td>
            <td>{{ $r->commentaires }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
