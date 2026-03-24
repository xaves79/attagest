<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservoir extends Model
{
    protected $table = 'reservoirs';

    public $timestamps = false; // Pas de colonnes updated_at

    protected $fillable = [
        'nom_reservoir',
        'type_produit',
        'capacite_max_kg',
        'point_vente_id',
        'quantite_actuelle_kg',
    ];

    protected $casts = [
        'capacite_max_kg' => 'decimal:2',
        'quantite_actuelle_kg' => 'decimal:2',
    ];

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementReservoir::class);
    }
}