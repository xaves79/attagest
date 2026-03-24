<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotRizEtuve extends Model
{
    protected $table = 'lots_riz_etuve';

    protected $fillable = [
        'code_lot',
        'quantite_entree_kg',
        'quantite_restante_kg',
        'masse_apres_kg',
        'provenance_etuvage_id',
        'variete_rice_id',
        'date_production',
    ];

    protected $casts = [
        'quantite_entree_kg'   => 'decimal:2',
        'quantite_restante_kg' => 'decimal:2',
        'masse_apres_kg'       => 'decimal:2',
        // FIX : colonnes GENERATED — lecture seule, cast ajouté
        'perte_kg'             => 'decimal:2',
        'rendement_pourcentage' => 'decimal:2',
        'date_production'      => 'datetime',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    // Colonnes calculées par PostgreSQL — jamais en fillable
    protected $guarded = ['perte_kg', 'rendement_pourcentage'];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function etuvage()
    {
        return $this->belongsTo(Etuvage::class, 'provenance_etuvage_id');
    }

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }

    // FIX : relation manquante
    public function decorticages()
    {
        return $this->hasMany(Decorticage::class, 'lot_riz_etuve_id');
    }
}