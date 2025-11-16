@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des utilisateurs</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">Créer un utilisateur</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->prenom }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role_id }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
