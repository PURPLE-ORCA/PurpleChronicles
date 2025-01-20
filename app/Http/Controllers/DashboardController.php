<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {      
        $comments = Comment::with(['user', 'post'])
            ->where('status', 'pending')
            ->latest()
            ->take(9)
            ->get()
            ->map(function ($comment) {
                $comment->created_at_human = $comment->created_at->diffForHumans();
                return $comment;
            })
            ->filter(function ($comment) {
                return $comment->user !== null && $comment->post !== null; // Filter out comments without a user or post
            });
    
        $allPosts = Post::with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get()
            ->filter(function ($post) {
                return $post->user !== null && $post->category !== null; // Filter out posts without a user or category
            });

            $teamMembers = User::whereIn('role', ['admin', 'author'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Dashboard', [
            'toComments' => $comments,
            'allPosts' => $allPosts,
            'teamMembers' => $teamMembers
        ]);
    }

    
    public function updateUserRole(Request $request, User $user)
        {
            $request->validate([
                'role' => 'required|in:admin,author',
            ]);

            $user->update(['role' => $request->role]);
    
            return Redirect::back()->with('message', 'User role updated successfully.');
        }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => 'required|in:admin,author',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return Redirect::back()->with('message', 'User created successfully.');
    }

    }


