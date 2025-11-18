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
        $users = User::with('role')->get(); // Afficher tous les utilisateurs y compris les admins
        return view('admin.users.index', compact('users'));
    }

    // Formulaire d’ajout d’un utilisateur
    public function create()
    {
        $roles = Role::where('id', '!=', 1)->get(); // Exclure Admin (id = 1)
        return view('admin.users.create', compact('roles'));
    }

    // Enregistrer un utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id|not_in:1', // Empêcher la création d'admin
        ]);

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès');
    }

    // Modifier un utilisateur
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la modification des admins
        if ($user->isAdmin()) {
            abort(403, 'Vous ne pouvez pas modifier un administrateur');
        }
        
        // Exclure le rôle Admin de la liste
        $roles = Role::where('id', '!=', 1)->get();
        return view('admin.users.edit', compact('user', 'roles'));    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la modification des admins
        if ($user->isAdmin()) {
            abort(403, 'Vous ne pouvez pas modifier un administrateur');
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role_id' => 'required|exists:roles,id|not_in:1', // Empêcher de changer en admin
        ]);

        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la suppression des admins
        if ($user->isAdmin()) {
            abort(403, 'Vous ne pouvez pas supprimer un administrateur');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès');
    }
}
