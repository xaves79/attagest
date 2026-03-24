<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'type_client',
        'nom',
        'prenom',
        'raison_sociale',
        'sigle',
        'code_client',
        'whatsapp',
        'telephone',
        'email',
        'localite_id',
        'point_vente_id',
        'type_achat',
    ];

    protected $casts = [
        'type_client'   => 'string',
        'type_achat'    => 'string',
    ];

    public function localite()
    {
        return $this->belongsTo(Localite::class, 'localite_id');
    }

    public function pointVente()
    {
        return $this->belongsTo(PointVente::class, 'point_vente_id');
    }
}
