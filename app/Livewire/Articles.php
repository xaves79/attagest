<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class Articles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $form = [
        'id'            => null,
        'nom'           => '',
        'description'   => '',
        'prix_unitaire' => '',
        'stock'         => '',
        'variete_id'    => '',
        'type_produit'  => 'riz_blanchi',
        'taille_sac'    => '1kg',
        'prix_kg'       => '',
        'unite_vente'   => 'sac',
    ];

    public $showModal = false;

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
	{
		$articles = Article::latest()->paginate(10);

		return view('livewire.articles.index', compact('articles'));
	}

    public function query()
    {
        $query = Article::query();

        if ($this->search) {
            $query->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
        }

        return $query;
    }

    public function resetForm()
    {
        $this->form = [
            'id'            => null,
            'nom'           => '',
            'description'   => '',
            'prix_unitaire' => '',
            'stock'         => '',
            'variete_id'    => '',
            'type_produit'  => 'riz_blanchi',
            'taille_sac'    => '1kg',
            'prix_kg'       => '',
            'unite_vente'   => 'sac',
        ];
        $this->showModal = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $this->form = $article->toArray();
        $this->showModal = true;
    }

    public function save()
	{
		// Convertir les champs numériques vides en 0
		$numericFields = ['stock', 'prix_unitaire', 'prix_kg'];
		foreach ($numericFields as $field) {
			if (isset($this->form[$field]) && $this->form[$field] === '') {
				$this->form[$field] = 0;
			} elseif (isset($this->form[$field])) {
				// S'assurer que c'est un nombre (float ou int)
				$this->form[$field] = (float) $this->form[$field];
			}
		}

		$this->validate([
			'form.nom'           => 'required|string|max:255',
			'form.description'   => 'nullable|string',
			'form.prix_unitaire' => 'required|numeric|min:0',
			'form.stock'         => 'nullable|integer|min:0',
			'form.variete_id'    => 'nullable|exists:varietes_rice,id',
			'form.type_produit'  => 'required|string|max:50',
			'form.taille_sac'    => 'required|string|max:10',
			'form.prix_kg'       => 'required|numeric|min:0',
			'form.unite_vente'   => 'required|string|max:20',
		]);

		Article::updateOrCreate(
			['id' => $this->form['id']],
			$this->form
		);

		$this->resetForm();
		$this->dispatch('article-saved');
		session()->flash('message', 'Article enregistré avec succès.');
	}

    public function delete($id)
    {
        Article::findOrFail($id)->delete();
        $this->dispatch('article-deleted');
    }
}
