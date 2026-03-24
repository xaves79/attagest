<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\JournalActivite;

class GestionUtilisateurs extends Component
{
    public bool   $showModal   = false;
    public ?int   $formId      = null;
    public string $formName    = '';
    public string $formEmail   = '';
    public string $formRole    = 'operateur';
    public string $formPassword = '';
    public bool   $formActif   = true;

    public string $successMessage = '';
    public string $errorMessage   = '';

    const ROLES = [
        'admin'      => '🛡️ Administrateur',
        'dg'         => '👑 Directeur Général',
        'comptable'  => '📒 Comptable',
        'commercial' => '🛒 Commercial',
        'production' => '🏭 Production',
        'magasinier' => '📦 Magasinier',
        'operateur'  => '👤 Opérateur',
    ];

    public function nouveau(): void
    {
        $this->formId       = null;
        $this->formName     = '';
        $this->formEmail    = '';
        $this->formRole     = 'operateur';
        $this->formPassword = '';
        $this->formActif    = true;
        $this->showModal    = true;
        $this->errorMessage = '';
    }

    public function editer(int $id): void
    {
        $u = DB::table('users')->where('id', $id)->first();
        if (!$u) return;
        $this->formId       = $u->id;
        $this->formName     = $u->name;
        $this->formEmail    = $u->email;
        $this->formRole     = $u->role ?? 'operateur';
        $this->formPassword = '';
        $this->formActif    = (bool)($u->actif ?? true);
        $this->showModal    = true;
        $this->errorMessage = '';
    }

    public function sauvegarder(): void
    {
        $this->errorMessage = '';

        if (!$this->formName)  { $this->errorMessage = 'Nom requis.'; return; }
        if (!$this->formEmail) { $this->errorMessage = 'Email requis.'; return; }

        // Vérifier email unique
        $exists = DB::table('users')
            ->where('email', $this->formEmail)
            ->when($this->formId, fn($q) => $q->where('id', '!=', $this->formId))
            ->exists();
        if ($exists) { $this->errorMessage = 'Cet email est déjà utilisé.'; return; }

        if ($this->formId) {
            $data = [
                'name'  => $this->formName,
                'email' => $this->formEmail,
                'role'  => $this->formRole,
                'actif' => $this->formActif,
            ];
            if ($this->formPassword) {
                $data['password'] = Hash::make($this->formPassword);
            }
            DB::table('users')->where('id', $this->formId)->update($data);
            JournalActivite::modification('users', "Modification utilisateur : {$this->formName} ({$this->formEmail}) — rôle : {$this->formRole}");
            $this->successMessage = "Utilisateur {$this->formName} modifié.";
        } else {
            if (!$this->formPassword) { $this->errorMessage = 'Mot de passe requis.'; return; }
            DB::table('users')->insert([
                'name'       => $this->formName,
                'email'      => $this->formEmail,
                'password'   => Hash::make($this->formPassword),
                'role'       => $this->formRole,
                'actif'      => $this->formActif,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            JournalActivite::creation('users', "Création utilisateur : {$this->formName} ({$this->formEmail}) — rôle : {$this->formRole}");
            $this->successMessage = "Utilisateur {$this->formName} créé.";
        }

        $this->showModal = false;
    }

    public function toggleActif(int $id): void
    {
        $u = DB::table('users')->where('id', $id)->first();
        if (!$u) return;
        $newActif = !((bool)($u->actif ?? true));
        DB::table('users')->where('id', $id)->update(['actif' => $newActif]);
        $action = $newActif ? 'activé' : 'désactivé';
        JournalActivite::modification('users', "Compte {$action} : {$u->name}");
        $this->successMessage = "Compte {$u->name} {$action}.";
    }

    public function render()
    {
        $users = DB::table('users')->orderBy('name')->get();
        return view('livewire.gestion-utilisateurs', compact('users'))
            ->layout('layouts.app');
    }
}