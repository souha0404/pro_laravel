<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gestion des Stages')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="@auth
          @if(auth()->user()->isAdmin())
              {{ route('admin.dashboard') }}
          @elseif(auth()->user()->isEncadrant())
              {{ route('encadrant.dashboard') }}
          @elseif(auth()->user()->isEtudiant())
              {{ route('etudiant.dashboard') }}
          @endif
      @else
          {{ route('home') }}
      @endauth">Gestion des Stages</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        @auth
            {{-- Menu pour Admin --}}
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                </li>
                <li class="nav-item">
                    {{--<a class="nav-link" href="{{ route('admin.demandes.index') }}">Demandes</a>--}}
                </li>
                <li class="nav-item">
                    {{--<a class="nav-link" href="{{ route('admin.rapports.index') }}">Rapports</a>--}}
                </li>
            @elseif(auth()->user()->isEncadrant())
                {{-- Menu pour Encadrant --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('encadrant.demandes.index') }}">Mes demandes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('encadrant.rapports.index') }}">Mes rapports</a>
                </li>
            @elseif(auth()->user()->isEtudiant())
                {{-- Menu pour Étudiant --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.demandes.index') }}">Mes demandes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etudiant.rapports.index') }}">Mes rapports</a>
                </li>
            @endif

            {{-- Déconnexion pour tous --}}
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-link nav-link" type="submit">Déconnexion</button>
                </form>
            </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>