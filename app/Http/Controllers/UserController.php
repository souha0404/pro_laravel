<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Afficher tous les utilisateurs (Admin)
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }

    // Formulaire d’ajout d’un utilisateur
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Enregistrer un utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required',
        ]);

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur ajouté avec succès');
    }

    // Modifier un utilisateur
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour');
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé');
    }
}
