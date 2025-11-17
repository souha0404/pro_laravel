<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'titre',
        'fichier',
    ];

    // Relation vers Demande
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    // Accès à l'étudiant via la demande
    public function etudiant()
    {
        return $this->hasOneThrough(User::class, Demande::class, 'id', 'id', 'demande_id', 'etudiant_id');
    }

    // Ou plus simple : accès via la relation demande
    public function getEtudiantAttribute()
    {
        return $this->demande->etudiant;
    }

    public function getEncadrantAttribute()
    {
        return $this->demande->encadrant;
    }
}