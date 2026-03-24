<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommandeVente extends Model
{
    protected $table = 'commandes_vente';

    protected $fillable = [
        'code_commande',
        'type_vente',
        'statut',
        'client_id',
        'agent_id',
        'point_vente_id',
        'facture_id',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'date_echeance',
        'montant_total_fcfa',
        'montant_acompte_fcfa',
        'remise_fcfa',
        'notes',
    ];

    protected $casts = [
        'date_commande'         => 'date',
        'date_livraison_prevue' => 'date',
        'date_livraison_reelle' => 'date',
        'date_echeance'         => 'date',
        'montant_total_fcfa'    => 'decimal:0',
        'montant_acompte_fcfa'  => 'decimal:0',
        'montant_solde_fcfa'    => 'decimal:0',  // colonne GENERATED
        'remise_fcfa'           => 'decimal:0',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    // Colonnes générées — jamais en fillable
    protected $guarded = ['montant_solde_fcfa'];

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

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function facture()
    {
        return $this->belongsTo(FactureClient::class, 'facture_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommandeVente::class, 'commande_id');
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonVente::class, 'commande_id');
    }

    public function reservations()
    {
        return $this->hasMany(ReservationStock::class, 'commande_id');
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class, 'commande_id');
    }

    // ----------------------------------------------------------------
    // Accesseurs
    // ----------------------------------------------------------------

    public function getEstLivreeAttribute(): bool
    {
        return $this->statut === 'livree';
    }

    public function getEstAnticipatoinAttribute(): bool
    {
        return $this->type_vente === 'anticipation';
    }

    public function getEstCreditAttribute(): bool
    {
        return $this->type_vente === 'credit';
    }

    // ----------------------------------------------------------------
    // Méthodes métier
    // ----------------------------------------------------------------

    /**
     * Recalcule et met à jour montant_total depuis les lignes
     */
    public function recalculerTotal(): void
    {
        $total = $this->lignes()->sum('sous_total_fcfa');
        $this->update(['montant_total_fcfa' => max(0, $total - $this->remise_fcfa)]);
    }

    /**
     * Confirme la commande et réserve le stock si anticipation
     */
    public function confirmer(): void
    {
        if ($this->statut !== 'brouillon') {
            throw new \Exception("Seule une commande en brouillon peut être confirmée.");
        }

        DB::transaction(function () {
            $nouveauStatut = $this->type_vente === 'anticipation'
                ? 'en_attente_livraison'
                : 'confirmee';

            $this->update(['statut' => $nouveauStatut]);

            // Réserver le stock pour les ventes par anticipation
            if ($this->type_vente === 'anticipation') {
                foreach ($this->lignes as $ligne) {
                    if ($ligne->sac_id) {
                        $stockSac = StockSac::where('sac_id', $ligne->sac_id)
                            ->where('point_vente_id', $this->point_vente_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$stockSac || $stockSac->quantite < $ligne->quantite) {
                            throw new \Exception(
                                "Stock insuffisant pour réserver : {$ligne->type_produit} — " .
                                "Disponible : " . ($stockSac?->quantite ?? 0) . " sac(s), " .
                                "Demandé : {$ligne->quantite} sac(s)."
                            );
                        }

                        ReservationStock::create([
                            'commande_id'      => $this->id,
                            'ligne_id'         => $ligne->id,
                            'stock_sac_id'     => $stockSac->id,
                            'quantite_reservee'=> $ligne->quantite,
                            'statut'           => 'active',
                            'date_expiration'  => $this->date_livraison_prevue,
                        ]);
                    }
                }
            }
        });
    }

    /**
     * Annule la commande et libère les réservations
     */
    public function annuler(string $raison = ''): void
    {
        if (in_array($this->statut, ['livree', 'annulee'])) {
            throw new \Exception("Cette commande ne peut plus être annulée.");
        }

        DB::transaction(function () use ($raison) {
            // Libérer les réservations actives
            $this->reservations()
                ->where('statut', 'active')
                ->update(['statut' => 'liberee', 'updated_at' => now()]);

            $this->update([
                'statut' => 'annulee',
                'notes'  => $this->notes . ($raison ? "\nAnnulation : {$raison}" : ''),
            ]);
        });
    }
}