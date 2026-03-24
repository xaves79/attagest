<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationStock extends Model
{
    protected $table = 'reservations_stock';

    protected $fillable = [
        'commande_id',
        'ligne_id',
        'stock_sac_id',
        'quantite_reservee',
        'statut',
        'date_reservation',
        'date_expiration',
    ];

    protected $casts = [
        'quantite_reservee' => 'integer',
        'date_reservation'  => 'datetime',
        'date_expiration'   => 'date',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function commande()
    {
        return $this->belongsTo(CommandeVente::class, 'commande_id');
    }

    public function ligne()
    {
        return $this->belongsTo(LigneCommandeVente::class, 'ligne_id');
    }

    public function stockSac()
    {
        return $this->belongsTo(StockSac::class, 'stock_sac_id');
    }

    // ----------------------------------------------------------------
    // Méthodes métier
    // ----------------------------------------------------------------

    public function liberer(): void
    {
        if ($this->statut !== 'active') return;
        $this->update(['statut' => 'liberee']);
    }

    public function estExpiree(): bool
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }
}