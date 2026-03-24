<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcritureComptable extends Model
{
    protected $table = 'ecritures_comptables';

    protected $fillable = [
        'code_ecriture',
        'date_ecriture',
        'libelle',
        'compte_debit',
        'montant_debit',
        'compte_credit',
        'montant_credit',
        'piece_comptable',
        'valide',
    ];

    protected $casts = [
        'montant_debit' => 'decimal:2',
        'montant_credit' => 'decimal:2',
        'date_ecriture' => 'date',
        'valide' => 'boolean',
    ];

    public function compteDebit()
    {
        return $this->belongsTo(Compte::class, 'compte_debit', 'code_compte');
    }

    public function compteCredit()
    {
        return $this->belongsTo(Compte::class, 'compte_credit', 'code_compte');
    }
}
