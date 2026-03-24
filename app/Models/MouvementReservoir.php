<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementReservoir extends Model
{
    protected $table = 'mouvements_reservoirs';

    protected $fillable = [
        'reservoir_id',
        'stock_id',
        'type_stock',
        'quantite_kg',
        'type_mouvement',
        'agent_id',
        'decorticage_id',
    ];

    protected $casts = [
        'quantite_kg' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function reservoir()
    {
        return $this->belongsTo(Reservoir::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function stock()
    {
        return $this->belongsTo(StockProduitFini::class, 'stock_id');
    }

    public function decorticage()
    {
        return $this->belongsTo(Decorticage::class);
    }
}