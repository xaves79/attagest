<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VarieteRice extends Model
{
	public $timestamps = false;
	
    protected $table = 'varietes_rice';

    protected $fillable = [
        'nom',
        'code_variete',
        'type_riz',
        'rendement_estime',
        'duree_cycle',
        'origine',
        'description',
    ];

    protected $casts = [
        'rendement_estime' => 'decimal:2',
        'duree_cycle' => 'integer',
    ];
}
