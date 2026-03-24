<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointVente extends Model
{
    protected $table = 'points_vente';
    
    public $timestamps = false; // ← Ajoutez ceci

    protected $fillable = [
        'nom',
        'code_point',
        'agent_id',
        'localite_id',
        'adresse',
        'telephone',
        'whatsapp',
        'email',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function localite()
    {
        return $this->belongsTo(Localite::class);
    }
}