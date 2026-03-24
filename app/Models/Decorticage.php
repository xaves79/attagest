<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decorticage extends Model
{
    protected $table = 'decorticages';

    protected $fillable = [
        'code_decorticage',
        'lot_riz_etuve_id',
        'agent_id',
        // FIX : lot_paddy_id et variete_rice_id étaient absents
        'lot_paddy_id',
        'variete_rice_id',
        'quantite_paddy_entree_kg',
        'quantite_riz_blanc_kg',
        'quantite_rejet_kg',
        'quantite_brise_kg',
        'quantite_son_kg',
        'taux_rendement',
        'date_debut_decorticage',
        'date_fin_decorticage',
        'date_terminaison',
        'statut',
    ];

    protected $casts = [
        'quantite_paddy_entree_kg' => 'decimal:2',
        'quantite_riz_blanc_kg'    => 'decimal:2',
        'quantite_rejet_kg'        => 'decimal:2',
        'quantite_brise_kg'        => 'decimal:2',
        'quantite_son_kg'          => 'decimal:2',
        'taux_rendement'           => 'decimal:2',
        'date_debut_decorticage'   => 'datetime',
        'date_fin_decorticage'     => 'datetime',
        'date_terminaison'         => 'datetime',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function lotRizEtuve()
    {
        return $this->belongsTo(LotRizEtuve::class, 'lot_riz_etuve_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // FIX : etuvage() et achatPaddy() supprimées (colonnes inexistantes)
    // Le lien vers l'étuvage passe par : decorticage → lotRizEtuve → etuvage
    public function etuvage()
    {
        return $this->lotRizEtuve?->etuvage;
    }

    public function lotPaddy()
    {
        return $this->belongsTo(AchatPaddy::class, 'lot_paddy_id');
    }

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }

    public function stocksProduitsFinis()
    {
        return $this->hasMany(StockProduitFini::class);
    }
}