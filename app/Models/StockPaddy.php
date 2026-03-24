<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPaddy extends Model
{
    protected $table = 'stocks_paddy';

    // FIX : date_stockage supprimé (absent de la table DB)
    protected $fillable = [
        'code_stock',
        'lot_paddy_id',
        'agent_id',
        'quantite_stock_kg',
        'emplacement',
        'quantite_restante_kg',
    ];

    protected $casts = [
        'quantite_stock_kg'    => 'decimal:2',
        'quantite_restante_kg' => 'decimal:2',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function lotPaddy()
    {
        return $this->belongsTo(AchatPaddy::class, 'lot_paddy_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // FIX : etuvages() supprimé — stock_paddy_id n'existe plus dans etuvages
    // Les étuvages se retrouvent via lotPaddy->etuvages()
}