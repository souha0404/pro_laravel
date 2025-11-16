<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'encadrant_id',
        'statut',
        'objet',   // ➜ ajoute ce champ si ton formulaire contient "objet"
    ];

    // 🔗 Relation : une demande appartient à un étudiant
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    // 🔗 Relation : une demande appartient à un encadrant
    public function encadrant()
    {
        return $this->belongsTo(User::class, 'encadrant_id');
    }

    // 🔗 Relation : une demande peut avoir plusieurs rapports
    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }
}
