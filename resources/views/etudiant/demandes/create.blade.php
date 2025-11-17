@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Créer une demande</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('etudiant.demandes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="encadrant_id" class="form-label">
                                Sélectionner un encadrant <span class="text-danger">*</span>
                            </label>
                            <select name="encadrant_id" id="encadrant_id" class="form-select @error('encadrant_id') is-invalid @enderror" required>
                                <option value="">-- Choisir un encadrant --</option>
                                @foreach($encadrants as $encadrant)
                                    <option value="{{ $encadrant->id }}" {{ old('encadrant_id') == $encadrant->id ? 'selected' : '' }}>
                                        {{ $encadrant->prenom }} {{ $encadrant->nom }} - {{ $encadrant->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('encadrant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="objet" class="form-label">
                                Objet de la demande <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="objet" 
                                   id="objet" 
                                   class="form-control @error('objet') is-invalid @enderror" 
                                   value="{{ old('objet') }}"
                                   placeholder="Ex: Demande d'encadrement pour mon stage"
                                   required>
                            @error('objet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Décrivez brièvement l'objet de votre demande
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('etudiant.demandes.index') }}" class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Envoyer la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection