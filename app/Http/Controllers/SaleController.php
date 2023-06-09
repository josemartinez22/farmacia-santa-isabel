<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Sales/Index', [
            'filters' => Request::all(['search']),
            'sales' => Sale::orderBy('created_at', 'desc')
                ->filter(Request::only(['search']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($sale) => [
                    'id' => $sale->id,
                    'user' => $sale->user->first_name . ' ' . $sale->user->last_name,
                    'total' => $sale->total,
                    'items' => $sale->items,
                    'cash' => $sale->cash,
                    'change' => $sale->change,
                    'status' => $sale->status,
                    'created_at' => $sale->created_at
                ]),
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(Sale $sale)
    {
        $details = [];

        $discTotal = 0;

        foreach ($sale->saleDetails as $sale_detail) {

            $discAmount = floatval($sale_detail->price) * $sale_detail->discount / 100;
            $discPrice = $sale_detail->price - $discAmount;
            $discQuantity = $discPrice * $sale_detail->quantity;
            $discTotal += $discQuantity;

            $details[] = [
                'product_name' => $sale_detail->product->name,
                'quantity' => $sale_detail->quantity,
                'price' => $sale_detail->price,
                'discPrice' => number_format($discPrice, 2),
                'discount' => $sale_detail->discount,
                'subtotal' => number_format($discQuantity, 2)
            ];
        }

        $total_items = $sale->saleDetails->sum('quantity');
        
        $total_price =number_format($discTotal, 2);

        $details = array(
            'sale_id' => $sale->id,
            'products' => $details
        );

        return Inertia::modal('Sales/Show')
            ->with([
                'sale_details' => $details,
                    'total_items' => $total_items,
                    'total_price' => $total_price
            ])
            ->baseRoute('sales');
    }
}
