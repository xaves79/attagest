<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSacPointVente extends Model
{
    public $timestamps = false;
	
	protected $table = 'stocks_sacs_points_vente';

    protected $fillable = [
        'point_vente_id',
        'sac_id',
        'quantite',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function sac()
    {
        return $this->belongsTo(SacProduitFini::class, 'sac_id');
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementSac::class, 'stock_sac_id');
    }
}