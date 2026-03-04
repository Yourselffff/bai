<?php

namespace App\Services;

use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Service centralisé pour l'enregistrement des logs d'actions.
 *
 * Pourquoi côté serveur ?
 * Les logs sont créés par le serveur PHP au moment où l'action est réellement exécutée.
 * L'utilisateur ne peut donc pas modifier ou supprimer ces informations via le navigateur.
 */
class ActionLogService
{
    /**
     * Enregistre une action dans la table action_logs.
     *
     * @param  string      $action      Nom de l'action (ex: "login_success", "idea_created")
     * @param  int|null    $userId      ID de l'utilisateur (null si non authentifié)
     * @param  int|null    $ideaId      ID de l'idée concernée (si applicable)
     * @param  int|null    $commentId   ID du commentaire concerné (si applicable)
     * @param  mixed       $dataBefore  Données avant modification (état précédent)
     * @param  mixed       $dataAfter   Données après modification (nouvel état)
     */
    public static function log(
        string $action,
        ?int $userId = null,
        ?int $ideaId = null,
        ?int $commentId = null,
        mixed $dataBefore = null,
        mixed $dataAfter = null
    ): void {
        ActionLog::create([
            'user_id'     => $userId ?? Auth::id(),
            'action'      => $action,
            'idea_id'     => $ideaId,
            'comment_id'  => $commentId,
            'data_before' => $dataBefore !== null ? json_encode($dataBefore, JSON_UNESCAPED_UNICODE) : null,
            'data_after'  => $dataAfter !== null ? json_encode($dataAfter, JSON_UNESCAPED_UNICODE) : null,
            'ip_address'  => Request::ip(),
            'user_agent'  => self::parseUserAgent(Request::userAgent()),
        ]);
    }

    /**
     * Transforme un user-agent brut en chaîne lisible.
     *
     * Exemple :
     *   "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/145.0.0.0"
     *   → "Chrome 145 — Windows 10"
     */
    public static function parseUserAgent(?string $ua): string
    {
        if (!$ua) {
            return 'Inconnu';
        }

        // --- Détection du navigateur ---
        $browser = 'Navigateur inconnu';

        if (preg_match('/Edg\/(\d+)/', $ua, $m)) {
            $browser = 'Edge ' . $m[1];
        } elseif (preg_match('/OPR\/(\d+)/', $ua, $m)) {
            $browser = 'Opera ' . $m[1];
        } elseif (preg_match('/Chrome\/(\d+)/', $ua, $m)) {
            $browser = 'Chrome ' . $m[1];
        } elseif (preg_match('/Firefox\/(\d+)/', $ua, $m)) {
            $browser = 'Firefox ' . $m[1];
        } elseif (preg_match('/Safari\/(\d+)/', $ua, $m) && !str_contains($ua, 'Chrome')) {
            $browser = 'Safari';
        } elseif (str_contains($ua, 'Trident') || str_contains($ua, 'MSIE')) {
            $browser = 'Internet Explorer';
        }

        // --- Détection du système d'exploitation ---
        $os = 'OS inconnu';

        if (preg_match('/Android (\d+)/', $ua, $m)) {
            $os = 'Android ' . $m[1];
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $os = 'iOS';
        } elseif (preg_match('/Windows NT (\d+\.\d+)/', $ua, $m)) {
            $versions = ['10.0' => '10/11', '6.3' => '8.1', '6.2' => '8', '6.1' => '7', '6.0' => 'Vista'];
            $os = 'Windows ' . ($versions[$m[1]] ?? $m[1]);
        } elseif (str_contains($ua, 'Macintosh')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'Linux')) {
            $os = 'Linux';
        }

        return $browser . ' — ' . $os;
    }
}
