<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RecuFournisseur;
use App\Models\VarieteRice;

class DetailsRecuFournisseur extends Model
{
    protected $table = 'details_recus_fournisseurs';

    protected $fillable = [
        'recu_id',
        'description',
        'variete_rice_id',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected $casts = [
        'quantite'      => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'sous_total'    => 'decimal:0',
    ];

    public function recu()
    {
        return $this->belongsTo(RecuFournisseur::class, 'recu_id');
    }

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }
}
