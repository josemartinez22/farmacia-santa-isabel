<?php

namespace App\Exports;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchasesExport implements FromCollection, WithHeadings, WithCustomStartCell, WithTitle, WithStyles, WithColumnFormatting, WithMapping, ShouldAutoSize
{
    protected $userId, $reportFrom, $reportTo;

    public function __construct($userId, $f1, $f2) {
        $this->userId = $userId;
        $this->reportFrom = $f1;
        $this->reportTo = $f2;
    }

    public function collection()
    {
        $data = [];

        if ($this->reportFrom == '' && $this->reportTo == '') {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
        } else {
            $from = Carbon::parse($this->reportFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->reportTo)->format('Y-m-d') . ' 23:59:59';
        }

        if ($this->userId == 0) {
            $data = Purchase::join('suppliers as s', 's.id', 'purchases.supplier_id')
            ->join('products as p', 'p.id', 'purchases.product_id')
            ->join('users as u', 'u.id', 'purchases.user_id')
            ->select('purchases.quantity', 'purchases.total', 's.name as supplier', 'p.name as products', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS user"), 'purchases.created_at')
            ->whereBetween('purchases.created_at', [$from, $to])
            ->get();
        } else {
            $data = Purchase::join('suppliers as s', 's.id', 'purchases.supplier_id')
            ->join('products as p', 'p.id', 'purchases.product_id')
            ->join('users as u', 'u.id', 'purchases.user_id')
            ->select('purchases.quantity', 'purchases.total', 's.name as supplier', 'p.name as products', DB::raw("CONCAT(u.first_name, ' ', u.last_name) AS user"), 'purchases.created_at')
            ->whereBetween('purchases.created_at', [$from, $to])
            ->where('user_id', $this->userId)
            ->get();
        }

        return $data;
    }

    public function headings() : array
    {
        return ["PROVEEDOR", "PRODUCTO", "REGISTRO", "CANTIDAD", "TOTAL", "FECHA"];
    }

    public function startCell() : string
    {
        return 'A2';
    }

    public function styles(Worksheet $worksheet)
    {
        $worksheet->getStyle('2')->getFont()->setBold(true);
        $worksheet->getStyle('A:F')->getAlignment()->setHorizontal('center');
    }

    public function map($data): array
    {
        return [
            $data->supplier,
            $data->products,
            $data->user,
            $data->quantity,
            $data->total,
            Date::dateTimeToExcel($data->created_at)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function title() : string
    {
        return 'Reporte de compras';
    }
}
