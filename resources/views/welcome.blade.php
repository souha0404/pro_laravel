<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Encadrant & Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow-sm p-4" style="width: 400px;">
            <div class="text-center mb-4">
                <h2>Bienvenue</h2>
                <p>Connectez-vous ou créez un compte</p>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Se connecter</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">S'inscrire</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
