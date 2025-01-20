<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        // Authorize the action using Gates
    
        $categories = Category::withCount('posts')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    // Repeat the same for other methods (create, store, edit, update, destroy)

    public function create()
    {
        Gate::authorize('admin');

        $categories = Category::all(); // For parent category selection
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('admin');

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
        $category->load('posts'); // Load articles related to this category
        $categories = Category::all();

        return Inertia::render('SCategory', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function edit(Category $category)
    {
        Gate::authorize('admin');

        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('admin');

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
        Gate::authorize('admin');

        if ($category->posts()->exists()) {
            return back()->with('error', 'Cannot delete category with associated posts.');
        }

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}