@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Modifier l'utilisateur</h1>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label">Prénom *</label>
                        <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                               id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password">
                        <small class="form-text text-muted">Laisser vide pour ne pas changer le mot de passe (minimum 6 caractères si modifié)</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Rôle *</label>
                    <select class="form-select @error('role_id') is-invalid @enderror" 
                            id="role_id" name="role_id" required>
                        <option value="">-- Sélectionner un rôle --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->nom }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Le rôle Admin n'est pas disponible</small>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection