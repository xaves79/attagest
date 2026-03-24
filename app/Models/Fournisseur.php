<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    protected $fillable = [
        'type_personne',
        'nom',
        'prenom',
        'raison_sociale',
        'sigle',
        'code_fournisseur',
        'whatsapp',
        'telephone',
        'localite_id',
        'type_fournisseur',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false; // Pas de colonnes updated_at

    public function localite()
    {
        return $this->belongsTo(Localite::class);
    }
}