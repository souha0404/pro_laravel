@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
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
                    <a href="{{ route('admin.demandes.index') }}" class="text-white text-decoration-none">Voir toutes →</a>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Rapports</h5>
                    <h2>{{ $rapports }}</h2>
                    <a href="{{ route('admin.rapports.index') }}" class="text-white text-decoration-none">Voir tous →</a>
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Répartition des utilisateurs par rôle</h5>
                </div>
                <div class="card-body">
                    <canvas id="usersChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Répartition des demandes par statut</h5>
                </div>
                <div class="card-body">
                    <canvas id="demandesChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Évolution des rapports (6 derniers mois)</h5>
                </div>
                <div class="card-body">
                    <canvas id="rapportsChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Données pour le graphique des utilisateurs
    const usersData = @json($usersByRole);
    
    // Configuration du graphique en donut pour les utilisateurs
    const ctxUsers = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(ctxUsers, {
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

    // Données pour le graphique des demandes
    const demandesData = @json($demandesByStatut);
    
    // Configuration du graphique en donut pour les demandes
    const ctxDemandes = document.getElementById('demandesChart').getContext('2d');
    const demandesChart = new Chart(ctxDemandes, {
        type: 'doughnut',
        data: {
            labels: Object.keys(demandesData),
            datasets: [{
                label: 'Nombre de demandes',
                data: Object.values(demandesData),
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',  // Jaune pour En attente
                    'rgba(75, 192, 192, 0.8)',  // Vert pour Acceptées
                    'rgba(255, 99, 132, 0.8)',  // Rouge pour Refusées
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
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
                            label += context.parsed + ' demande(s)';
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Données pour le graphique des rapports
    const rapportsData = @json($rapportsByMonth);
    
    // Configuration du graphique en barres pour les rapports
    const ctxRapports = document.getElementById('rapportsChart').getContext('2d');
    const rapportsChart = new Chart(ctxRapports, {
        type: 'bar',
        data: {
            labels: Object.keys(rapportsData),
            datasets: [{
                label: 'Nombre de rapports',
                data: Object.values(rapportsData),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' rapport(s)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection