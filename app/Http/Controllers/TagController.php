<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:tags'
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function show(Tag $tag)
    {
        $posts = $tag->posts()
            ->where('status', 'published')
            ->with(['user', 'category'])
            ->latest()
            ->paginate(10);

        return view('blog.tags.show', compact('tag', 'posts'));
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:tags,name,' . $tag->id
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    public function destroy(Tag $tag)
    {
        $tag->posts()->detach(); // Remove tag associations but keep posts
        $tag->delete();
        
        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}