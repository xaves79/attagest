<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockSac extends Model
{
    protected $table = 'stocks_sacs';

    // FIX : timestamps = true (la table a created_at/updated_at)
    public $timestamps = true;

    protected $fillable = [
        'point_vente_id',
        'sac_id',
        'quantite',
    ];

    protected $casts = [
        'quantite'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class);
    }

    public function sac()
    {
        return $this->belongsTo(SacProduitFini::class, 'sac_id');
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementSac::class, 'stock_sac_id')->orderBy('date_mouvement');
    }

    // ----------------------------------------------------------------
    // Méthodes métier
    // ----------------------------------------------------------------

    public function entrer(int $quantite, ?int $agentId = null, string $notes = ''): MouvementSac
    {
        if ($quantite <= 0) {
            throw new \InvalidArgumentException('La quantité doit être positive.');
        }

        return DB::transaction(function () use ($quantite, $agentId, $notes) {
            $this->increment('quantite', $quantite);

            return MouvementSac::create([
                'stock_sac_id'   => $this->id,
                'quantite'       => $quantite,
                'type_mouvement' => 'entree',
                'agent_id'       => $agentId,
                'notes'          => $notes,
                'date_mouvement' => now(),
                'unique_hash'    => md5("entree_{$this->id}_{$quantite}_" . now()->toDateTimeString()),
            ]);
        });
    }

    public function sortir(int $quantite, ?int $agentId = null, string $notes = ''): MouvementSac
    {
        if ($quantite <= 0) {
            throw new \InvalidArgumentException('La quantité doit être positive.');
        }

        return DB::transaction(function () use ($quantite, $agentId, $notes) {
            $stock = StockSac::lockForUpdate()->findOrFail($this->id);

            if ($stock->quantite < $quantite) {
                throw new \Exception(
                    "Stock insuffisant : {$stock->quantite} sac(s) disponible(s), {$quantite} demandé(s)."
                );
            }

            $stock->decrement('quantite', $quantite);

            return MouvementSac::create([
                'stock_sac_id'   => $this->id,
                'quantite'       => $quantite,
                'type_mouvement' => 'sortie',
                'agent_id'       => $agentId,
                'notes'          => $notes,
                'date_mouvement' => now(),
                'unique_hash'    => md5("sortie_{$this->id}_{$quantite}_" . now()->toDateTimeString()),
            ]);
        });
    }

    public static function pourPointVente(int $pointVenteId, int $sacId): self
    {
        return self::firstOrCreate(
            ['point_vente_id' => $pointVenteId, 'sac_id' => $sacId],
            ['quantite' => 0]
        );
    }
}