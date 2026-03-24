<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $table = 'comptes';

    protected $fillable = [
        'code_compte',
        'libelle',
        'type_compte',
        'solde_debit',
        'solde_credit',
    ];

    protected $casts = [
        'solde_debit' => 'decimal:2',
        'solde_credit' => 'decimal:2',
    ];

    public function ecrituresDebit()
    {
        return $this->hasMany(EcritureComptable::class, 'compte_debit', 'code_compte');
    }

    public function ecrituresCredit()
    {
        return $this->hasMany(EcritureComptable::class, 'compte_credit', 'code_compte');
    }
}
