@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <h1>Tableau de bord Étudiant</h1>
    <p class="lead">Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }} !</p>

    {{-- Cartes avec statistiques et liens --}}
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Mes demandes</h5>
                    <h2>{{ $totalDemandes ?? 0 }}</h2>
                    <a href="{{ route('etudiant.demandes.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-eye"></i> Voir toutes mes demandes
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Mes rapports</h5>
                    <h2>{{ $totalRapports ?? 0 }}</h2>
                    <a href="{{ route('etudiant.rapports.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-eye"></i> Voir tous mes rapports
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques en donut --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Répartition des rapports par statut</h5>
                </div>
                <div class="card-body">
                    <canvas id="demandesChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistiques de mes rapports</h5>
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
    // Données pour le graphique des demandes
    const demandesData = @json($demandesByStatut ?? []);
    
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
    const rapportsData = @json($rapportsByStatut ?? []);

    const ctxRapports = document.getElementById('rapportsChart').getContext('2d');
    const rapportsChart = new Chart(ctxRapports, {
        type: 'doughnut',
        data: {
            labels: Object.keys(rapportsData),
            datasets: [{
                label: 'Nombre de rapports',
                data: Object.values(rapportsData),
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',  // Jaune pour Déposé
                    'rgba(54, 162, 235, 0.8)',  // Bleu pour En révision
                    'rgba(75, 192, 192, 0.8)',  // Vert pour Validé
                    'rgba(255, 99, 132, 0.8)',  // Rouge pour Rejeté
                    'rgba(255, 159, 64, 0.8)',  // Orange pour Correction requise
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
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
                            label += context.parsed + ' rapport(s)';
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