<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    public $timestamps = false;

    protected $table = 'ventes';

    protected $fillable = [
        'code_vente',
        'commande_id',
        'livraison_id',
        'client_id',
        'agent_id',
        'point_vente_id',
        'type_vente',
        'sac_id',
        'facture_id',
        'quantite_vendue_kg',
        'prix_vente_unitaire_fcfa',
        'montant_vente_total_fcfa',
        'type_produit',
        'date_vente',
        'statut_paiement',
        'remise_fcfa',
    ];

    protected $casts = [
        'date_vente'               => 'date',
        'quantite_vendue_kg'       => 'decimal:2',
        'prix_vente_unitaire_fcfa' => 'decimal:0',
        'montant_vente_total_fcfa' => 'decimal:0',
        'remise_fcfa'              => 'decimal:0',
        'created_at'               => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function commande()
    {
        return $this->belongsTo(CommandeVente::class, 'commande_id');
    }

    public function livraison()
    {
        return $this->belongsTo(LivraisonVente::class, 'livraison_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function sac()
    {
        return $this->belongsTo(SacProduitFini::class, 'sac_id');
    }

    public function facture()
    {
        return $this->belongsTo(FactureClient::class, 'facture_id');
    }

    public function transfert()
    {
        return $this->belongsTo(TransfertPointsVente::class, 'transfert_id');
    }
}