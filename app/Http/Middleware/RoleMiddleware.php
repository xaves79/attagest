<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // Permissions par rôle : quels modules sont accessibles
    const PERMISSIONS = [
        'admin'       => '*', // tout
        'dg'          => ['dashboard', 'achats', 'ventes', 'transformation', 'stocks', 'comptabilite', 'rapports', 'referentiels'],
        'comptable'   => ['dashboard', 'comptabilite', 'rapports', 'bilans', 'factures', 'paiements'],
        'commercial'  => ['dashboard', 'ventes', 'commandes', 'clients', 'stocks'],
        'production'  => ['dashboard', 'transformation', 'stocks', 'achats'],
        'magasinier'  => ['dashboard', 'stocks', 'ensachage'],
        'operateur'   => ['dashboard'],
    ];

    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Vérifier si le compte est actif
        if (isset($user->actif) && !$user->actif) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte est désactivé.');
        }

        // Admin a accès à tout
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Vérifier les rôles autorisés
        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Accès non autorisé. Votre rôle ne permet pas cette action.');
        }

        return $next($request);
    }

    public static function peutAcceder(string $module): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        if ($user->role === 'admin') return true;

        $perms = self::PERMISSIONS[$user->role] ?? [];
        return in_array($module, $perms);
    }
}