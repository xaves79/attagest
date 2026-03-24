<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LigneFacture extends Model
{
    protected $table = 'lignes_facture';  // ← NOM EXACT
    
    protected $fillable = [
		'facture_id',
		'type_produit',
		'poids_sac_kg',
		'unite',
		'description',
		'quantite',
		'prix_unitaire',
		'montant',
	];

    public function facture() { return $this->belongsTo(FactureClient::class, 'facture_id'); }
    public function article() { return $this->belongsTo(Article::class); }
}
