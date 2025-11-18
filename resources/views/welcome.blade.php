@extends('layouts.guest')

@section('content')
<div class="text-center">
    <h1 class="display-4 mb-4">
        <i class="fas fa-graduation-cap text-primary"></i>
        Bienvenue sur Gestion des Stages
    </h1>
    
    <p class="lead mb-5">
        Plateforme de gestion des stages pour étudiants et encadrants
    </p>
    
    <div class="d-flex gap-3 justify-content-center">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-user-plus me-2"></i>S'inscrire
        </a>
    </div>
    
    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5>Pour les Étudiants</h5>
                    <p class="text-muted">Déposez vos demandes de stage et vos rapports facilement</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                    <h5>Pour les Encadrants</h5>
                    <p class="text-muted">Gérez les demandes et évaluez les rapports de stage</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-cog fa-3x text-info mb-3"></i>
                    <h5>Pour les Administrateurs</h5>
                    <p class="text-muted">Supervisez toute l'activité de la plateforme</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection