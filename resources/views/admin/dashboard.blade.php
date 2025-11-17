@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Tableau de bord Admin</h1>
    <p class="lead">Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }} !</p>

    {{-- Statistiques en cartes --}}
    <div class="row mt-4">
        <div class="col-md-2 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <h2>{{ $etudiants + $encadrants + $admins }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">Voir tous →</a>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Demandes</h5>
                    <h2>{{ $demandes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Rapports</h5>
                    <h2>{{ $rapports }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Encadrants</h5>
                    <h2>{{ $encadrants }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Étudiants</h5>
                    <h2>{{ $etudiants }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphique de répartition des utilisateurs --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Répartition des utilisateurs par rôle</h5>
                </div>
                <div class="card-body">
                    <canvas id="usersChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Détails par rôle</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Admin
                            <span class="badge bg-primary rounded-pill">{{ $usersByRole['Admin'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Encadrant
                            <span class="badge bg-success rounded-pill">{{ $usersByRole['Encadrant'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Étudiant
                            <span class="badge bg-info rounded-pill">{{ $usersByRole['Étudiant'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Données pour le graphique
    const usersData = @json($usersByRole);
    
    // Configuration du graphique en donut
    const ctx = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(usersData),
            datasets: [{
                label: 'Nombre d\'utilisateurs',
                data: Object.values(usersData),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',  // Bleu pour Admin
                    'rgba(75, 192, 192, 0.8)',  // Vert pour Encadrant
                    'rgba(153, 102, 255, 0.8)', // Violet pour Étudiant
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' utilisateur(s)';
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection