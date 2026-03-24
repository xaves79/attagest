<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecuLigne extends Model
{
    protected $table = 'recu_lignes';

    protected $fillable = [
        'recu_fournisseur_id',
        'achat_paddy_id',
        'variete_rice_id',
        'quantite_kg',
        'prix_unitaire',
        'sous_total',
    ];

    // FIX : decimal au lieu de integer (colonne corrigée en DB)
    protected $casts = [
        'quantite_kg'   => 'decimal:2',
        'prix_unitaire' => 'decimal:0',
        'sous_total'    => 'decimal:0',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function recu()
    {
        return $this->belongsTo(RecuFournisseur::class, 'recu_fournisseur_id');
    }

    public function achat()
    {
        return $this->belongsTo(AchatPaddy::class, 'achat_paddy_id');
    }

    // FIX : méthode variete() déclarée une seule fois (doublon supprimé)
    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }
}