<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametrePrix extends Model
{
    protected $table = 'parametres_prix';

    protected $fillable = [
        'type_produit',
        'poids_sac_kg',
        'variete_rice_id',
        'prix_unitaire_fcfa',
        'unite',
        'actif',
        'date_application',
        'notes',
    ];

    protected $casts = [
        'poids_sac_kg'       => 'decimal:2',
        'prix_unitaire_fcfa' => 'decimal:0',
        'actif'              => 'boolean',
        'date_application'   => 'date',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function variete()
    {
        return $this->belongsTo(VarieteRice::class, 'variete_rice_id');
    }

    // ----------------------------------------------------------------
    // Méthodes métier
    // ----------------------------------------------------------------

    /**
     * Récupère le prix actif pour un type produit + poids sac donnés.
     * Priorité : variété spécifique > toutes variétés
     */
    public static function getPrix(
        string $typeProduit,
        ?float $poidsSacKg = null,
        ?int $varieteId = null,
        string $unite = 'sac'
    ): ?self {
        return self::where('type_produit', $typeProduit)
            ->where('unite', $unite)
            ->where('actif', true)
            ->where(function ($q) use ($poidsSacKg) {
                if ($poidsSacKg) {
                    $q->where('poids_sac_kg', $poidsSacKg);
                } else {
                    $q->whereNull('poids_sac_kg');
                }
            })
            ->orderByRaw('CASE WHEN variete_rice_id = ? THEN 0 ELSE 1 END', [$varieteId ?? 0])
            ->orderBy('date_application', 'desc')
            ->first();
    }
}