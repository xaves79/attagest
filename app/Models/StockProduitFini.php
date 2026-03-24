<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockProduitFini extends Model
{
    protected $table = 'stocks_produits_finis';

    // FIX : achat_paddy_id → lot_paddy_id, statut et categorie ajoutés
    protected $fillable = [
        'code_stock',
        'type_produit',
        'quantite_kg',
        'decorticage_id',
        'agent_id',
        'variete_rice_id',
        'etuvage_id',
        'lot_paddy_id',
        'statut',
        'categorie',
    ];

    protected $casts = [
        'quantite_kg' => 'decimal:2',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function decorticage()
    {
        return $this->belongsTo(Decorticage::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function varieteRice()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }

    // FIX : achatPaddy() renommé en lotPaddy() pour refléter le vrai nom de colonne
    public function lotPaddy()
    {
        return $this->belongsTo(AchatPaddy::class, 'lot_paddy_id');
    }

    public function etuvage()
    {
        return $this->belongsTo(Etuvage::class);
    }

    public function sacs()
    {
        return $this->hasMany(SacProduitFini::class, 'stock_produit_fini_id');
    }

    public function mouvementsReservoir()
    {
        return $this->hasMany(MouvementReservoir::class, 'stock_id');
    }

    // ----------------------------------------------------------------
    // Accesseurs
    // ----------------------------------------------------------------

    public function getNomStockAttribute(): string
    {
        return "{$this->type_produit} ({$this->code_stock})";
    }
}