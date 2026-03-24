<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchatPaddy extends Model
{
    protected $table = 'lots_paddy';

    protected $fillable = [
        'code_lot',
        'agent_id',
        'fournisseur_id',
        'variete_id',
        'localite_id',
        'entreprise_id',
        'date_achat',
        'quantite_achat_kg',
        'prix_achat_unitaire_fcfa',
        'montant_achat_total_fcfa',
        'statut',
        // FIX : quantite_restante_kg était absent
        'quantite_restante_kg',
    ];

    protected $casts = [
        'date_achat'               => 'date',
        'quantite_achat_kg'        => 'decimal:2',
        'quantite_restante_kg'     => 'decimal:2',
        'prix_achat_unitaire_fcfa' => 'decimal:0',
        'montant_achat_total_fcfa' => 'decimal:2',
        'statut'                   => 'string',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_id');
    }

    public function localite()
    {
        return $this->belongsTo(Localite::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function stockPaddy()
    {
        return $this->hasOne(StockPaddy::class, 'lot_paddy_id');
    }

    public function etuvages()
    {
        return $this->hasMany(Etuvage::class, 'lot_paddy_id');
    }

    public function recuFournisseur()
    {
        return $this->hasOne(RecuFournisseur::class, 'achat_paddy_id');
    }

    public function lignesRecu()
    {
        return $this->hasMany(LigneRecuFournisseur::class, 'achat_paddy_id');
    }

    // ----------------------------------------------------------------
    // Accesseurs
    // ----------------------------------------------------------------

    public function getHasRecuAttribute(): bool
    {
        return \DB::table('recu_lignes')->where('achat_paddy_id', $this->id)->exists()
            || \DB::table('recus_fournisseurs')->where('achat_paddy_id', $this->id)->exists();
    }

    // ----------------------------------------------------------------
    // Méthodes métier
    // ----------------------------------------------------------------

    public function creerEcritureComptable()
    {
        $service = new \App\Services\EcritureComptableService();
        return $service->createEcriture(
            libelle: "Achat paddy fournisseur {$this->fournisseur->nom} (lot {$this->code_lot})",
            compteDebit: '601100',
            montantDebit: $this->montant_achat_total_fcfa,
            compteCredit: '401',
            montantCredit: $this->montant_achat_total_fcfa,
            pieceComptable: $this->code_lot,
            dateEcriture: $this->date_achat,
            valide: true
        );
    }
}