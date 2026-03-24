<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CommandeVente;
use App\Models\PointVente;
use App\Models\Client;

class ListeCommandes extends Component
{
    use WithPagination;

    // ----------------------------------------------------------------
    // Filtres
    // ----------------------------------------------------------------
    public string $search         = '';
    public string $filtreStatut   = '';
    public string $filtreType     = '';
    public string $filtrePointVente = '';
    public string $filtreDateDu   = '';
    public string $filtreDateAu   = '';

    // ----------------------------------------------------------------
    // UI
    // ----------------------------------------------------------------
    public string $successMessage = '';
    public string $errorMessage   = '';

    protected $queryString = [
        'search'          => ['except' => ''],
        'filtreStatut'    => ['except' => ''],
        'filtreType'      => ['except' => ''],
        'filtrePointVente'=> ['except' => ''],
    ];

    // ----------------------------------------------------------------
    // Reset pagination si filtre change
    // ----------------------------------------------------------------
    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFiltreStatut(): void { $this->resetPage(); }
    public function updatingFiltreType(): void   { $this->resetPage(); }

    // ----------------------------------------------------------------
    // Actions
    // ----------------------------------------------------------------
    public function annulerCommande(int $id): void
    {
        try {
            $commande = CommandeVente::findOrFail($id);
            $commande->annuler('Annulation depuis la liste');
            $this->successMessage = "Commande {$commande->code_commande} annulée.";
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function resetFiltres(): void
    {
        $this->search           = '';
        $this->filtreStatut     = '';
        $this->filtreType       = '';
        $this->filtrePointVente = '';
        $this->filtreDateDu     = '';
        $this->filtreDateAu     = '';
        $this->resetPage();
    }

    // ----------------------------------------------------------------
    // Render
    // ----------------------------------------------------------------
    public function render()
    {
        $commandes = CommandeVente::with(['client', 'agent', 'pointVente', 'lignes'])
            ->when($this->search, fn($q) =>
                $q->where(function ($q) {
                    $q->where('code_commande', 'ilike', "%{$this->search}%")
                      ->orWhereHas('client', fn($q) =>
                          $q->where('nom', 'ilike', "%{$this->search}%")
                            ->orWhere('raison_sociale', 'ilike', "%{$this->search}%")
                            ->orWhere('code_client', 'ilike', "%{$this->search}%")
                      );
                })
            )
            ->when($this->filtreStatut,     fn($q) => $q->where('statut', $this->filtreStatut))
            ->when($this->filtreType,       fn($q) => $q->where('type_vente', $this->filtreType))
            ->when($this->filtrePointVente, fn($q) => $q->where('point_vente_id', $this->filtrePointVente))
            ->when($this->filtreDateDu,     fn($q) => $q->whereDate('date_commande', '>=', $this->filtreDateDu))
            ->when($this->filtreDateAu,     fn($q) => $q->whereDate('date_commande', '<=', $this->filtreDateAu))
            ->orderByDesc('created_at')
            ->paginate(15);

        // Totaux pour les KPIs
        $stats = CommandeVente::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN statut NOT IN ('annulee') THEN montant_total_fcfa ELSE 0 END) as ca_total,
            SUM(CASE WHEN statut = 'livree' THEN montant_total_fcfa ELSE 0 END) as ca_livre,
            SUM(CASE WHEN statut IN ('confirmee','en_attente_livraison','partiellement_livree') THEN montant_solde_fcfa ELSE 0 END) as en_attente
        ")->first();

        return view('livewire.commandes.liste-commandes', [
            'commandes'   => $commandes,
            'stats'       => $stats,
            'pointsVente' => PointVente::orderBy('nom')->get(),
        ])->layout('layouts.app');
    }
}