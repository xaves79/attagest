<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementTraitement extends Model
{
    protected $table = 'paiements_traitements'; // ✅ Nom correct de la table

    protected $fillable = [
        'traitement_id',
        'numero_paiement',
        'montant_paye',
        'date_paiement',
        'mode_paiement',
        'description',
        'statut',
    ];

    public function traitement()
    {
        return $this->belongsTo(TraitementClient::class, 'traitement_id');
    }
}