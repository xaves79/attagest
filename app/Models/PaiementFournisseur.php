<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementFournisseur extends Model
{
    protected $table = 'paiements_fournisseurs';

    protected $fillable = [
        'recu_fournisseur_id',
        'date_paiement',
        'montant',
        'mode_paiement',
        'reference',
        'notes',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant'       => 'decimal:2',
    ];

    public function recu(): BelongsTo
    {
        return $this->belongsTo(RecuFournisseur::class, 'recu_fournisseur_id');
    }
}