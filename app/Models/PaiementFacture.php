<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementFacture extends Model
{
    protected $table = 'paiements_factures';

    protected $fillable = [
        'facture_id',
        'numero_paiement',
        'montant_paye',
        'date_paiement',
        'mode_paiement',
        'description',
        'statut',
    ];

    protected $casts = [
		'date_paiement' => 'datetime',
		'montant_paye' => 'integer',  // ✅ INTÉGER
	];

    public function facture()
    {
        return $this->belongsTo(FactureClient::class, 'facture_id');
    }
}
