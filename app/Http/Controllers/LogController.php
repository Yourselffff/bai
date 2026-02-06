<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

/**
 * Affichage des logs Laravel.
 * Accès réservé aux administrateurs (middleware admin).
 */
class LogController extends Controller
{
    public function index()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logFile)) {
            // Lire les dernières lignes du fichier efficacement
            $file = new \SplFileObject($logFile, 'r');
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key();

            // Récupérer les 500 dernières lignes
            $startLine = max(0, $totalLines - 500);
            $file->seek($startLine);

            $lines = [];
            while (!$file->eof()) {
                $lines[] = $file->fgets();
            }

            // Parser les logs (format: [2024-01-15 10:30:00] local.WARNING: Message)
            foreach (array_reverse($lines) as $line) {
                $line = trim($line);
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.*)/', $line, $matches)) {
                    $logs[] = [
                        'date' => $matches[1],
                        'level' => $matches[2],
                        'message' => \Illuminate\Support\Str::limit($matches[3], 200),
                    ];

                    // Limiter à 100 entrées
                    if (count($logs) >= 100) {
                        break;
                    }
                }
            }
        }

        return view('logs.index', compact('logs'));
    }
}
