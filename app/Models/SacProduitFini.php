<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SacProduitFini extends Model
{
    protected $table = 'sacs_produits_finis';

    protected $fillable = [
        'code_sac',
        'stock_produit_fini_id',
        'type_sac',
        'poids_sac_kg',
        'nombre_sacs',
        'variete_code',
        'provenance_decorticage',
        'provenance_etuvage',
        'provenance_paddy',
        'rendement_pourcent',
        'date_emballage',
        'agent_id',
        'statut',
    ];

    protected $casts = [
        'poids_sac_kg' => 'decimal:2',
        'rendement_pourcent' => 'decimal:2',
        'date_emballage' => 'datetime',
    ];

    public function stockProduitFini()
    {
        return $this->belongsTo(StockProduitFini::class, 'stock_produit_fini_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
	
	public function reservoir()
	{
		return $this->belongsTo(Reservoir::class);
	}
	
	public function article()
	{
		return $this->belongsTo(Article::class);
	}
}
