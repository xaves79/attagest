<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransfertPointsVente extends Model
{
    protected $table = 'transferts_points_vente';

    protected $fillable = [
        'code_transfert',
        'stock_riz_id',
        'point_vente_id',
        'agent_id',
        'quantite_transferee_kg',
        'date_transfert',
    ];

    protected $casts = [
        'quantite_transferee_kg' => 'decimal:2',
        'date_transfert' => 'date',
    ];

    public function stockRiz()
    {
        return $this->belongsTo(StockProduitFini::class, 'stock_riz_id');
    }

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class, 'point_vente_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
