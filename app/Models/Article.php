<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'nom',
        'description',
        'prix_unitaire',
        'stock',
        'variete_id',
        'type_produit',
        'taille_sac',
        'prix_kg',
        'unite_vente',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:0',
        'stock'         => 'integer',
        'prix_kg'       => 'decimal:2',
    ];

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_id');
    }
	
	public function sacsCorrespondants()
	{
		return SacProduitFini::where('type_sac', $this->type_produit)
			->where('poids_sac_kg', floatval($this->taille_sac));
	}
}
