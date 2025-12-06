<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DiscussionController extends Controller
{
    /**
     * Display discussions for a specific project.
     */
    public function index(Project $project)
    {
        $user = auth()->user();

        // Check if user can access this project
        if (Gate::allows('admin')) {
            // Admin can see all
        } elseif (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            if (!$teamLeadTeams->contains($project->team)) {
                abort(403, 'Unauthorized access to this project discussion.');
            }
        } else {
            // Team member must be assigned to project
            if (!$user->projects->contains($project->id)) {
                abort(403, 'Unauthorized access to this project discussion.');
            }
        }

        // Get only top-level discussions (not replies) with their replies
        $discussions = Discussion::where('project_id', $project->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.discussions.index', compact('project', 'discussions'));
    }

    /**
     * Store a new discussion message.
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        // Check if user can access this project
        if (Gate::allows('admin')) {
            // Admin can post
        } elseif (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            if (!$teamLeadTeams->contains($project->team)) {
                abort(403, 'Unauthorized to post in this project discussion.');
            }
        } else {
            // Team member must be assigned to project
            if (!$user->projects->contains($project->id)) {
                abort(403, 'Unauthorized to post in this project discussion.');
            }
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:discussions,id',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('discussion-attachments', 'public');
                $attachmentPaths[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
        }

        $discussion = Discussion::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'parent_id' => $validated['parent_id'] ?? null,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
        ]);

        // Load relationships
        $discussion->load(['user', 'replies.user']);

        // If AJAX request, return JSON with HTML
        if ($request->wantsJson() || $request->ajax()) {
            $html = view('pages.discussions._message', [
                'discussion' => $discussion,
                'project' => $project,
                'isReply' => !is_null($discussion->parent_id),
            ])->render();

            return response()->json([
                'success' => true,
                'id' => $discussion->id,
                'html' => $html,
                'is_reply' => !is_null($discussion->parent_id),
                'parent_id' => $discussion->parent_id,
            ]);
        }

        return redirect()->route('discussions.index', $project)
            ->with('success', 'Message posted successfully!');
    }

    /**
     * Update a discussion message.
     */
    public function update(Request $request, Discussion $discussion)
    {
        $user = auth()->user();

        // Only message owner or admin can edit
        if (!Gate::allows('admin') && $discussion->user_id !== $user->id) {
            abort(403, 'Unauthorized to edit this message.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $discussion->update([
            'message' => $validated['message'],
        ]);

        return redirect()->back()
            ->with('success', 'Message updated successfully!');
    }

    /**
     * Delete a discussion message.
     */
    public function destroy(Discussion $discussion)
    {
        $user = auth()->user();

        // Only message owner or admin can delete
        if (!Gate::allows('admin') && $discussion->user_id !== $user->id) {
            abort(403, 'Unauthorized to delete this message.');
        }

        // Delete attachments if any
        if ($discussion->attachments) {
            foreach ($discussion->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $discussion->delete();

        return redirect()->back()
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Toggle pin status (admin only).
     */
    public function togglePin(Discussion $discussion)
    {
        Gate::authorize('admin');

        $discussion->update([
            'is_pinned' => !$discussion->is_pinned,
        ]);

        return redirect()->back()
            ->with('success', 'Message ' . ($discussion->is_pinned ? 'pinned' : 'unpinned') . ' successfully!');
    }

    /**
     * Check for new messages (for polling).
     */
    public function checkNewMessages(Request $request, Project $project)
    {
        $lastMessageId = $request->query('last_id', 0);

        $newMessagesCount = Discussion::where('project_id', $project->id)
            ->where('id', '>', $lastMessageId)
            ->count();

        return response()->json([
            'has_new_messages' => $newMessagesCount > 0,
            'count' => $newMessagesCount,
        ]);
    }
}
