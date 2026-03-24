<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceComptable extends Model
{
    protected $table = 'pieces_comptables';  // ici, le vrai nom de la table

    protected $fillable = [
        'code',
        'libelle',
        'description',
    ];
}
