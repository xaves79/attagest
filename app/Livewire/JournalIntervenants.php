<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class JournalIntervenants extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $filtreModule = '';
    public string $filtreAction = '';
    public string $filtreUser   = '';
    public string $filtreDateDeb = '';
    public string $filtreDateFin = '';

    public function mount(): void
    {
        $this->filtreDateDeb = now()->startOfMonth()->format('Y-m-d');
        $this->filtreDateFin = now()->format('Y-m-d');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $activites = DB::table('journal_activites as j')
            ->leftJoin('users as u', 'u.id', '=', 'j.user_id')
            ->select(
                'j.id', 'j.action', 'j.module', 'j.description',
                'j.ip_address', 'j.created_at',
                'u.name as user_name', 'u.email as user_email',
                DB::raw("COALESCE(u.role, 'inconnu') as user_role")
            )
            ->when($this->search, fn($q) =>
                $q->where('j.description', 'ilike', "%{$this->search}%")
                  ->orWhere('u.name', 'ilike', "%{$this->search}%")
            )
            ->when($this->filtreModule, fn($q) => $q->where('j.module', $this->filtreModule))
            ->when($this->filtreAction, fn($q) => $q->where('j.action', $this->filtreAction))
            ->when($this->filtreUser,   fn($q) => $q->where('j.user_id', $this->filtreUser))
            ->when($this->filtreDateDeb, fn($q) => $q->where('j.created_at', '>=', $this->filtreDateDeb))
            ->when($this->filtreDateFin, fn($q) => $q->where('j.created_at', '<=', $this->filtreDateFin . ' 23:59:59'))
            ->orderByDesc('j.created_at')
            ->paginate(25);

        // Stats rapides
        $stats = DB::table('journal_activites')
            ->when($this->filtreDateDeb, fn($q) => $q->where('created_at', '>=', $this->filtreDateDeb))
            ->when($this->filtreDateFin, fn($q) => $q->where('created_at', '<=', $this->filtreDateFin . ' 23:59:59'))
            ->selectRaw('action, COUNT(*) as nb')
            ->groupBy('action')
            ->orderByDesc('nb')
            ->get()
            ->keyBy('action');

        $modules  = DB::table('journal_activites')->distinct()->orderBy('module')->pluck('module');
        $users    = DB::table('users')->orderBy('name')->select('id', 'name', 'email')->get();

        return view('livewire.journal-intervenants', compact('activites', 'stats', 'modules', 'users'))
            ->layout('layouts.app');
    }
}