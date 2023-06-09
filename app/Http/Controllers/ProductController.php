<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class ProductController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Products/Index', [
            'filters' => Request::all(['search']),
            'products' => Product::orderBy('name')
                ->filter(Request::only(['search']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'barcode' => $product->barcode,
                    'category' => $product->category->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'status' => $product->status,
                    'image' => $product->image ? URL::route('image', ['path' => $product->image, 'w' => 45, 'h' => 45, 'fit' => 'crop']) : null,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::select('id','name')->orderBy('name')->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })->limit(10)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $request->validated();

        Product::create([
            'name' => Request::get('name'),
            'barcode' => Request::get('barcode'),
            'cost' => Request::get('cost'),
            'price' => Request::get('price'),
            'stock' => Request::get('stock'),
            'alert' => Request::get('alert'),
            'status' => Request::get('status'),
            'image' => Request::file('image') ? Request::file('image')->store('products') : null,
            'category_id' => Request::get('category_id')
        ]);

        return Redirect::route('products')->with('success', 'Producto creado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'cost' => $product->cost,
                'price' => $product->price,
                'stock' => $product->stock,
                'alert' => $product->alert,
                'status' => $product->status,
                'image' => $product->image ? URL::route('image', ['path' => $product->image, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'category_id' => $product->category_id
            ],
            'selected' => Category::select('id','name')->where('id', $product->category_id)->get(),
            'categories' => Category::select('id','name')->orderBy('name')->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })->limit(10)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $request->validated();

        $product->update(Request::only(
            'name', 'barcode' , 'cost', 'price', 'stock', 'alert', 'status', 'category_id'));

        if (Request::file('image')) {
            if ($product->image !== null) {
                Storage::delete($product->image);
            }
            $product->update(['image' => Request::file('image')->store('products')]);
        }

        return Redirect::back()->with('success', 'Producto actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
