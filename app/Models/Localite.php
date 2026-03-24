<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localite extends Model
{
	public $timestamps = false; // ← Ajoutez cette ligne
	
    protected $table = 'localites';

    protected $fillable = [
        'nom',
        'region',
    ];

    protected $casts = [
        'nom'    => 'string',
        'region' => 'string',
    ];
}
