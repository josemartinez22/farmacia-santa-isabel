<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {

        $names = [];
        $sales = [];
        $stockProducts = [];
        $topProducts = [];

        $byCategories = SaleDetail::join('products AS p', 'sale_details.product_id', 'p.id')
                ->join('categories AS c', 'p.category_id', 'c.id')
                ->select('c.name as name', DB::raw('SUM(sale_details.quantity) AS sales'))
                ->groupBy(['c.id','c.name'])
                ->orderByRaw('sales DESC')
                ->limit(3)
                ->get();
        
        $jsonArr = json_decode($byCategories);
        
        $formatData = [['Categoría', 'Ventas']];
        foreach ($jsonArr as $item) {
            array_push($formatData, [$item->name, intval($item->sales)]);
        }

        $topProducts = Product::join('sale_details AS sd','products.id','sd.product_id')
                ->select('products.name','sd.price','sd.discount',DB::raw('SUM(sd.quantity) AS sold'))
                ->groupBy(['products.id','products.name','sd.price','sd.discount'])
                ->orderByRaw('sold DESC')
                ->limit(10)
                ->get();

        $stockProducts = Product::select('products.name', 'products.stock', 'products.alert')
                ->where('products.stock','=',0)
                ->orWhere('products.stock','<=',DB::raw('products.alert'))
                ->orderBy('products.stock', 'ASC')
                ->get();

        return Inertia::render('Dashboard', [
            'topProducts' => $topProducts,
            'stockProducts' => $stockProducts,
            'byCategories' => $formatData
        ]);
    }
}
