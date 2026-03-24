<?php

// app/Services/EcritureComptableService.php

namespace App\Services;

use App\Models\EcritureComptable;
use Illuminate\Support\Facades\DB;

class EcritureComptableService
{
    /**
     * Génère un code d'écriture unique au format ECR-AAAA-NNNNNN
     */
    public function generateCodeEcriture($annee = null)
	{
		$annee = $annee ?? now()->year;

		$last = EcritureComptable::where('code_ecriture', 'like', "ECR-{$annee}-%")
			->orderBy('code_ecriture', 'desc')
			->first();

		if ($last) {
			preg_match('/(\d+)$/', $last->code_ecriture, $matches);
			$num = (int) $matches[1] + 1;
		} else {
			$num = 1;
		}

		$code = sprintf('ECR-%d-%06d', $annee, $num);

		// On s’assure que ce code n’existe pas déjà
		while (EcritureComptable::where('code_ecriture', $code)->exists()) {
			$num++;
			$code = sprintf('ECR-%d-%06d', $annee, $num);
		}

		return $code;
	}

    /**
     * Crée une écriture comptable simple (débit / crédit)
     */
    public function createEcriture(
        string $libelle,
        string $compteDebit,
        float $montantDebit,
        string $compteCredit,
        float $montantCredit,
        string $pieceComptable,
        ?string $dateEcriture = null,
        bool $valide = true
    ): EcritureComptable {
        // On vérifie que le montant débit = crédit
        if (abs($montantDebit - $montantCredit) > 0.001) {
            throw new \InvalidArgumentException('Le montant débit doit être égal au montant crédit.');
        }

        return EcritureComptable::create([
            'code_ecriture'   => $this->generateCodeEcriture($dateEcriture ? now()->parse($dateEcriture)->year : null),
            'date_ecriture'   => $dateEcriture ?? now()->toDateString(),
            'libelle'         => $libelle,
            'compte_debit'    => $compteDebit,
            'montant_debit'   => $montantDebit,
            'compte_credit'   => $compteCredit,
            'montant_credit'  => $montantCredit,
            'piece_comptable' => $pieceComptable,
            'valide'          => $valide,
        ]);
    }

    /**
     * Crée une écriture à partir d'un AchatPaddy
     */
    public function createEcritureFromAchatPaddy($achatPaddy): EcritureComptable
    {
        return $this->createEcriture(
            libelle: "Achat paddy fournisseur {$achatPaddy->fournisseur->nom} (lot {$achatPaddy->code_lot})",
            compteDebit: '601100',
            montantDebit: $achatPaddy->montant_achat_total_fcfa,
            compteCredit: '401',
            montantCredit: $achatPaddy->montant_achat_total_fcfa,
            pieceComptable: $achatPaddy->code_lot,
            dateEcriture: $achatPaddy->date_achat,
            valide: true
        );
    }

    /**
     * Crée une écriture à partir d'une Vente
     */
    public function createEcritureFromVente($vente): EcritureComptable
    {
        $compteCredit = match ($vente->type_produit) {
            'riz_blanc' => '707100',
            'son'       => '707200',
            'riz_brisé' => '707300',
            'rejet'     => '707400',
            default     => '707',
        };

        $clientNom = $vente->client ? $vente->client->nom : 'Client inconnu';

        return $this->createEcriture(
            libelle: "Vente {$vente->type_produit} client {$clientNom}",
            compteDebit: '512',
            montantDebit: $vente->montant_vente_total_fcfa,
            compteCredit: $compteCredit,
            montantCredit: $vente->montant_vente_total_fcfa,
            pieceComptable: $vente->code_vente,
            dateEcriture: $vente->date_vente,
            valide: true
        );
    }
}
