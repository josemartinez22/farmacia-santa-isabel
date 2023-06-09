<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Purchases/Index', [
            'filters' => Request::all(['search']),
            'purchases' => Purchase::orderBy('created_at', 'desc')
                ->filter(Request::only(['search']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($purchase) => [
                    'id' => $purchase->id,
                    'quantity' => $purchase->quantity,
                    'total' => $purchase->total,
                    'product' => $purchase->product->name,
                    'supplier' => $purchase->supplier->name,
                    'user' => $purchase->user->name,
                    'created_at' => $purchase->created_at
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Purchases/Create', [
            'suppliers' => Supplier::select('id', 'name')->orderBy('name')->when(request('searchSuppliers'), function ($query, $searchSuppliers) {
                $query->where('name', 'like', "%$searchSuppliers%")
                ->limit(5)->get();
            })->get(),
            'products' => Category::join('products', 'categories.id', '=', 'products.category_id')
            ->select('products.id', 'products.name', 'products.barcode', 'products.cost', 'products.price', 'products.stock', 'products.alert', 'products.status', 'products.category_id',
            'categories.name as categoryName', 'categories.id as categoryId')
            ->orderBy('products.name')
            ->when(request('searchProducts'), function ($query, $searchProducts) {
                $query->where('products.name', 'like', "%$searchProducts%")
                    ->orWhere('products.barcode', 'like', '%'.$searchProducts.'%');
            })
            ->limit(5)
            ->get(),
            'categories' => Category::select('id', 'name')->orderBy('name')->when(request('searchCategory'), function ($query, $searchCategory) {
                $query->where('name', 'like', "%$searchCategory%");
            })->limit(5)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (Request::get('checkSupplier') == true and Request::get('checkProduct') == false) {
            //Nuevo proveedor & Actualizar producto existente

            Request::validate([
                'supplierName' => ['required', 'string', 'unique:suppliers,name', 'min:3', 'max:50'],
                'phone' => ['required', 'min:8', 'max:9'],
                'selectedProduct' => ['required'],
                'quantity' => ['required', 'integer', 'gte:1'],
                'total' => ['required', 'decimal:2', 'gte:0.01'],
            ]);

            Request::validate([
                'name' => ['sometimes', 'string', 'min:3', 'max:50', 'unique:products,name,' . Request::get('product_id')],
                'barcode' => ['sometimes'],
                'cost' => ['sometimes', 'decimal:2', 'gte:0.01'],
                'price' => ['sometimes', 'decimal:2', 'gte:0.01'],
                'stock' => ['sometimes', 'integer'],
                'alert' => ['sometimes', 'integer'],
                'status' => ['sometimes', 'integer'],
                'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
                'category_id' => ['sometimes', 'numeric'],
                'selectedCategory' => ['required']
            ]);

            DB::beginTransaction();

            try {
                //Supplier
                $supplier = Supplier::create([
                    'name' => Request::get('supplierName'),
                    'phone' => Request::get('phone'),
                ]);

                //Product
                $newStock = Request::get('stock') + Request::get('quantity');

                $product = Product::find(Request::get('product_id'));

                $product->update([
                    'name' => Request::get('name'),
                    'barcode' => Request::get('barcode'),
                    'cost' => Request::get('cost'),
                    'price' => Request::get('price'),
                    'stock' => $newStock,
                    'alert' => Request::get('alert'),
                    'status' => Request::get('status'),
                    'category_id' => Request::get('category_id')
                ]);

                if (Request::file('image')) {
                    if ($product->image !== null) {
                        Storage::delete($product->image);
                    }
                    $product->update(['image' => Request::file('image')->store('products')]);
                }

                Purchase::create([
                    'quantity' => Request::get('quantity'),
                    'total' => Request::get('total'),
                    'product_id' => Request::get('product_id'),
                    'supplier_id' => $supplier->id,
                    'user_id' => Auth::user()->id,
                ]);

                DB::commit();

                return Redirect::route('purchases')->with('success', 'Comprar registrada.');
                
            } catch (\Throwable $e) {

                DB::rollback();

                return Redirect::route('purchase.create')->with('error', $e);
            }
        } elseif (Request::get('checkSupplier') == false and Request::get('checkProduct') == true) {
            // Proveedor existente & Producto nuevo

            Request::validate([
                'selectedSupplier' => ['required'],
                'quantity' => ['required', 'integer'],
                'total' => ['required', 'decimal:2'],
                'name' => ['required', 'string', 'unique:products', 'min:3', 'max:50'],
                'barcode' => ['nullable'],
                'cost' => ['required', 'decimal:2', 'gte:0.01'],
                'price' => ['required', 'decimal:2', 'gte:0.01'],
                'stock' => ['required', 'integer', 'gte:1'],
                'alert' => ['required', 'integer'],
                'status' => ['required', 'integer'],
                'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
                'category_id' => ['required', 'numeric'],
                'selectedCategory' => ['required'],
                'quantity' => ['required', 'numeric', 'gte:1'],
                'total' => ['required', 'numeric', 'gte:0.01']
            ]);

            DB::beginTransaction();

            try {

                //Product
                $product = Product::create([
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

                Purchase::create([
                    'quantity' => Request::get('quantity'),
                    'total' => Request::get('total'),
                    'product_id' => $product->id,
                    'supplier_id' => Request::get('supplier_id'),
                    'user_id' => Auth::user()->id
                ]);

                DB::commit();

                return Redirect::route('purchases')->with('success', 'Comprar registrada.');
            } catch (\Throwable $e) {

                DB::rollback();

                return Redirect::route('purchase.create')->with('error', $e);
            }
        } elseif (Request::get('checkSupplier') == true and Request::get('checkProduct') == true) {
            // Proveedor nuevo & Producto nuevo

            Request::validate([
                'supplierName' => ['required', 'string', 'unique:suppliers,name', 'min:3', 'max:50'],
                'phone' => ['required', 'min:8', 'max:9'],
                'selectedCategory' => ['required'],
                'quantity' => ['required', 'integer'],
                'total' => ['required', 'decimal:2'],
                'name' => ['required', 'string', 'unique:products', 'min:3', 'max:50'],
                'barcode' => ['nullable'],
                'cost' => ['required', 'decimal:2'],
                'price' => ['required', 'decimal:2'],
                'stock' => ['required', 'integer'],
                'alert' => ['required', 'integer'],
                'status' => ['required', 'integer'],
                'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
                'category_id' => ['required', 'numeric']
            ]);

            DB::beginTransaction();

            try {

                //Supplier
                $supplier = Supplier::create([
                    'name' => Request::get('supplierName'),
                    'phone' => Request::get('phone'),
                ]);

                //Product
                $product = Product::create([
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

                Purchase::create([
                    'quantity' => Request::get('quantity'),
                    'total' => Request::get('total'),
                    'product_id' => $product->id,
                    'supplier_id' => $supplier->id,
                    'user_id' => Auth::user()->id
                ]);

                DB::commit();

                return Redirect::route('purchases')->with('success', 'Comprar registrada.');

            } catch (\Throwable $e) {

                DB::rollback();

                return Redirect::route('purchase.create')->with('error', $e);
            }

        } else {
            // Proveedor existente & Producto existente
            Request::validate([
                'selectedSupplier' => ['required'],
                'selectedProduct' => ['required']
            ]);

            Request::validate([
                'name' => ['sometimes', 'string', 'min:3', 'max:50', 'unique:products,name,' . Request::get('product_id')],
                'barcode' => ['sometimes'],
                'cost' => ['sometimes', 'decimal:2'],
                'price' => ['sometimes', 'decimal:2'],
                'stock' => ['sometimes', 'integer'],
                'alert' => ['sometimes', 'integer'],
                'status' => ['sometimes', 'integer'],
                'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
                'category_id' => ['sometimes', 'numeric'],
                'selectedCategory' => ['required']
            ]);

            DB::beginTransaction();

            try {

                //Product
                $newStock = Request::get('stock') + Request::get('quantity');

                $product = Product::find(Request::get('product_id'));

                $product->update([
                    'name' => Request::get('name'),
                    'barcode' => Request::get('barcode'),
                    'cost' => Request::get('cost'),
                    'price' => Request::get('price'),
                    'stock' => $newStock,
                    'alert' => Request::get('alert'),
                    'status' => Request::get('status'),
                    'category_id' => Request::get('category_id')
                ]);

                Purchase::create([
                    'quantity' => Request::get('quantity'),
                    'total' => Request::get('total'),
                    'product_id' => Request::get('product_id'),
                    'supplier_id' => Request::get('supplier_id'),
                    'user_id' => Auth::user()->id
                ]);

                DB::commit();

                return Redirect::route('purchases')->with('success', 'Comprar registrada.');

            } catch (\Throwable $e) {

                DB::rollback();

                return Redirect::route('purchase.create')->with('error', $e);
            }
        }
    }
}
