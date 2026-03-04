<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Comment;
use App\Services\ActionLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller responsible for comments on ideas.
 *
 * NOTE:
 * - No validation (TODO)
 * - No limit per user (add max 3 comments per idea) (TODO)
 * - No authorization on delete (TODO secure)
 */
class CommentController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Store a new comment for an idea.
     */
    public function store(Request $request, Idea $idea)
    {
        $comment = Comment::create([
            'idea_id'     => $idea->id,
            'user_id'     => Auth::id(),
            'description' => $request->input('description'), // XSS vulnerable
        ]);

        // LOG : création d'un commentaire — enregistré par le serveur après insertion en base
        ActionLogService::log(
            action: 'comment_created',
            ideaId: $idea->id,
            commentId: $comment->id,
            dataAfter: ['description' => $comment->description]
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Comment added.');
    }

    /**
     * Show edit form for a comment.
     * Le propriétaire peut modifier son commentaire.
     * L'admin peut modérer (modifier n'importe quel commentaire).
     */
    public function edit(Idea $idea, Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', compact('idea', 'comment'));
    }

    /**
     * Update a comment.
     * Le propriétaire peut modifier son commentaire.
     * L'admin peut modérer (modifier n'importe quel commentaire).
     */
    public function update(Request $request, Idea $idea, Comment $comment)
    {
        $this->authorize('update', $comment);

        // Capture de l'état avant modification pour le log
        $dataBefore = ['description' => $comment->description];

        $comment->update([
            'description' => $request->input('description'),
        ]);

        // LOG : modification d'un commentaire — état avant et après enregistrés côté serveur
        ActionLogService::log(
            action: 'comment_updated',
            ideaId: $idea->id,
            commentId: $comment->id,
            dataBefore: $dataBefore,
            dataAfter: ['description' => $comment->description]
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Comment updated.');
    }

    /**
     * Remove a comment.
     * Le propriétaire peut supprimer son commentaire.
     * L'admin peut modérer (supprimer n'importe quel commentaire).
     */
    public function destroy(Idea $idea, Comment $comment)
    {
        $this->authorize('delete', $comment);

        // Capture de l'état avant suppression pour le log
        $dataBefore = ['description' => $comment->description];

        $comment->delete();

        // LOG : suppression d'un commentaire — enregistré après suppression effective en base
        ActionLogService::log(
            action: 'comment_deleted',
            ideaId: $idea->id,
            commentId: $comment->id,
            dataBefore: $dataBefore
        );

        return redirect()
            ->route('ideas.show', $idea)
            ->with('status', 'Comment deleted.');
    }
}
