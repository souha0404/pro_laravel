<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gestion des Stages')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">Gestion des Stages</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        @auth
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
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

</body>
</html>
