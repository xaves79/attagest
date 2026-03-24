<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LivraisonVente extends Model
{
    protected $table = 'livraisons_vente';

    protected $fillable = [
        'code_livraison',
        'commande_id',
        'agent_id',
        'point_vente_id',
        'date_livraison',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_livraison' => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function commande()
    {
        return $this->belongsTo(CommandeVente::class, 'commande_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneLivraison::class, 'livraison_id');
    }

    // ----------------------------------------------------------------
    // Méthodes métier
    // ----------------------------------------------------------------

    /**
     * Crée une livraison complète avec ses lignes en une transaction.
     * Le trigger DB gère le débit stock et la mise à jour du statut commande.
     *
     * @param CommandeVente $commande
     * @param array $lignes  [['ligne_commande_id' => x, 'stock_sac_id' => y, 'quantite_livree' => z]]
     * @param int $agentId
     * @param string|null $notes
     */
    public static function creer(
        CommandeVente $commande,
        array $lignes,
        int $agentId,
        ?string $notes = null
    ): self {
        return DB::transaction(function () use ($commande, $lignes, $agentId, $notes) {

            // Générer code livraison
            $prefix = 'LIV-' . now()->format('Y-');
            $last = DB::table('livraisons_vente')
                ->where('code_livraison', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->value('code_livraison');

            $num = $last ? ((int) substr($last, -4) + 1) : 1;
            $code = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

            $livraison = self::create([
                'code_livraison' => $code,
                'commande_id'    => $commande->id,
                'agent_id'       => $agentId,
                'point_vente_id' => $commande->point_vente_id,
                'date_livraison' => now(),
                'statut'         => 'effectuee',
                'notes'          => $notes,
            ]);

            // Insérer les lignes — les triggers DB gèrent le reste
            foreach ($lignes as $l) {
                LigneLivraison::create([
                    'livraison_id'     => $livraison->id,
                    'ligne_commande_id'=> $l['ligne_commande_id'],
                    'stock_sac_id'     => $l['stock_sac_id'] ?? null,
                    'quantite_livree'  => $l['quantite_livree'],
                ]);
            }

            // Créer l'écriture dans le journal ventes
            self::enregistrerVente($livraison, $commande);

            return $livraison;
        });
    }

    /**
     * Enregistre la vente dans le journal comptable
     */
    private static function enregistrerVente(self $livraison, CommandeVente $commande): void
    {
        $lignesCommande = $commande->lignes()
            ->whereIn('id', $livraison->lignes()->pluck('ligne_commande_id'))
            ->get();

        $montant          = $lignesCommande->sum('sous_total_fcfa');
        $quantiteTotaleKg = $lignesCommande->sum(fn($l) => ($l->quantite ?? 0) * ($l->poids_sac_kg ?? 1));
        $prixUnitaire     = $quantiteTotaleKg > 0
            ? round($montant / $quantiteTotaleKg, 2)
            : ($lignesCommande->first()?->prix_unitaire_fcfa ?? 0);

        $remise           = $commande->remise_fcfa ?? 0;
        $montantNet       = max(0, ($montant ?: $commande->montant_total_fcfa) - $remise);

        Vente::create([
            'code_vente'               => 'VNT-' . now()->format('Y') . '-' . str_pad($livraison->id, 4, '0', STR_PAD_LEFT),
            'commande_id'              => $commande->id,
            'livraison_id'             => $livraison->id,
            'client_id'                => $commande->client_id,
            'agent_id'                 => $livraison->agent_id,
            'point_vente_id'           => $livraison->point_vente_id,
            'type_vente'               => $commande->type_vente,
            'montant_vente_total_fcfa' => $montantNet,
            'quantite_vendue_kg'       => $quantiteTotaleKg ?: 0,
            'prix_vente_unitaire_fcfa' => $prixUnitaire ?: 0,
            'type_produit'             => $lignesCommande->first()?->type_produit ?? 'riz_blanc',
            'statut_paiement'          => $commande->type_vente === 'comptant' ? 'paye' : 'non_paye',
            'date_vente'               => now()->toDateString(),
            'facture_id'               => $commande->facture_id ?? null,
            'remise_fcfa'              => $remise,
        ]);
    }
}