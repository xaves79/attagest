<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecuFournisseur extends Model
{
    protected $table = 'recus_fournisseurs';

    // FIX : variete_rice_id ajouté
    protected $fillable = [
        'numero_recu',
        'achat_paddy_id',
        'fournisseur_id',
        'variete_rice_id',
        'date_recu',
        'montant_total',
        'paye',
        'date_limite_paiement',
        'jours_credit',
        'mode_paiement',
        'acompte',
        'solde_du',
        'reference_entreprise',
        'entreprise_id',
    ];

    protected $casts = [
        'date_recu'            => 'date',
        'date_limite_paiement' => 'date',
        // FIX : paye est une vraie colonne booléenne en DB
        // Les accesseurs ci-dessous sont supprimés pour éviter le conflit
        'paye'                 => 'boolean',
        'montant_total'        => 'decimal:0',
        'acompte'              => 'decimal:0',
        'solde_du'             => 'decimal:0',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function paiements()
    {
        return $this->hasMany(PaiementFournisseur::class, 'recu_fournisseur_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneRecuFournisseur::class, 'recu_fournisseur_id');
    }

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }

    public function achatPaddy()
    {
        return $this->belongsTo(AchatPaddy::class, 'achat_paddy_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    // ----------------------------------------------------------------
    // Méthodes métier (calcul depuis les paiements réels)
    // FIX : renommés pour ne plus écraser les colonnes DB
    // ----------------------------------------------------------------

    public function getMontantPayeReel(): float
    {
        return (float) $this->paiements()->sum('montant');
    }

    public function getSoldeReel(): float
    {
        return $this->montant_total - $this->getMontantPayeReel();
    }

    public function isPayeReel(): bool
    {
        return $this->getSoldeReel() <= 0;
    }

    // ----------------------------------------------------------------
    // Boot
    // ----------------------------------------------------------------

    protected static function booted()
    {
        static::creating(function ($recu) {
            $recu->date_recu = $recu->date_recu ?? now()->format('Y-m-d');

            if (!$recu->numero_recu) {
                $prefix = 'REC-' . now()->format('Y');
                $last = DB::table('recus_fournisseurs')
                    ->where('numero_recu', 'like', $prefix . '%')
                    ->lockForUpdate()
                    ->orderBy('id', 'desc')
                    ->first();
                $nextNumber = $last ? ((int) substr($last->numero_recu, -4) + 1) : 1;
                $recu->numero_recu = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}