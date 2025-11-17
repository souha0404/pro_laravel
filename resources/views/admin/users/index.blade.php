@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Gestion des utilisateurs</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Retour au dashboard</a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            Créer un utilisateur
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->nom }}</td>
                        <td>{{ $user->prenom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role_id === 1 ? 'danger' : 'primary' }}">
                                {{ $user->role->nom ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if($user->role_id === 1)
                                {{-- Boutons désactivés pour les admins --}}
                                <button class="btn btn-warning btn-sm" disabled title="Non modifiable">Modifier</button>
                                <button class="btn btn-danger btn-sm" disabled title="Non supprimable">Supprimer</button>
                            @else
                                {{-- Boutons actifs pour les autres utilisateurs --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                      method="POST" 
                                      style="display:inline;"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection