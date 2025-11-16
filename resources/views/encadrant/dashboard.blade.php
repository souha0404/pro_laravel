<h1>Mes demandes</h1>
<ul>
@foreach($demandes as $demande)
    <li>Demande #{{ $demande->id }} - Statut : {{ $demande->statut }}</li>
@endforeach
</ul>

<h1>Mes rapports</h1>
<ul>
@foreach($rapports as $rapport)
    <li>Rapport #{{ $rapport->id }} - Étudiant : {{ $rapport->etudiant->prenom ?? 'N/A' }}</li>
@endforeach
</ul>
