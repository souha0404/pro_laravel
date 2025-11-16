@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tableau de bord Admin</h1>
    <p>Bienvenue, {{ auth()->user()->prenom }} !</p>

    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Gérer les utilisateurs</a>
</div>
@endsection
