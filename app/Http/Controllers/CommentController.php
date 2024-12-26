<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'post'])
            ->latest()
            ->paginate(20);
            
        return view('admin.comments.index', compact('comments'));
    }

    public function store(Request $request, Post $post)
    {
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
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $comment->update([
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Comment status updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully!');
    }
}