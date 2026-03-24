<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Agent extends Model
{	
	public $timestamps = false;
    protected $fillable = [
        'nom',
        'prenom',
        'matricule',
        'whatsapp',
        'telephone',
        'email',
        'photo',
        'entreprise_id',
        'date_embauche',
        'actif',
        'poste_id',
        'nom_complet',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'actif'         => 'boolean',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    protected static function booted()
    {
        static::deleting(function ($agent) {
            if ($agent->photo && Storage::disk('public')->exists($agent->photo)) {
                Storage::disk('public')->delete($agent->photo);
            }
        });
    }
}