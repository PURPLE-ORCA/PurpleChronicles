<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all(); // For parent category selection
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        $posts = $category->posts()
            ->where('status', 'published')
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('blog.categories.show', compact('category', 'posts'));
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->exists()) {
            return back()->with('error', 'Cannot delete category with associated posts.');
        }

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}