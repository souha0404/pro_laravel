@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bienvenue sur votre espace Étudiant, {{ $user->prenom }}</h1>
    <p>Ici vous pouvez consulter vos demandes et déposer vos rapports.</p>
</div>
@endsection
