<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-500/20 border border-red-500/30 flex items-center justify-center text-sm">🛡️</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Gestion des utilisateurs</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Rôles et accès</p>
                </div>
            </div>
            <button wire:click="nouveau"
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-xl transition active:scale-95">
                + Nouvel utilisateur
            </button>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8 space-y-6">

        @if($successMessage)
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 text-green-300 text-sm">✅ {{ $successMessage }}</div>
        @endif

        {{-- TABLEAU UTILISATEURS --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Utilisateur</th>
                        <th class="text-left px-5 py-3">Email</th>
                        <th class="text-left px-5 py-3">Rôle</th>
                        <th class="text-center px-5 py-3">Statut</th>
                        <th class="text-center px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @php
                        $roles = \App\Livewire\GestionUtilisateurs::ROLES;
                        $roleColors = ['admin' => 'bg-red-900/50 text-red-300 border-red-700/50', 'dg' => 'bg-amber-900/50 text-amber-300 border-amber-700/50', 'comptable' => 'bg-violet-900/50 text-violet-300 border-violet-700/50', 'commercial' => 'bg-green-900/50 text-green-300 border-green-700/50', 'production' => 'bg-blue-900/50 text-blue-300 border-blue-700/50', 'magasinier' => 'bg-teal-900/50 text-teal-300 border-teal-700/50', 'operateur' => 'bg-slate-800 text-slate-400 border-slate-700'];
                    @endphp
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-800/40 transition {{ !(bool)($u->actif ?? true) ? 'opacity-50' : '' }}">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-amber-600 flex items-center justify-center text-sm font-bold text-white">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <span class="text-slate-300 font-semibold">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $u->email }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs border {{ $roleColors[$u->role ?? 'operateur'] ?? 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                {{ $roles[$u->role ?? 'operateur'] ?? $u->role }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="toggleActif({{ $u->id }})"
                                    class="px-3 py-1 rounded-lg text-xs font-semibold transition
                                    {{ (bool)($u->actif ?? true) ? 'bg-green-900/50 text-green-300 hover:bg-green-800' : 'bg-slate-800 text-slate-500 hover:bg-slate-700' }}">
                                {{ (bool)($u->actif ?? true) ? '✅ Actif' : '⛔ Inactif' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="editer({{ $u->id }})"
                                    class="px-3 py-1.5 bg-yellow-700/60 hover:bg-yellow-600 text-white text-xs rounded-lg transition">
                                ✏️ Modifier
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- LÉGENDE RÔLES --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Permissions par rôle</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                @foreach(['admin' => 'Accès total à tout le système', 'dg' => 'Tous modules sauf admin système', 'comptable' => 'Comptabilité, bilans, factures, paiements', 'commercial' => 'Ventes, commandes, clients, stocks', 'production' => 'Transformation, stocks, achats', 'magasinier' => 'Stocks et ensachage uniquement', 'operateur' => 'Dashboard uniquement'] as $role => $desc)
                <div class="bg-slate-800 rounded-xl p-3">
                    <span class="px-2 py-0.5 rounded-full text-xs border {{ $roleColors[$role] ?? '' }} block mb-2 text-center">{{ $roles[$role] }}</span>
                    <p class="text-slate-500">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- MODAL --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-red-700/40 rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">🛡️ {{ $formId ? 'Modifier' : 'Nouvel' }} utilisateur</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-white transition">✕</button>
            </div>

            @if($errorMessage)
            <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-3 text-red-300 text-sm">⚠️ {{ $errorMessage }}</div>
            @endif

            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Nom complet <span class="text-red-400">*</span></label>
                    <input type="text" wire:model="formName" placeholder="Prénom Nom"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" wire:model="formEmail" placeholder="email@attagest.ci"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Rôle <span class="text-red-400">*</span></label>
                    <select wire:model="formRole"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-500 transition">
                        @foreach(\App\Livewire\GestionUtilisateurs::ROLES as $k => $l)
                        <option value="{{ $k }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">
                        Mot de passe {{ $formId ? '(laisser vide pour ne pas changer)' : '*' }}
                    </label>
                    <input type="password" wire:model="formPassword" placeholder="••••••••"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-500 transition">
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" wire:model="formActif" id="actif" class="rounded">
                    <label for="actif" class="text-sm text-slate-300">Compte actif</label>
                </div>
            </div>

            <div class="flex gap-3">
                <button wire:click="$set('showModal', false)"
                        class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">Annuler</button>
                <button wire:click="sauvegarder"
                        class="flex-1 py-2.5 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                    ✅ {{ $formId ? 'Modifier' : 'Créer' }}
                </button>
            </div>
        </div>
    </div>
    @endif

</div>