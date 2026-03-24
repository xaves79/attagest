<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ParametresApp extends Component
{
    public array  $parametres    = [];
    public string $successMessage = '';
    public string $errorMessage   = '';

    public function mount(): void
    {
        $this->charger();
    }

    private function charger(): void
    {
        $rows = DB::table('parametres_app')->orderBy('groupe')->orderBy('label')->get();
        $this->parametres = [];
        foreach ($rows as $row) {
            $this->parametres[$row->id] = [
                'id'          => $row->id,
                'cle'         => $row->cle,
                'valeur'      => $row->valeur,
                'type'        => $row->type,
                'groupe'      => $row->groupe,
                'label'       => $row->label,
                'description' => $row->description,
            ];
        }
    }

    public function sauvegarder(): void
    {
        $this->errorMessage  = '';
        $this->successMessage = '';

        try {
            DB::transaction(function () {
                foreach ($this->parametres as $id => $param) {
                    $valeur = trim($param['valeur']);

                    // Validation selon type
                    if ($param['type'] === 'integer' && !is_numeric($valeur)) {
                        $this->errorMessage = "Valeur invalide pour « {$param['label']} » — nombre entier attendu.";
                        throw new \Exception('Validation');
                    }
                    if ($param['type'] === 'decimal' && !is_numeric($valeur)) {
                        $this->errorMessage = "Valeur invalide pour « {$param['label']} » — nombre décimal attendu.";
                        throw new \Exception('Validation');
                    }

                    DB::table('parametres_app')->where('id', $id)->update([
                        'valeur'     => $valeur,
                        'updated_at' => now(),
                    ]);
                }
            });

            $this->successMessage = 'Paramètres sauvegardés avec succès.';

        } catch (\Exception $e) {
            if ($e->getMessage() !== 'Validation') {
                $this->errorMessage = 'Erreur : ' . $e->getMessage();
            }
        }
    }

    public function render()
    {
        $groupes = collect($this->parametres)->groupBy('groupe');

        return view('livewire.parametres.parametres-app', compact('groupes'))
            ->layout('layouts.app');
    }
}