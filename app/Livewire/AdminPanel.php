<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPanel extends Component
{
    public $selectedTables = [];
    public $message = '';
    public $error = '';

    // Propriétés pour la création d'un super admin
    public $name;
    public $email;
    public $password;

    // Liste des tables (publique)
    public $tables = [
		'achats_paddy',
		'agents',
		'articles',
		'clients',
		'comptes',
		'decorticages',
		'details_recus_fournisseurs',
		'ecritures_comptables',
		'entreprises',
		'etuvages',
		'factures_clients',
		'fournisseurs',
		'lignes_facture',
		'localites',
		'lots_riz_etuve',
		'migrations',
		'mouvements_reservoirs',
		'paiements_factures',
		'paiements_fournisseurs',
		'password_reset_tokens',
		'pieces_comptables',
		'points_vente',
		'postes',
		'recu_lignes',
		'recus_fournisseurs',
		'reservoirs',
		'sacs_produits_finis',
		'sessions',
		'stocks_paddy',
		'stocks_produits_finis',
		'traitements_client',
		'transferts_points_vente',
		'users',
		'varietes_rice',
		'ventes',
	];

    public function render()
    {
        return view('livewire.admin-panel');
    }

    public function clearSelectedTables()
    {
        if (empty($this->selectedTables)) {
            $this->error = 'Veuillez sélectionner au moins une table.';
            return;
        }

        try {
            DB::statement('SET session_replication_role = replica');

            foreach ($this->selectedTables as $table) {
                DB::table($table)->truncate();
            }

            DB::statement('SET session_replication_role = origin');

            $this->message = 'Les tables sélectionnées ont été vidées avec succès.';
            $this->error = '';
        } catch (\Exception $e) {
            $this->error = 'Erreur : ' . $e->getMessage();
        }
    }

    public function createSuperAdmin()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_super_admin' => true,
        ]);

        $this->message = 'Super administrateur créé avec succès.';
        $this->reset(['name', 'email', 'password']);
    }
}