<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black text-white p-6">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent inline-block">
                ⚙️ Panneau d'administration
            </h1>
            <p class="text-slate-400 mt-2">Gestion avancée et maintenance de l'application</p>
        </div>

        <!-- Messages flash -->
        @if($message)
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl text-emerald-300 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @endif

        @if($error)
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-300 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $error }}</span>
            </div>
        @endif

        <!-- Grille principale -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Carte Nettoyage des tables -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700/50 shadow-2xl hover:border-emerald-500/30 transition-all duration-300">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-2xl">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Nettoyage des tables</h2>
                </div>

                <p class="text-slate-400 mb-6">Sélectionnez les tables à vider. Cette action est irréversible.</p>

                <div class="max-h-64 overflow-y-auto custom-scrollbar bg-slate-900/50 rounded-2xl p-4 border border-slate-700/50 mb-6">
                    @foreach($tables as $table)
                        <label class="flex items-center p-2 hover:bg-slate-700/30 rounded-xl transition cursor-pointer group">
                            <input type="checkbox" wire:model="selectedTables" value="{{ $table }}" 
                                   class="w-4 h-4 text-emerald-500 bg-slate-800 border-slate-600 rounded focus:ring-emerald-500 focus:ring-offset-0 focus:ring-2">
                            <span class="ml-3 text-sm text-slate-300 group-hover:text-white">{{ $table }}</span>
                        </label>
                    @endforeach
                </div>

                <button wire:click="clearSelectedTables" 
                        wire:confirm="⚠️ Attention ! Cette action supprimera définitivement toutes les données des tables sélectionnées. Continuer ?"
                        class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-red-500/30 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Vider les tables sélectionnées
                </button>
            </div>

            <!-- Carte Création d'un super admin -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700/50 shadow-2xl hover:border-emerald-500/30 transition-all duration-300">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-2xl">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Super administrateur</h2>
                </div>

                <p class="text-slate-400 mb-6">Créez un nouveau compte avec tous les privilèges.</p>

                <form wire:submit.prevent="createSuperAdmin" class="space-y-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nom complet</label>
                        <div class="relative">
                            <input type="text" id="name" wire:model="name" required
                                   class="w-full bg-slate-900/50 border border-slate-700 rounded-xl py-3 px-4 pl-11 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                            <svg class="w-5 h-5 absolute left-3 top-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @error('name') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Adresse email</label>
                        <div class="relative">
                            <input type="email" id="email" wire:model="email" required
                                   class="w-full bg-slate-900/50 border border-slate-700 rounded-xl py-3 px-4 pl-11 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                            <svg class="w-5 h-5 absolute left-3 top-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @error('email') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Mot de passe</label>
                        <div class="relative">
                            <input type="password" id="password" wire:model="password" required
                                   class="w-full bg-slate-900/50 border border-slate-700 rounded-xl py-3 px-4 pl-11 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                            <svg class="w-5 h-5 absolute left-3 top-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        @error('password') <span class="text-sm text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Créer le super administrateur
                    </button>
                </form>
            </div>
        </div>

        <!-- Pied de page informatif -->
        <div class="mt-12 text-center text-sm text-slate-500 border-t border-slate-800 pt-6">
            <p>⚠️ Les opérations de nettoyage sont définitives. Assurez-vous d'avoir une sauvegarde avant de procéder.</p>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.5);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.8);
        }
    </style>
</div>