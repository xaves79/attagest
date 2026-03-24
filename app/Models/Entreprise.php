<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $table = 'entreprises';
    protected $guarded = [];
    
    // Désactiver les timestamps car la table n'a pas created_at/updated_at
    public $timestamps = false;

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-company.png');
    }
}