<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Services\ActionLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller responsible for managing ideas.
 *
 * NOTE:
 * - No validation (voluntary vulnerabilities for the cybersecurity exercises) (TODO)
 * - No authorization (TODO)
 * - XSS not escaped in the views (TODO)
 */
class IdeaController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display all ideas.
     */
    public function index()
    {
        // Loads ideas with their authors (simple pagination)
        $ideas = Idea::with('user')
            ->latest()
            ->paginate(10);

        return view('ideas.index', compact('ideas'));
    }

    /**
     * Show the form to create a new idea.
     */
    public function create()
    {
        return view('ideas.create');
    }

    /**
     * Store a newly created idea.
     *
     * SECURITY NOTE:
     * - No validation (TODO)
     * - No rate limiting (TODO)
     */
    public function store(Request $request)
    {
        $idea = Idea::create([
            'user_id'     => Auth::id(),
            'title'       => $request->input('title'),
            'description' => $request->input('description'), // XSS not escaped
            'application' => $request->input('application'),
        ]);

        // LOG : création d'une idée — enregistré par le serveur après insertion en base
        ActionLogService::log(
            action: 'idea_created',
            ideaId: $idea->id,
            dataAfter: [
                'title'       => $idea->title,
                'description' => $idea->description,
                'application' => $idea->application,
            ]
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Idea created (vulnerable version).');
    }

    /**
     * Display the specified idea.
     */
    public function show(Idea $idea)
    {
        $idea->load('comments.user');
        return view('ideas.show', compact('idea'));
    }

    /**
     * Show edit form.
     * Le propriétaire peut modifier son idée.
     * L'admin peut modérer (modifier n'importe quelle idée).
     */
    public function edit(Idea $idea)
    {
        $this->authorize('update', $idea);

        return view('ideas.edit', compact('idea'));
    }

    /**
     * Update the idea.
     * Le propriétaire peut modifier son idée.
     * L'admin peut modérer (modifier n'importe quelle idée).
     */
    public function update(Request $request, Idea $idea)
    {
        $this->authorize('update', $idea);

        // Capture de l'état avant modification pour le log
        $dataBefore = [
            'title'       => $idea->title,
            'description' => $idea->description,
            'application' => $idea->application,
        ];

        $idea->update([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'application' => $request->input('application'),
        ]);

        // LOG : modification d'une idée — état avant et après enregistrés côté serveur
        ActionLogService::log(
            action: 'idea_updated',
            ideaId: $idea->id,
            dataBefore: $dataBefore,
            dataAfter: [
                'title'       => $idea->title,
                'description' => $idea->description,
                'application' => $idea->application,
            ]
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Idea updated.');
    }

    /**
     * Remove an idea.
     * Le propriétaire peut supprimer son idée.
     * L'admin peut modérer (supprimer n'importe quelle idée).
     */
    public function destroy(Idea $idea)
    {
        $this->authorize('delete', $idea);

        // Capture de l'état avant suppression pour le log
        $dataBefore = [
            'title'       => $idea->title,
            'description' => $idea->description,
            'application' => $idea->application,
        ];

        $idea->delete();

        // LOG : suppression d'une idée — enregistré après suppression effective en base
        ActionLogService::log(
            action: 'idea_deleted',
            ideaId: $idea->id,
            dataBefore: $dataBefore
        );

        return redirect()
            ->route('ideas.index')
            ->with('status', 'Idea deleted.');
    }
}
