<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use Illuminate\Http\Request;

/**
 * Affichage des logs d'actions en base de données.
 * Accès réservé aux administrateurs (middleware admin).
 */
class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActionLog::with('user')->latest();

        // --- Filtre par date ---
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // --- Filtre par type d'action ---
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}
