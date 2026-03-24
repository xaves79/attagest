<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class JournalActivite
{
    public static function log(
        string $action,
        string $module,
        string $description,
        array  $avant = [],
        array  $apres = []
    ): void {
        try {
            DB::table('journal_activites')->insert([
                'user_id'       => Auth::id(),
                'action'        => $action,
                'module'        => $module,
                'description'   => $description,
                'donnees_avant' => $avant ? json_encode($avant) : null,
                'donnees_apres' => $apres ? json_encode($apres) : null,
                'ip_address'    => Request::ip(),
                'user_agent'    => substr(Request::userAgent() ?? '', 0, 255),
                'created_at'    => now(),
            ]);
        } catch (\Exception $e) {
            // Ne jamais bloquer l'app pour un log raté
            \Log::warning('JournalActivite::log failed: ' . $e->getMessage());
        }
    }

    // Raccourcis sémantiques
    public static function creation(string $module, string $description, array $apres = []): void
    {
        self::log('creation', $module, $description, [], $apres);
    }

    public static function modification(string $module, string $description, array $avant = [], array $apres = []): void
    {
        self::log('modification', $module, $description, $avant, $apres);
    }

    public static function suppression(string $module, string $description, array $avant = []): void
    {
        self::log('suppression', $module, $description, $avant, []);
    }

    public static function paiement(string $module, string $description, array $apres = []): void
    {
        self::log('paiement', $module, $description, [], $apres);
    }

    public static function connexion(string $description): void
    {
        self::log('connexion', 'auth', $description);
    }

    public static function export(string $module, string $description): void
    {
        self::log('export', $module, $description);
    }
}