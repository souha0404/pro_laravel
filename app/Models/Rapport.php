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
        'fichier', // chemin du fichier dans storage
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
