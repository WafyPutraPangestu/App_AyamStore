<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\OrdersReportExport; // Impor kelas export Anda
use Maatwebsite\Excel\Facades\Excel;

class OrderReportController extends Controller
{
    public function exportOrdersExcel()
    {
        $fileName = 'laporan_pesanan_ayam_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new OrdersReportExport, $fileName);
    }
}
