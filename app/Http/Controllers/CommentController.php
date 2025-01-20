<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CommentController extends Controller
{
    public function index()
    {
        $toComments = Comment::with(['user', 'post'])
            ->where('status', 'pending')
            ->latest()
            ->take(9)
            ->get()
            ->map(function ($comment) {
                $comment->created_at_human = $comment->created_at->diffForHumans();
                return $comment;
            });

        return Inertia::render('Dashboard', [
            'toComments' => $toComments
        ]);    
    }

    public function store(Request $request, Post $post)
    {
        // Authorize the action using Gates
        Gate::authorize('create', Comment::class);
    
        $validated = $request->validate([
            'content' => 'required|min:3'
        ]);
    
        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'status' => Auth::user()->role === 'admin' ? 'approved' : 'pending'
        ]);
    
        $post->comments()->save($comment);
    
        return back()->with('success', 'Comment submitted successfully! ' . 
            ($comment->status === 'pending' ? 'It will be visible after moderation.' : ''));
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update-comment', $comment);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $comment->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Comment status updated successfully!');
    }

    public function approve(Comment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->update([
            'status' => 'approved',
        ]);

        return response()->json(['message' => 'Comment approved successfully!'], 200);
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully!');
    }
}