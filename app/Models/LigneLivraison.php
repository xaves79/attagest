<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneLivraison extends Model
{
    protected $table = 'lignes_livraison';

    public $timestamps = false;

    protected $fillable = [
        'livraison_id',
        'ligne_commande_id',
        'stock_sac_id',
        'quantite_livree',
    ];

    protected $casts = [
        'quantite_livree' => 'integer',
        'created_at'      => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function livraison()
    {
        return $this->belongsTo(LivraisonVente::class, 'livraison_id');
    }

    public function ligneCommande()
    {
        return $this->belongsTo(LigneCommandeVente::class, 'ligne_commande_id');
    }

    public function stockSac()
    {
        return $this->belongsTo(StockSac::class, 'stock_sac_id');
    }
}