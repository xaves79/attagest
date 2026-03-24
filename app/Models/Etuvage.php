<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etuvage extends Model
{
    protected $table = 'etuvages';

    // FIX : fillable aligné sur le vrai schéma
    // Supprimés : achat_paddy_id, stock_paddy_id, masse_entree_kg, masse_sortie_kg
    // Ajoutés   : lot_paddy_id, quantite_paddy_entree_kg
    protected $fillable = [
        'code_etuvage',
        'lot_paddy_id',
        'agent_id',
        'quantite_paddy_entree_kg',
        'date_debut_etuvage',
        'temperature_etuvage',
        'duree_etuvage_minutes',
        'date_fin_etuvage',
        'statut',
    ];

    protected $casts = [
        'quantite_paddy_entree_kg' => 'decimal:2',
        'temperature_etuvage'      => 'decimal:2',
        'date_debut_etuvage'       => 'datetime',
        'date_fin_etuvage'         => 'datetime',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    // FIX : achatPaddy() et stockPaddy() remplacées par lotPaddy()
    public function lotPaddy()
    {
        return $this->belongsTo(AchatPaddy::class, 'lot_paddy_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function lotsRizEtuve()
    {
        return $this->hasMany(LotRizEtuve::class, 'provenance_etuvage_id');
    }

    public function decorticages()
    {
        return $this->hasManyThrough(
            Decorticage::class,
            LotRizEtuve::class,
            'provenance_etuvage_id',
            'lot_riz_etuve_id'
        );
    }
}