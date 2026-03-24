<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneCommandeVente extends Model
{
    protected $table = 'lignes_commande_vente';

    protected $fillable = [
        'commande_id',
        'sac_id',
        'type_produit',
        'poids_sac_kg',
        'quantite',
        'unite',
        'prix_unitaire_fcfa',
        'remise_ligne_fcfa',
        'quantite_livree',
    ];

    protected $casts = [
        'poids_sac_kg'       => 'decimal:2',
        'prix_unitaire_fcfa' => 'integer',
        'remise_ligne_fcfa'  => 'integer',
        'quantite'           => 'integer',
        'quantite_livree'    => 'integer',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------
    public function commande(): BelongsTo
    {
        return $this->belongsTo(CommandeVente::class, 'commande_id');
    }

    public function sac(): BelongsTo
    {
        return $this->belongsTo(SacProduitFini::class, 'sac_id');
    }

    public function variete(): BelongsTo
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }

    // ----------------------------------------------------------------
    // Accesseurs calculés
    // ----------------------------------------------------------------
    public function getSousTotalFcfaAttribute(): int
    {
        return max(0, ($this->quantite * $this->prix_unitaire_fcfa) - $this->remise_ligne_fcfa);
    }

    public function getQuantiteRestanteAttribute(): int
    {
        return max(0, $this->quantite - $this->quantite_livree);
    }

    // ----------------------------------------------------------------
    // Méthode statique : prix suggéré depuis parametres_prix
    // ----------------------------------------------------------------
    public static function getPrixSuggere(
        string $typeProduit,
        ?float $poidsSacKg = null,
        ?int $varieteRiceId = null,
        string $unite = 'sac'
    ): int {
        // 1. Chercher un prix spécifique à la variété + poids
        if ($varieteRiceId && $poidsSacKg) {
            $prix = ParametrePrix::where('type_produit', $typeProduit)
                ->where('poids_sac_kg', $poidsSacKg)
                ->where('variete_rice_id', $varieteRiceId)
                ->where('unite', $unite)
                ->where('actif', true)
                ->orderByDesc('date_application')
                ->value('prix_unitaire_fcfa');

            if ($prix) return (int) $prix;
        }

        // 2. Chercher un prix générique par poids
        if ($poidsSacKg) {
            $prix = ParametrePrix::where('type_produit', $typeProduit)
                ->where('poids_sac_kg', $poidsSacKg)
                ->whereNull('variete_rice_id')
                ->where('unite', $unite)
                ->where('actif', true)
                ->orderByDesc('date_application')
                ->value('prix_unitaire_fcfa');

            if ($prix) return (int) $prix;
        }

        // 3. Chercher un prix au kg (vrac)
        $prix = ParametrePrix::where('type_produit', $typeProduit)
            ->where('unite', $unite)
            ->whereNull('poids_sac_kg')
            ->whereNull('variete_rice_id')
            ->where('actif', true)
            ->orderByDesc('date_application')
            ->value('prix_unitaire_fcfa');

        return (int) ($prix ?? 0);
    }
}