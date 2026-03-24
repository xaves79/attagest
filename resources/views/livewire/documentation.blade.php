<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-sm">📚</div>
            <div>
                <h1 class="text-sm font-bold text-white leading-none">Documentation & Formation</h1>
                <p class="text-xs text-slate-500 mt-0.5">Guide d'utilisation ATTAGEST</p>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-8 space-y-6" x-data="{ section: 'flux' }">

        {{-- NAVIGATION SECTIONS --}}
        <div class="flex flex-wrap gap-2">
            @foreach(['flux' => '🔄 Flux métier', 'achats' => '🌾 Achats', 'transformation' => '⚙️ Transformation', 'ventes' => '🛒 Ventes', 'stocks' => '📦 Stocks', 'comptabilite' => '📒 Comptabilité', 'admin' => '🛡️ Administration'] as $k => $l)
            <button @click="section = '{{ $k }}'"
                    :class="section === '{{ $k }}' ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition">
                {{ $l }}
            </button>
            @endforeach
        </div>

        {{-- FLUX MÉTIER --}}
        <div x-show="section === 'flux'" class="space-y-4">
            <div class="bg-slate-900 border border-blue-700/30 rounded-2xl p-6">
                <h2 class="text-base font-black text-white mb-4">🔄 Flux métier complet de la rizerie</h2>
                <div class="space-y-3">
                    @foreach([
                        ['🌾', 'Achat paddy', 'Enregistrer un lot de paddy acheté auprès d\'un fournisseur. Le stock paddy est créé automatiquement.', 'Achats → Achats paddy → + Nouvel achat', 'amber'],
                        ['🔥', 'Étuvage', 'Lancer l\'étuvage d\'un lot paddy. En 2 temps : lancement puis clôture avec pesée réelle.', 'Transformation → Étuvages → + Nouvelle opération', 'blue'],
                        ['⚙️', 'Décorticage', 'Décortiquer le riz étuvé. Produit : riz blanc, son, brisures, rejets. En 2 temps.', 'Transformation → Décorticages → + Nouvelle opération', 'purple'],
                        ['🎒', 'Ensachage', 'Conditionner les produits finis en sacs commerciaux. Saisir la masse, choisir la capacité.', 'Transformation → Ensachage', 'green'],
                        ['🛒', 'Vente', 'Créer une commande client. Livrer les sacs. Encaisser le paiement.', 'Ventes → Commandes → + Nouvelle commande', 'green'],
                        ['📒', 'Comptabilité', 'Enregistrer les écritures comptables liées aux opérations. Journal, grand livre, résultat.', 'Comptabilité → Comptabilité', 'violet'],
                    ] as $step)
                    <div class="flex items-start gap-4 bg-slate-800/50 rounded-xl p-4">
                        <div class="text-2xl">{{ $step[0] }}</div>
                        <div class="flex-1">
                            <p class="font-bold text-white text-sm">{{ $step[1] }}</p>
                            <p class="text-slate-400 text-xs mt-1">{{ $step[2] }}</p>
                            <p class="text-{{ $step[4] }}-400 text-xs font-mono mt-2">📍 {{ $step[3] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Flux visuel --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Chaîne de valeur</h3>
                <div class="flex items-center justify-between flex-wrap gap-2 text-xs text-center">
                    @foreach(['🌾 Paddy' => 'amber', '🔥 Étuvage' => 'blue', '⚙️ Décorti.' => 'purple', '🎒 Ensachage' => 'green', '🛒 Vente' => 'emerald', '💰 Encaissement' => 'teal'] as $label => $color)
                    <div class="flex items-center gap-2">
                        <div class="bg-{{ $color }}-900/50 border border-{{ $color }}-700/50 rounded-xl px-3 py-2 text-{{ $color }}-300 font-bold">{{ $label }}</div>
                        @if(!$loop->last)<span class="text-slate-600">→</span>@endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ACHATS --}}
        <div x-show="section === 'achats'" class="space-y-4">
            <div class="bg-slate-900 border border-amber-700/30 rounded-2xl p-6 space-y-4">
                <h2 class="text-base font-black text-white">🌾 Module Achats Paddy</h2>
                @foreach([
                    ['Enregistrer un achat comptant', 'Achats → Achats paddy → + Nouvel achat → Type : Comptant → Remplir le formulaire → Sauvegarder. Le stock paddy est créé automatiquement.'],
                    ['Enregistrer un achat anticipé', 'Type : Anticipe → Renseigner la date de livraison prévue. Le lot apparaît avec statut "Anticipé" jusqu\'à réception.'],
                    ['Enregistrer un achat à crédit', 'Type : Crédit → Renseigner le montant de l\'acompte. La différence devient le solde dû au fournisseur.'],
                    ['Payer un solde fournisseur', 'Achats → Achats paddy → Voir le lot → Section paiements → Enregistrer un paiement.'],
                    ['Imprimer un reçu fournisseur', 'Achats → Achats paddy → Voir le lot → Bouton 🖨️ PDF.'],
                ] as $item)
                <div class="border-l-2 border-amber-600 pl-4">
                    <p class="font-bold text-amber-300 text-sm">{{ $item[0] }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- TRANSFORMATION --}}
        <div x-show="section === 'transformation'" class="space-y-4">
            <div class="bg-slate-900 border border-purple-700/30 rounded-2xl p-6 space-y-4">
                <h2 class="text-base font-black text-white">⚙️ Module Transformation</h2>
                @foreach([
                    ['Lancer un étuvage', 'Transformation → Étuvages → + Nouvelle opération → Sélectionner le lot paddy et la quantité → Sauvegarder. Le stock paddy est débité automatiquement.'],
                    ['Clôturer un étuvage', 'Étuvages → Voir l\'étuvage en cours → Saisir la masse pesée après étuvage → Clôturer. Un lot riz étuvé est créé.'],
                    ['Lancer un décorticage', 'Transformation → Décorticages → + Nouvelle opération → Sélectionner un lot riz étuvé.'],
                    ['Clôturer un décorticage', 'Saisir les quantités obtenues : riz blanc, son, brisures, rejets. Le son est calculé automatiquement. Les stocks produits finis sont créés.'],
                    ['Ensachage', 'Transformation → Ensachage → Sélectionner un stock produit fini → Saisir la masse à ensacher → Choisir la capacité du sac. Le nombre de sacs est calculé automatiquement.'],
                    ['Traitement client (décorticage à façon)', 'Transformation → Traitements clients → + Nouveau → Saisir le paddy, le riz blanc obtenu et le prix/kg. Le montant est calculé auto.'],
                ] as $item)
                <div class="border-l-2 border-purple-600 pl-4">
                    <p class="font-bold text-purple-300 text-sm">{{ $item[0] }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- VENTES --}}
        <div x-show="section === 'ventes'" class="space-y-4">
            <div class="bg-slate-900 border border-green-700/30 rounded-2xl p-6 space-y-4">
                <h2 class="text-base font-black text-white">🛒 Module Ventes</h2>
                @foreach([
                    ['Créer une commande', 'Ventes → Commandes → + Nouvelle commande → Sélectionner le client, le point de vente, les sacs → Saisir un acompte si nécessaire → Valider.'],
                    ['Livrer une commande', 'Commandes → Voir la commande → Bouton "Livrer" → Confirmer les quantités livrées.'],
                    ['Encaisser un paiement', 'Commandes → Voir la commande → Section paiements → Enregistrer le montant reçu et le mode de paiement.'],
                    ['Imprimer une facture', 'Commandes → Voir la commande → Bouton 🖨️ PDF.'],
                    ['Vente à crédit', 'Lors de la commande, saisir un acompte inférieur au total. Le solde reste dû et apparaît dans les alertes.'],
                ] as $item)
                <div class="border-l-2 border-green-600 pl-4">
                    <p class="font-bold text-green-300 text-sm">{{ $item[0] }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- STOCKS --}}
        <div x-show="section === 'stocks'" class="space-y-4">
            <div class="bg-slate-900 border border-teal-700/30 rounded-2xl p-6 space-y-4">
                <h2 class="text-base font-black text-white">📦 Module Stocks</h2>
                @foreach([
                    ['Consulter les stocks paddy', 'Stocks → Stocks paddy. Affiche les lots, quantités restantes et statuts.'],
                    ['Consulter les produits finis', 'Stocks → Produits finis. Les cartes en haut affichent les alertes si sous le seuil.'],
                    ['Modifier les seuils d\'alerte', 'Référentiels → Paramètres app → Modifier les seuils. Les alertes se mettent à jour automatiquement.'],
                    ['Consulter les sacs disponibles', 'Stocks → Sacs produits finis. Affiche les sacs par type et point de vente.'],
                ] as $item)
                <div class="border-l-2 border-teal-600 pl-4">
                    <p class="font-bold text-teal-300 text-sm">{{ $item[0] }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- COMPTABILITÉ --}}
        <div x-show="section === 'comptabilite'" class="space-y-4">
            <div class="bg-slate-900 border border-violet-700/30 rounded-2xl p-6 space-y-4">
                <h2 class="text-base font-black text-white">📒 Module Comptabilité</h2>
                @foreach([
                    ['Saisir une écriture', 'Comptabilité → Comptabilité → + Écriture → Choisir la date, la pièce, le libellé, les comptes débit/crédit et le montant.'],
                    ['Consulter le journal', 'Onglet "Journal". Filtrer par compte, période ou libellé.'],
                    ['Voir le grand livre', 'Onglet "Grand livre". Affiche les soldes débit/crédit de tous les comptes.'],
                    ['Voir le résultat', 'Onglet "Résultat". Affiche charges vs produits et le résultat net.'],
                    ['Générer un bilan', 'Comptabilité → Bilans globaux → Choisir la période → Télécharger PDF.'],
                    ['Générer les rapports', 'Comptabilité → Rapports → Choisir la période → PDF ou CSV.'],
                ] as $item)
                <div class="border-l-2 border-violet-600 pl-4">
                    <p class="font-bold text-violet-300 text-sm">{{ $item[0] }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ADMIN --}}
        <div x-show="section === 'admin'" class="space-y-4">
            <div class="bg-slate-900 border border-red-700/30 rounded-2xl p-6 space-y-4">
                <h2 class="text-base font-black text-white">🛡️ Administration système</h2>
                @foreach([
                    ['Créer un utilisateur', 'Référentiels → Administration → + Nouvel utilisateur → Définir le rôle.'],
                    ['Désactiver un compte', 'Administration → Cliquer sur "Actif" pour basculer en "Inactif". L\'utilisateur ne pourra plus se connecter.'],
                    ['Consulter le journal d\'activité', 'Référentiels → Journal des intervenants. Filtrer par utilisateur, module ou action.'],
                    ['Vider les données de test', 'En ligne de commande : php artisan attagest:reset-data. Garde les référentiels et utilisateurs.'],
                    ['Configurer l\'entreprise', 'Référentiels → Entreprises → Modifier les informations (nom, adresse, RCCM...). Apparaît sur tous les PDF.'],
                    ['Modifier les seuils', 'Référentiels → Paramètres app → Modifier les seuils de stock et rendements.'],
                ] as $item)
                <div class="border-l-2 border-red-600 pl-4">
                    <p class="font-bold text-red-300 text-sm">{{ $item[0] }}</p>
                    <p class="text-slate-400 text-xs mt-1">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Rôles --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-white mb-3">Rôles et accès</h3>
                <div class="space-y-2 text-xs">
                    @foreach(['🛡️ Admin' => 'Accès total — création/modification/suppression sur tout', '👑 DG' => 'Tous les modules de consultation et d\'opération', '📒 Comptable' => 'Comptabilité, bilans, rapports, factures, paiements', '🛒 Commercial' => 'Ventes, commandes, clients, consultation stocks', '🏭 Production' => 'Transformation, stocks, achats', '📦 Magasinier' => 'Stocks et ensachage uniquement', '👤 Opérateur' => 'Dashboard de consultation uniquement'] as $role => $desc)
                    <div class="flex items-start gap-3 bg-slate-800 rounded-lg p-3">
                        <span class="font-bold text-slate-300 w-28 shrink-0">{{ $role }}</span>
                        <span class="text-slate-400">{{ $desc }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>