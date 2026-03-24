<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactureClient extends Model
{
    protected $table = 'factures_clients';

    // FIX : point_vente_id et agent_id supprimés (absents de la table DB)
    protected $fillable = [
        'numero_facture',
        'client_id',
        'date_facture',
        'montant_total',
        'montant_paye',
        'solde_restant',
        'statut',
        'date_echeance',
        'jours_credit',
        'auto_numero',
    ];

    protected $casts = [
        'date_facture'  => 'date',
        'date_echeance' => 'date',
        // FIX : decimal au lieu de integer pour ne pas perdre les centimes
        'montant_total'  => 'decimal:0',
        'montant_paye'   => 'decimal:0',
        'solde_restant'  => 'decimal:0',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function commande()
    {
        return $this->hasOne(CommandeVente::class, 'facture_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneFacture::class, 'facture_id');
    }

    public function paiements()
    {
        return $this->hasMany(PaiementFacture::class, 'facture_id');
    }

    public function traitements()
    {
        return $this->hasMany(TraitementClient::class, 'facture_client_id');
    }
	
	public function pointVente(): BelongsTo
    {
        return $this->belongsTo(PointVente::class);
    }
	
	 public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class); // ou User::class selon votre modèle
    }
}