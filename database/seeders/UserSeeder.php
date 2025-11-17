<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 1, // Admin
        ]);

        // Optionnel : créer des utilisateurs de test
        User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'encadrant@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // Encadrant
        ]);

        User::create([
            'nom' => 'Martin',
            'prenom' => 'Marie',
            'email' => 'etudiant@example.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // Étudiant
        ]);
    }
}
