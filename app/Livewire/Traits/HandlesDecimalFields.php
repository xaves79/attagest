<?php

namespace App\Livewire\Traits;

trait HandlesDecimalFields
{
    /**
     * Convertit les virgules en points pour les champs définis dans la propriété $decimalFields.
     */
    public function convertDecimalFields()
    {
        if (!property_exists($this, 'decimalFields') || !is_array($this->decimalFields)) {
            return;
        }

        foreach ($this->decimalFields as $field) {
            if (isset($this->form[$field]) && is_string($this->form[$field])) {
                // Remplacer la virgule par un point
                $this->form[$field] = str_replace(',', '.', $this->form[$field]);
                // Supprimer tout caractère non numérique sauf le point et le signe moins
                $this->form[$field] = preg_replace('/[^0-9.-]/', '', $this->form[$field]);
                // Convertir en float si ce n'est pas vide
                if ($this->form[$field] !== '') {
                    $this->form[$field] = (float) $this->form[$field];
                } else {
                    $this->form[$field] = null;
                }
            }
        }
    }

    /**
     * Surcharge de la méthode validate pour inclure la conversion.
     */
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        $this->convertDecimalFields();
        return parent::validate($rules, $messages, $attributes);
    }

    /**
     * Optionnel : si vous utilisez la validation dans d'autres méthodes, vous pouvez aussi surcharger validateOnly.
     */
    public function validateOnly($field, $rules = null, $messages = [], $attributes = [], $dataOverrides = [])
    {
        $this->convertDecimalFields();
        return parent::validateOnly($field, $rules, $messages, $attributes, $dataOverrides);
    }
}