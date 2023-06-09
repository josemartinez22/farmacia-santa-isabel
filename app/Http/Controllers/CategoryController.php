<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'filters' => Request::all(['search']),
            'categories' => Category::orderBy('name')
                ->filter(Request::only(['search']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'image' => $category->image ? URL::route('image', ['path' => $category->image, 'w' => 45, 'h' => 45, 'fit' => 'crop']) : null,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $request->validated();

        Category::create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
            'image' => Request::file('image') ? Request::file('image')->store('categories') : null,
        ]);

        return Redirect::route('categories')->with('success', 'Categoría creada.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => $category->image ? URL::route('image', ['path' => $category->image, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $request->validated();

        $category->update(Request::only('name', 'description'));

        if (Request::file('image')) {
            if ($category->image !== null) {
                Storage::delete($category->image);
            }
            $category->update(['image' => Request::file('image')->store('categories')]);
        }

        return Redirect::back()->with('success', 'Categoría actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
