<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementSac extends Model
{
    protected $table = 'mouvements_sacs';

    public $timestamps = false;

    protected $fillable = [
        'stock_sac_id',
        'quantite',
        'type_mouvement',
        'agent_id',
        'notes',
        'date_mouvement',
        'unique_hash',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'date_mouvement' => 'datetime',
    ];

    public function stockSac()
    {
        return $this->belongsTo(StockSac::class, 'stock_sac_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}