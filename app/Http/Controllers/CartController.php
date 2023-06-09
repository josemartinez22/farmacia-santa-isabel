<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $cart = Cart::where('user_id', Auth::user()->id)->first();

        if ($cart) {

            $cart_id = $cart->id;

            $cart_items = [];

            $discTotal = 0;

            foreach ($cart->products as $item) {


                $discAmount = floatval($item->price) * $item->pivot->discount / 100;
                $discPrice = $item->price - $discAmount;
                $discQuantity = $discPrice * $item->pivot->quantity;
                $discTotal += $discQuantity;

                $cart_items[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'stock' => $item->stock,
                    'image' => $item->image ? URL::route('image', ['path' => $item->image, 'w' => 45, 'h' => 45, 'fit' => 'crop']) : null,
                    'quantity' => $item->pivot->quantity,
                    'discount' => $item->pivot->discount
                ];
            }

            $format_total = number_format($discTotal, 2);

            $total_items = $cart->products->sum('pivot.quantity');
        } else {
            $format_total = 0;
            $cart_items = [];
            $cart_id = null;
            $total_items = 0;
        }


        return Inertia::render('Cart/Index', [
            'cart' => $cart_items,
            'cart_id' => $cart_id,
            'total_items' => $total_items,
            'total_price' => $format_total,
            'filters' => Category::join('products', 'categories.id', '=', 'products.category_id')
                ->select('products.id', 'products.name', 'products.price', 'products.image', 'categories.name as category')
                ->where('products.stock', '>', 0)
                ->where('products.status', '>', 0)
                ->orderBy('products.name')
                ->when(request('search'), function ($query, $search) {
                    $query->where('products.name', 'like', "%$search%")
                        ->orWhere('products.barcode', 'like', '%'.$search.'%');
                })
                ->limit(5)
                ->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($productid)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->first();

        if ($cart) {

            $hasProduct = $cart->products()->where('product_id', $productid)->exists();

            if ($hasProduct) {
                $stock = $cart->products()->where('product_id', $productid)->first()->stock;

                $quantity = $cart->products()->where('product_id', $productid)->first()->pivot->quantity;

                if ($quantity > $stock) {
                    $newQuantity = $stock;
                } else {
                    $newQuantity = $quantity + 1;
                }

                $cart->products()->sync([$productid => [
                    'quantity' => $newQuantity,
                    'updated_at' => now()
                ]], false);
            } else {

                $cart->products()->attach($productid, [
                    'quantity' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $msg = 'Carrito actualizado.';
        } else {

            $cart = Cart::create([
                'user_id' => Auth::user()->id
            ]);

            $cart->products()->attach($productid, [
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $msg = 'Se agregó el producto.';
        }

        return Redirect::route('cart')->with('success', $msg);
    }

    public function attach(Request $request, Cart $cart)
    {

        if (Request::get('newCartQuantity')) {
            $request = Request::get('newCartQuantity');
            
            $checkProduct = $cart->products()->where('product_id', $request['id'])->exists();

            if ($checkProduct) {
                $stock = $cart->products()->where('product_id', $request['id'])->first()->stock;
    
                if ($request['quantity'] > $stock) {
                    $newQuantity = $stock;
                } else {
                    $newQuantity = $request['quantity'];
                }
    
                $cart->products()->sync([$request['id'] => [
                    'quantity' => $newQuantity,
                    'updated_at' => now()
                ]], false);
            }
        } else {
            $request = Request::get('newCartDiscount');
            
            $cart->products()->sync([$request['id'] => [
                'discount' => $request['discount'],
                'updated_at' => now()
            ]], false);
        }

        return Redirect::route('cart')->with('success', 'Carrito actualizado.');
    }

    public function detach($cartid, $productid)
    {
        $cart = Cart::find($cartid);

        $cart->products()->detach($productid);

        if (!count($cart->products)) {
            $cart->delete();
        }

        return Redirect::route('cart')->with('success', 'Producto eliminado.');
    }

    public function checkout(Request $request, Cart $cart)
    {
        $request = Request::all();

        foreach ($request['checkout']['cart'] as $check) {

            $product = Product::find($check['id']);

            if ($product->stock <= 0) {

                $cart->products()->detach($check['id']);

                return Redirect::back()->with('error', 'El producto: ' . $product->name . ' se quedó sin existencias.');
            }

        }

        DB::beginTransaction();

        try {

            $sale = Sale::create([
                'total' => $request['checkout']['totalPrice'],
                'items' => $request['checkout']['totalItems'],
                'cash' => number_format($request['checkout']['cash'], 2),
                'change' => $request['checkout']['change'],
                'name' => $request['checkout']['name'] ? $request['checkout']['name'] : null,
                'address' => $request['checkout']['address'] ? $request['checkout']['address'] : null,
                'duiornit' =>$request['checkout']['duiornit'] ? $request['checkout']['duiornit'] : null,
                'user_id' => Auth::user()->id
            ]);

            if ($sale) {

                foreach ($request['checkout']['cart'] as $item) {
                    SaleDetail::create([
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'discount' => $item['discount'],
                        'product_id' => $item['id'],
                        'sale_id' => $sale->id
                    ]);

                    $product = Product::find($item['id']);
                    $product->stock = $product->stock - $item['quantity'];
                    $product->save();
                }
            }

            $cart->delete();

            DB::commit();
            return Inertia::location("invoice/$sale->id");

        } catch (\Throwable $e) {
            
            DB::rollback();

            return Redirect::back()->with('error', $e);
        }
    }
}
