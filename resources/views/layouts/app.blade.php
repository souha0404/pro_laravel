<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Stages')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome (icônes) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid px-4">
  <a class="navbar-brand" href="{{ url('/') }}">Gestion des Stages</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        @auth
        {{-- Menu pour Admin --}}
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users me-2"></i>Utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.demandes.index') }}">
                        <i class="fas fa-envelope me-2"></i>Demandes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.rapports.index') }}">
                        <i class="fas fa-file-pdf me-2"></i>Rapports
                    </a>
                </li>
            @elseif(auth()->user()->isEncadrant())
                {{-- Menu pour Encadrant --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('encadrant.demandes.index') }}">
                        <i class="fas fa-inbox me-2"></i>Mes demandes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('encadrant.rapports.index') }}">
                        <i class="fas fa-folder-open me-2"></i>Mes rapports
                    </a>
                </li>
            @elseif(auth()->user()->isEtudiant())
                {{-- Menu pour Étudiant --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.demandes.index') }}">
                        <i class="fas fa-paper-plane me-2"></i>Mes demandes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.rapports.index') }}">
                        <i class="fas fa-file-alt me-2"></i>Mes rapports
                    </a>
                </li>
            @endif

            {{-- Déconnexion pour tous --}}
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-link nav-link" type="submit">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>