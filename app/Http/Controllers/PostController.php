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
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])
        ->where('status', 'published')
        ->latest()
        ->paginate(6);

        $featuredPosts = Post::with(['user', 'category'])
        ->where('status', 'published')
        ->orderBy('views', 'desc')
        ->take(16)
        ->get();

        $comments = Comment::with(['user', 'post'])
        ->where('status', 'approved')
          ->latest()
          ->take(9)
         ->get()
          ->map(function ($comment) {
             $comment->created_at_human = $comment->created_at->diffForHumans();
              return $comment;
          });

        $authors = User::where('role', 'author')
            ->withCount('posts')
            ->get();

        $categories = Category::all();

    return Inertia::render('Welcome', [
        'posts' => $posts,
        'featuredPosts' => $featuredPosts, 
        'comments' => $comments,
        'authors' => $authors,
        'categories' => $categories
    ]);
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'featured_image' => 'nullable|image|max:2048',
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

        return view('posts.show', compact('post', 'relatedPosts'));
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