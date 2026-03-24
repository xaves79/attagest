<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneRecuFournisseur extends Model
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

    protected $casts = [
        'quantite_kg'   => 'float',
        'prix_unitaire' => 'float',
        'sous_total'    => 'float',
    ];

    public function recu()
    {
        return $this->belongsTo(RecuFournisseur::class, 'recu_fournisseur_id');
    }

    public function achat()
	{
		return $this->belongsTo(AchatPaddy::class, 'achat_paddy_id');
	}

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }
}