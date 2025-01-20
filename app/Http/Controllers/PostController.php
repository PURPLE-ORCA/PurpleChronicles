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

class PostController extends Controller
{
    public function index()
    {
    
        $allPosts = Post::with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get();
    
        $comments = Comment::with(['user', 'post'])
            ->where('status', 'pending')
            ->latest()
            ->take(9)
            ->get()
            ->map(function ($comment) {
                $comment->created_at_human = $comment->created_at->diffForHumans();
                return $comment;
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
        // Authorize the action using Gates
        Gate::authorize('admin');
    
        $request->validate([
            'role' => 'required|in:admin,author',
        ]);
    
        $user->update(['role' => $request->role]);
    
        return Redirect::back()->with('message', 'User role updated successfully.');
    }
    
    public function createUser(Request $request)
    {
        // Authorize the action using Gates
        Gate::authorize('admin');
    
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
   
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        // Authorize the action using Gates
        Gate::authorize('create', Post::class);
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'featured_image' => 'nullable|image',
        ]);
    
        $post = new Post();
        $post->title = $validated['title'];
        $post->slug = $this->generateUniqueSlug($validated['title']);
        $post->content = $validated['content'];
        $post->category_id = $validated['category_id'];
        $post->user_id = Auth::id();
        $post->status = 'published';
    
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $path;
        }
    
        $post->save();
    
        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }
    
        return redirect()->route('blog.posts.show', $post->slug)
            ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->increment('views');
        $post->load(['user', 'category', 'tags', 'comments' => function($query) {
            $query->where('status', 'approved')->latest();
        }]);
        
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();

            
        return Inertia::render('Post', [
             'post' => $post,
             'relatedPosts' => $relatedPosts
        ]);

        // return view('posts.show', compact('post', 'relatedPosts'));
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $post->title = $validated['title'];
        $post->slug = $this->generateUniqueSlug($validated['title'], $post->id);
        $post->content = $validated['content'];
        $post->category_id = $validated['category_id'];

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $path;
        }

        $post->save();

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('blog.posts.show', $post->slug)
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $post->delete();
        return redirect()->route('blog.posts.index')
            ->with('success', 'Post deleted successfully!');
    }

    private function generateUniqueSlug($title, $postId = null)
    {
        $slug = Str::slug($title);
        $count = Post::where('slug', 'like', "{$slug}%")
            ->when($postId, function ($query, $postId) {
                return $query->where('id', '!=', $postId);
            })
            ->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}