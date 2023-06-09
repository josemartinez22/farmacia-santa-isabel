<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use App\Exports\SalesExport;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'users' => User::select('id', 'first_name', 'last_name')->orderBy('first_name')->when(request('search'), function ($query, $search) {
                $query->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', '%'.$search.'%');
            })->limit(5)->get()
        ]);
    }

    public function reportPDF($userId, $reportType, $reportFrom = null, $reportTo = null)
    {
        $data = [];

        if ($reportFrom == '' && $reportTo == '') {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
        } else {
            $from = Carbon::parse($reportFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($reportTo)->format('Y-m-d') . ' 23:59:59';
        }

        if ($reportType == "0") {
    
            if ($userId == 0) {
                $data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS user"))
                ->whereBetween('sales.created_at', [$from, $to])
                ->get();
            } else {
                $data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS user"))
                ->whereBetween('sales.created_at', [$from, $to])
                ->where('user_id', $userId)
                ->get();
            }
    
            $user = $userId == 0 ? 'Todos' : User::find($userId)->getFullName();
    
            $dateReportPDF = $reportFrom == null && $reportTo == null ? Carbon::parse(Carbon::now())->format('d-m-Y') : Carbon::parse($reportFrom)->format('d-m-Y') . ' al ' . Carbon::parse($reportTo)->format('d-m-Y');
            $reportNamePDF = 'Reporte de ventas_' . $dateReportPDF . '.pdf';
    
            $pdf = PDF::loadView('pdf.report', compact('data', 'reportType', 'user', 'reportFrom', 'reportTo'));
    
            return $pdf->stream($reportNamePDF);

        } else {

            if ($userId == 0) {
                $data = Purchase::join('suppliers as s', 's.id', 'purchases.supplier_id')
                    ->join('products as p', 'p.id', 'purchases.product_id')
                    ->join('users as u', 'u.id', 'purchases.user_id')
                    ->select('purchases.quantity', 'purchases.total', 's.name as supplier', 'p.name as products', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS user"), 'purchases.created_at as date')
                    ->whereBetween('purchases.created_at', [$from, $to])
                    ->get();
            } else {
                $data = Purchase::join('suppliers as s', 's.id', 'purchases.supplier_id')
                    ->join('products as p', 'p.id', 'purchases.product_id')
                    ->join('users as u', 'u.id', 'purchases.user_id')
                    ->select('purchases.quantity', 'purchases.total', 's.name as supplier', 'p.name as products', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS user"), 'purchases.created_at as date')
                    ->whereBetween('purchases.created_at', [$from, $to])
                    ->where('user_id', $userId)
                    ->get();
            }

            $user = $userId == 0 ? 'Todos' : User::find($userId)->getFullName();

            $dateReportPDF = $reportFrom == null && $reportTo == null ? Carbon::parse(Carbon::now())->format('d-m-Y') : Carbon::parse($reportFrom)->format('d-m-Y') . ' al ' . Carbon::parse($reportTo)->format('d-m-Y');
            $reportNamePDF = 'Reporte de compras_' . $dateReportPDF . '.pdf';
    
            $pdf = PDF::loadView('pdf.reportc', compact('data', 'reportType', 'user', 'reportFrom', 'reportTo'));
    
            return $pdf->stream($reportNamePDF);
        }
        

    }

    public function reportExcel($userId, $reportType, $reportFrom = null, $reportTo = null)
    {

        if ($reportFrom == '' && $reportTo == '') {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
        } else {
            $from = Carbon::parse($reportFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($reportTo)->format('Y-m-d') . ' 23:59:59';
        }

        if ($reportType == "0") {

            $dateReportExcel = $reportFrom == null && $reportTo == null ? Carbon::parse(Carbon::now())->format('d-m-Y') : Carbon::parse($reportFrom)->format('d-m-Y') . ' al ' . Carbon::parse($reportTo)->format('d-m-Y');
            $reportNameExcel = 'Reporte de ventas_' . $dateReportExcel . '.xlsx';
    
            return Excel::download(new SalesExport($userId, $reportFrom, $reportTo), $reportNameExcel);

        } else {
            $dateReportExcel = $reportFrom == null && $reportTo == null ? Carbon::parse(Carbon::now())->format('d-m-Y') : Carbon::parse($reportFrom)->format('d-m-Y') . ' al ' . Carbon::parse($reportTo)->format('d-m-Y');
            $reportNameExcel = 'Reporte de compras_' . $dateReportExcel . '.xlsx';

            return Excel::download(new PurchasesExport($userId, $reportFrom, $reportTo), $reportNameExcel);
        }
        
    }

    public function invoicePDF($saleID)
    {
        $sale = Sale::find($saleID);

        $user = User::find($sale->user_id);
        $products = Sale::join('sale_details as sd', 'sales.id', 'sd.sale_id')
            ->join('products as p', 'sd.product_id', 'p.id')
            ->select('sd.price', 'sd.quantity', 'sd.discount', 'p.name')
            ->where('sd.sale_id', $sale->id)
            ->get();

        $reportNamePDF = "Factura Consumidor Final $sale->created_at";

        $pdf = PDF::loadView('invoice.invoice', compact('user', 'products', 'sale'))->setPaper("a5", 'portrait');

        return $pdf->stream($reportNamePDF);
    }
}
