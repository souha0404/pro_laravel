@extends('layouts.guest')

@section('content')
<div class="text-center mb-4">
    <h4 class="fw-bold mb-2">Connexion</h4>
    <p class="text-muted">Connectez-vous à votre compte</p>
</div>

@if (session('status'))
    <div class="alert alert-success mb-3">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input id="email" 
                   type="email" 
                   name="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" 
                   placeholder="votre@email.com"
                   required 
                   autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Mot de passe -->
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input id="password" 
                   type="password" 
                   name="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="••••••••"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Se souvenir de moi -->
    <div class="mb-3 form-check">
        <input class="form-check-input" 
               type="checkbox" 
               name="remember" 
               id="remember" 
               {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">
            Se souvenir de moi
        </label>
    </div>

    <!-- Bouton de connexion -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
        </button>
    </div>

    <!-- Mot de passe oublié -->
    @if (Route::has('password.request'))
        <div class="text-center mb-3">
            <a href="{{ route('password.request') }}" class="text-decoration-none">
                <i class="fas fa-key me-1"></i>Mot de passe oublié ?
            </a>
        </div>
    @endif

    <!-- Lien vers inscription -->
    <hr class="my-4">
    <div class="text-center">
        <p class="text-muted mb-2">Vous n'avez pas encore de compte ?</p>
        <a href="{{ route('register') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-plus me-2"></i>Créer un compte
        </a>
    </div>
</form>
@endsection