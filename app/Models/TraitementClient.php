<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraitementClient extends Model
{
    protected $table = 'traitements_client';

    // FIX : montant_paye_traitement et solde_traitement supprimés (absents de la table DB)
    // FIX : quantite_brisures_kg → quantite_brise_kg (vrai nom en DB)
    // FIX : point_vente_id ajouté
    protected $fillable = [
        'code_traitement',
        'client_id',
        'agent_id',
        'variete_id',
        'localite_id',
        'point_vente_id',
        'quantite_paddy_kg',
        'date_reception',
        'quantite_riz_blanc_kg',
        'quantite_brise_kg',
        'quantite_son_kg',
        'taux_rendement',
        'prix_traitement_par_kg',
        'montant_traitement_fcfa',
        'statut',
        'observations',
        'facture_client_id',
    ];

    protected $casts = [
        'quantite_paddy_kg'       => 'decimal:2',
        'quantite_riz_blanc_kg'   => 'decimal:2',
        'quantite_brise_kg'       => 'decimal:2',
        'quantite_son_kg'         => 'decimal:2',
        'taux_rendement'          => 'decimal:2',
        'prix_traitement_par_kg'  => 'decimal:2',
        'montant_traitement_fcfa' => 'decimal:0',
        'date_reception'          => 'date',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_id');
    }

    public function localite()
    {
        return $this->belongsTo(Localite::class);
    }

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function facture()
    {
        return $this->belongsTo(FactureClient::class, 'facture_client_id');
    }

    public function paiements()
    {
        return $this->hasMany(PaiementTraitement::class, 'traitement_id');
    }

    // ----------------------------------------------------------------
    // Accesseurs calculés depuis les paiements
    // ----------------------------------------------------------------

    public function getMontantPayeAttribute(): float
    {
        return (float) $this->paiements()->sum('montant_paye');
    }

    public function getSoldeAttribute(): float
    {
        return $this->montant_traitement_fcfa - $this->getMontantPayeAttribute();
    }
}