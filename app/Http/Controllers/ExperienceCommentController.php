<?php

namespace App\Http\Controllers;

use App\Models\CarExperience;
use App\Models\ExperienceComment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceCommentController extends Controller
{
    /**
     * Load comments for an experience — top-level with replies eager-loaded.
     * Public endpoint, guests can read.
     */
    public function index(CarExperience $experience)
    {
        abort_if(!$experience->isApproved(), 404);

        $comments = ExperienceComment::with(['user:id,name,profile_photo', 'replies.user:id,name,profile_photo'])
            ->where('car_experience_id', $experience->id)
            ->topLevel()
            ->oldest()
            ->get()
            ->map(fn ($c) => $this->formatComment($c));

        return response()->json($comments);
    }

    /**
     * Post a new comment or reply.
     * Auth required — any logged-in user.
     */
    public function store(Request $request, CarExperience $experience)
    {
        abort_if(!$experience->isApproved(), 404);

        $request->validate([
            'body'      => ['required', 'string', 'min:2', 'max:1000'],
            'parent_id' => ['nullable', 'integer', 'exists:experience_comments,id'],
        ], [
            'body.required' => 'Comment cannot be empty.',
            'body.min'      => 'Comment is too short.',
            'body.max'      => 'Comment cannot exceed 1000 characters.',
        ]);

        // ── Enforce 2-level depth ─────────────────────────────────────
        if ($request->filled('parent_id')) {
            $parent = ExperienceComment::findOrFail($request->parent_id);

            // Parent must belong to the same experience
            abort_if($parent->car_experience_id !== $experience->id, 422, 'Invalid parent comment.');

            // Parent must be a top-level comment — no replying to a reply
            abort_if($parent->isReply(), 422, 'You can only reply to a top-level comment.');
        }

        $comment = ExperienceComment::create([
            'car_experience_id' => $experience->id,
            'user_id'           => Auth::id(),
            'parent_id'         => $request->parent_id ?? null,
            'body'              => $request->body,
        ]);

        $comment->load('user:id,name,profile_photo');

        // ── Notify parent comment author (if this is a reply) ─────────
        if ($comment->parent_id) {
            $parent = ExperienceComment::find($comment->parent_id);

            // Don't notify if replying to your own comment
            if ($parent && $parent->user_id !== Auth::id()) {
                app(NotificationService::class)->commentReplied($comment, $parent, $experience);
            }
        }

        return response()->json($this->formatComment($comment), 201);
    }

    /**
     * Edit own comment body.
     * Auth required — only the comment author.
     */
    public function update(Request $request, ExperienceComment $comment)
    {
        abort_if($comment->user_id !== Auth::id(), 403, 'You can only edit your own comments.');

        $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $comment->update([
            'body'      => $request->body,
            'is_edited' => true,
        ]);

        return response()->json([
            'body'      => $comment->body,
            'is_edited' => $comment->is_edited,
        ]);
    }

    /**
     * Delete own comment (or admin deletes any).
     * When a top-level comment is deleted, its replies are also deleted
     * (since parent_id uses nullOnDelete, we handle it explicitly here).
     */
    public function destroy(ExperienceComment $comment)
    {
        $user = Auth::user();

        abort_if(
            $comment->user_id !== $user->id && !$user->hasRole(['superadmin', 'admin']),
            403,
            'You can only delete your own comments.'
        );

        // Explicitly delete replies if this is a top-level comment
        if (!$comment->isReply()) {
            ExperienceComment::where('parent_id', $comment->id)->delete();
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted.']);
    }

    // ── Internal helpers ──────────────────────────────────────────────

    /**
     * Serialize a comment (and its replies) for JSON response.
     * Keeps a consistent shape for the frontend Alpine component.
     */
    private function formatComment(ExperienceComment $comment, bool $includeReplies = true): array
    {
        return [
            'id'         => $comment->id,
            'body'       => $comment->body,
            'is_edited'  => $comment->is_edited,
            'parent_id'  => $comment->parent_id,
            'created_at' => $comment->created_at,
            'user'       => [
                'id'            => $comment->user->id,
                'name'          => $comment->user->name,
                'profile_photo' => $comment->user->profile_photo,
            ],
            'replies' => $includeReplies
                ? ($comment->relationLoaded('replies')
                    ? $comment->replies->map(fn ($r) => $this->formatComment($r, false))->values()
                    : [])
                : [],
            // Tells the frontend which comments the current user owns
            'is_mine' => Auth::check() && $comment->user_id === Auth::id(),
        ];
    }
}