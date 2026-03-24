<?php

namespace App\Livewire;

use App\Models\AchatPaddy;
use App\Models\RecuFournisseur;
use App\Models\Fournisseur;
use App\Models\VarieteRice;
use App\Services\RecuFournisseurService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class RecusFournisseursCrud extends Component
{
    use WithPagination;

    // Propriétés pour la liste et les filtres
    public $search = '';
    public $filterFournisseur = '';
    public $filterStatut = '';
    public $filterDateDebut = '';
    public $filterDateFin = '';
    public $perPage = 10;

    // Propriétés du formulaire (reçu)
    public $recu_id = null;
    public $numero_recu;
    public $fournisseur_id;
    public $date_recu;
    public $montant_total = 0;
    public $paye = false;
    public $date_limite_paiement;
    public $jours_credit = 60;
    public $mode_paiement = 'espece';
    public $variete_rice_id;
    public $entreprise_id = 1;
    public $achat_paddy_id = null;

    // Lignes du reçu (provenant des achats ou manuelles)
    public $lignes = [];

    // Contrôle du formulaire
    public $showForm = false;
    public $hidden = false;

    // Propriétés pour le modal de paiement
    public $showPaiementModal = false;
    public $paiement_recu_id;
    public $paiement_montant;
    public $paiement_mode = 'espece';
    public $paiement_reference;
    public $paiement_notes;

    // Propriétés pour le modal de détails
    public $showDetailModal = false;
    public $detail = null;

    // Listes déroulantes
    public $fournisseurs = [];
    public $varietes = [];

    // Achats disponibles (pour le select)
    public $achatsDisponibles = [];

    // Pour le sélecteur d'achat
    public $showAchatSelector = false;

    protected function rules()
    {
        return [
            'numero_recu' => [
                'required',
                'string',
                'max:20',
                Rule::unique('recus_fournisseurs', 'numero_recu')->ignore($this->recu_id),
            ],
            'fournisseur_id'   => 'required|exists:fournisseurs,id',
            'date_recu'        => 'required|date',
            'montant_total'    => 'required|numeric|min:0',
            'paye'             => 'boolean',
            'date_limite_paiement' => 'nullable|date',
            'jours_credit'     => 'nullable|integer|min:0',
            'mode_paiement'    => 'nullable|in:espece,cheque,mobile_money,credit,virement',
            'variete_rice_id'  => 'nullable|exists:varietes_rice,id',
            'entreprise_id'    => 'nullable|exists:entreprises,id',
            'achat_paddy_id'   => 'nullable|exists:lots_paddy,id',
            'lignes.*.achat_paddy_id'   => 'nullable|exists:lots_paddy,id',
            'lignes.*.variete_rice_id'  => 'required_with:lignes.*.achat_paddy_id|exists:varietes_rice,id',
            'lignes.*.quantite_kg'      => 'required_with:lignes.*.achat_paddy_id|numeric|min:0',
            'lignes.*.prix_unitaire'    => 'required_with:lignes.*.achat_paddy_id|numeric|min:0',
        ];
    }

    public function mount()
    {
        $this->fournisseurs = Fournisseur::orderBy('nom')->get();
        $this->varietes = VarieteRice::orderBy('nom')->get();
        $this->chargerAchatsDisponibles();
        $this->resetForm();
    }

    public function render()
    {
        $query = RecuFournisseur::with(['fournisseur', 'paiements', 'lignes.variete'])
            ->where('entreprise_id', $this->entreprise_id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('numero_recu', 'like', "%{$this->search}%")
                  ->orWhereHas('fournisseur', fn($q) => $q->where('nom', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterFournisseur) {
            $query->where('fournisseur_id', $this->filterFournisseur);
        }

        if ($this->filterDateDebut) {
            $query->whereDate('date_recu', '>=', $this->filterDateDebut);
        }
        if ($this->filterDateFin) {
            $query->whereDate('date_recu', '<=', $this->filterDateFin);
        }

        $recus = $query->latest()->paginate($this->perPage);

        // Appliquer le filtre statut après la pagination (car le statut est calculé)
        if ($this->filterStatut) {
            $recus->getCollection()->transform(function ($r) {
                $totalPaye = $r->paiements ? $r->paiements->sum('montant') : 0;
                $statut = $totalPaye <= 0 ? 'impayé' : ($totalPaye < $r->montant_total ? 'partiel' : 'payé');
                $r->statut_calcule = $statut;
                return $r;
            });

            $recus->setCollection(
                $recus->getCollection()->filter(function ($r) {
                    return $r->statut_calcule === $this->filterStatut;
                })
            );
        }

        return view('livewire.recus-fournisseurs-crud', [
            'recus' => $recus,
            'fournisseurs' => $this->fournisseurs,
            'varietes' => $this->varietes,
            'achatsDisponibles' => $this->achatsDisponibles,
            'showAchatSelector' => $this->showAchatSelector,
            'showDetailModal' => $this->showDetailModal,
            'detail' => $this->detail,
            'showForm' => $this->showForm, // ← crucial pour le modal
        ]);
    }

    // Charge les achats qui n'ont pas encore de reçu associé
    public function chargerAchatsDisponibles()
    {
        $this->achatsDisponibles = AchatPaddy::with(['fournisseur', 'variete'])
            ->whereNotIn('id', function ($query) {
                $query->select('achat_paddy_id')
                      ->from('recu_lignes')
                      ->whereNotNull('achat_paddy_id');
            })
            ->whereNotIn('id', function ($query) {
                $query->select('achat_paddy_id')
                      ->from('recus_fournisseurs')
                      ->whereNotNull('achat_paddy_id');
            })
            ->orderBy('date_achat', 'desc')
            ->get();
    }

    // Ajoute un achat aux lignes
    public function ajouterAchat($achatId)
    {
        $achat = AchatPaddy::with('variete')->findOrFail($achatId);

        foreach ($this->lignes as $ligne) {
            if (($ligne['achat_paddy_id'] ?? null) == $achatId) {
                session()->flash('error', 'Cet achat est déjà dans la liste.');
                return;
            }
        }

        $this->lignes[] = [
            'achat_paddy_id'   => $achat->id,
            'variete_rice_id'  => $achat->variete_id,
            'quantite_kg'      => (float) $achat->quantite_achat_kg,
            'prix_unitaire'    => (float) $achat->prix_achat_unitaire_fcfa,
            'sous_total'       => (float) $achat->montant_achat_total_fcfa,
        ];

        $this->showAchatSelector = false;
        $this->calculerMontantTotal();
    }

    // Ajoute une ligne manuelle
    public function addLigne()
    {
        $this->lignes[] = [
            'achat_paddy_id'   => null,
            'variete_rice_id'  => null,
            'quantite_kg'      => 0,
            'prix_unitaire'    => 0,
            'sous_total'       => 0,
        ];
    }

    // Supprime une ligne
    public function removeLigne($index)
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
        $this->calculerMontantTotal();
    }

    // Calcule le montant total à partir des lignes
    protected function calculerMontantTotal()
    {
        $total = 0;
        foreach ($this->lignes as $ligne) {
            $total += ($ligne['quantite_kg'] ?? 0) * ($ligne['prix_unitaire'] ?? 0);
        }
        $this->montant_total = $total;
    }

    // Réinitialise le formulaire
    public function resetForm()
    {
        $this->recu_id = null;
        $this->numero_recu = '';
        $this->fournisseur_id = '';
        $this->date_recu = now()->format('Y-m-d');
        $this->montant_total = 0;
        $this->paye = false;
        $this->date_limite_paiement = '';
        $this->jours_credit = 60;
        $this->mode_paiement = 'espece';
        $this->variete_rice_id = '';
        $this->achat_paddy_id = null;
        $this->lignes = [];
        $this->resetErrorBag();
    }

    // Génère un numéro de reçu unique
    public function generateNumeroRecu()
    {
        $prefix = 'REC-' . now()->format('Y') . '-';
        $last = RecuFournisseur::where('numero_recu', 'like', $prefix . '%')
            ->orderBy('numero_recu', 'desc')
            ->first();

        if ($last) {
            $num = (int) substr($last->numero_recu, -4) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    // Créer un nouveau reçu
    public function create()
	{
		$this->resetForm();
		$this->numero_recu = $this->generateNumeroRecu();
		$this->showForm = true;
		\Log::info('showForm = true');
	}

    // Éditer un reçu existant
    public function edit($id)
    {
        $recu = RecuFournisseur::with('lignes.variete')->findOrFail($id);

        $this->recu_id = $recu->id;
        $this->numero_recu = $recu->numero_recu;
        $this->fournisseur_id = $recu->fournisseur_id;
        $this->date_recu = $recu->date_recu->format('Y-m-d');
        $this->montant_total = $recu->montant_total;
        $this->paye = $recu->paye;
        $this->date_limite_paiement = $recu->date_limite_paiement?->format('Y-m-d');
        $this->jours_credit = $recu->jours_credit;
        $this->mode_paiement = $recu->mode_paiement;
        $this->variete_rice_id = $recu->variete_rice_id;
        $this->entreprise_id = $recu->entreprise_id;
        $this->achat_paddy_id = $recu->achat_paddy_id;

        $this->lignes = $recu->lignes->map(fn($l) => [
            'id'               => $l->id,
            'achat_paddy_id'   => $l->achat_paddy_id,
            'variete_rice_id'  => $l->variete_rice_id,
            'quantite_kg'      => (float) $l->quantite_kg,
            'prix_unitaire'    => (float) $l->prix_unitaire,
            'sous_total'       => (float) $l->sous_total,
        ])->toArray();

        $this->showForm = true;
    }

    // Afficher les détails
    public function show($id)
    {
        $this->detail = RecuFournisseur::with(['fournisseur', 'lignes.variete', 'paiements'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    // Sauvegarder le reçu
    public function save()
    {
        $this->validate();

        $data = [
            'numero_recu'           => $this->numero_recu,
            'fournisseur_id'        => $this->fournisseur_id,
            'date_recu'             => $this->date_recu,
            'montant_total'         => $this->montant_total,
            'date_limite_paiement'  => $this->date_limite_paiement,
            'jours_credit'          => $this->jours_credit,
            'mode_paiement'         => $this->mode_paiement,
            'variete_rice_id'       => $this->variete_rice_id,
            'entreprise_id'         => $this->entreprise_id,
            'achat_paddy_id'        => $this->achat_paddy_id,
        ];

        $this->date_limite_paiement = $this->date_limite_paiement ?: null;

        try {
            if ($this->recu_id) {
                $recu = RecuFournisseur::findOrFail($this->recu_id);
                $service = new RecuFournisseurService();
                $service->mettreAJour($recu, $data, $this->lignes);
                session()->flash('message', 'Reçu mis à jour avec succès.');
            } else {
                $recu = RecuFournisseur::create($data);
                foreach ($this->lignes as $ligne) {
                    if (!empty($ligne['variete_rice_id']) && ($ligne['quantite_kg'] ?? 0) > 0) {
                        // Convertir en entiers pour correspondre à la table
                        $quantite = (int) round($ligne['quantite_kg']);
                        $prix = (int) $ligne['prix_unitaire'];
                        $sousTotal = $quantite * $prix;

                        $recu->lignes()->create([
                            'achat_paddy_id'   => $ligne['achat_paddy_id'] ?? null,
                            'variete_rice_id'  => $ligne['variete_rice_id'],
                            'quantite_kg'      => $quantite,
                            'prix_unitaire'    => $prix,
                            'sous_total'       => $sousTotal,
                        ]);
                    }
                }
                session()->flash('message', 'Reçu créé avec succès.');
            }

            $this->chargerAchatsDisponibles();
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
            return;
        }

        $this->resetForm();
        $this->showForm = false;
    }

    // Supprimer un reçu
    public function delete($id)
    {
        $recu = RecuFournisseur::findOrFail($id);

        if ($recu->paiements()->exists()) {
            session()->flash('error', 'Impossible de supprimer ce reçu car des paiements y sont rattachés.');
            return;
        }

        $recu->delete();
        session()->flash('message', 'Reçu supprimé avec succès.');
    }

    // Ouvrir le modal de paiement
    public function ouvrirModalPaiement($recuId)
    {
        $recu = RecuFournisseur::findOrFail($recuId);
        $this->paiement_recu_id = $recu->id;
        $totalPaye = $recu->paiements()->sum('montant') ?? 0;
        $this->paiement_montant = $recu->montant_total - $totalPaye;
        $this->paiement_mode = 'espece';
        $this->paiement_reference = null;
        $this->paiement_notes = null;
        $this->showPaiementModal = true;
    }

    // Enregistrer un paiement
    public function enregistrerPaiement()
    {
        $this->validate([
            'paiement_montant' => 'required|numeric|min:0.01',
            'paiement_mode'    => 'required|in:espece,cheque,mobile_money,virement',
        ]);

        $recu = RecuFournisseur::findOrFail($this->paiement_recu_id);
        $service = new RecuFournisseurService();

        try {
            $service->enregistrerPaiement(
                $recu,
                (float) $this->paiement_montant,
                $this->paiement_mode,
                $this->paiement_reference,
                $this->paiement_notes
            );
            session()->flash('message', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $this->showPaiementModal = false;
        $this->reset(['paiement_recu_id', 'paiement_montant', 'paiement_mode', 'paiement_reference', 'paiement_notes']);
    }

    // Télécharger le PDF du reçu
    public function telechargerPdf($recuId)
    {
        $recu = RecuFournisseur::with(['fournisseur', 'entreprise', 'lignes.variete'])->findOrFail($recuId);
        $pdf = Pdf::loadView('pdf.recu-fournisseur', compact('recu'));
        $pdf->setPaper('A5', 'portrait');
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "recu_{$recu->numero_recu}.pdf"
        );
    }

    // Écouter l'événement depuis AchatsPaddy
    #[On('createFromAchatPaddy')]
    public function createFromAchatPaddy($achatPaddyId)
    {
        $achat = AchatPaddy::with(['fournisseur', 'variete'])->findOrFail($achatPaddyId);

        $this->resetForm();
        $this->numero_recu = $this->generateNumeroRecu();
        $this->fournisseur_id = $achat->fournisseur_id;
        $this->date_recu = $achat->date_achat->format('Y-m-d');
        $this->variete_rice_id = $achat->variete_id;
        $this->entreprise_id = $achat->entreprise_id;
        $this->achat_paddy_id = $achat->id;

        $this->lignes = [
            [
                'achat_paddy_id'   => $achat->id,
                'variete_rice_id'  => $achat->variete_id,
                'quantite_kg'      => (float) $achat->quantite_achat_kg,
                'prix_unitaire'    => (float) $achat->prix_achat_unitaire_fcfa,
                'sous_total'       => (float) $achat->montant_achat_total_fcfa,
            ],
        ];

        $this->montant_total = (float) $achat->montant_achat_total_fcfa;
        $this->showForm = true;
        $this->dispatch('form-filled-from-achat');
    }
}