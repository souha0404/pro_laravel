@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une demande</h1>
    <form action="{{ route('etudiant.demandes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>
        <button class="btn btn-primary">Envoyer</button>
    </form>
</div>
@endsection
