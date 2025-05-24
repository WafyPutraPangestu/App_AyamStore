<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Statistik dasar
        $totalUsers              = User::where('role', 'user')->count();
        $totalProduks            = Produk::count();
        $totalOrders             = Order::count();
        $totalPembayaranBerhasil = Pembayaran::where('status', 'berhasil')->count();
        $totalPendapatan         = Order::where('status', 'selesai')->sum('total');
        $recentOrders            = Order::with('user')->latest()->take(5)->get();


        // Helper: bangun rentang tanggal untuk 7 dan 30 hari
        $buildRange = function (int $days) {
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $result[] = now()->subDays($i)->format('Y-m-d');
            }
            return $result;
        };

        // Helper: hitung total pesanan per tanggal
        $buildTotals = function ($range) {
            $result = [];

            // Query data
            $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereBetween('created_at', [
                    $range[0] . ' 00:00:00',
                    end($range) . ' 23:59:59'
                ])
                ->groupBy('date')
                ->get();

            // Buat array dengan key tanggal
            $ordersByDate = [];
            foreach ($orders as $order) {
                $ordersByDate[$order->date] = $order->total;
            }

            // Map ke array hasil
            foreach ($range as $date) {
                $result[] = $ordersByDate[$date] ?? 0;
            }

            return $result;
        };

        // Data 7 hari
        $range7  = $buildRange(7);
        $totals7 = $buildTotals($range7);

        // Data 30 hari
        $range30  = $buildRange(30);
        $totals30 = $buildTotals($range30);

        // Data 12 bulan
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        // Query data bulanan dengan strftime untuk SQLite
        $monthlyOrders = DB::table('orders')
            ->selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as total")
            ->whereRaw("created_at >= ?", [$months[0] . '-01'])
            ->groupBy('month')
            ->get();

        // Susun data bulanan
        $ordersByMonth = [];
        foreach ($monthlyOrders as $order) {
            $ordersByMonth[$order->month] = $order->total;
        }

        // Map ke array hasil
        $totals12 = [];
        foreach ($months as $month) {
            $totals12[] = $ordersByMonth[$month] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProduks',
            'totalOrders',
            'totalPembayaranBerhasil',
            'totalPendapatan',
            'recentOrders',
            'range7',
            'totals7',
            'range30',
            'totals30',
            'months',
            'totals12'
        ));
    }

    public function dashboardUser()
    {
        // dd('$pemabayaran');

        return view('user.dashboard');
    }

    public function profileView()
    {
        return view('user.profile');
    }

    public function detailOrder()
    {
        $allOrders = Order::with('user')->latest()->simplePaginate(10);
        return view('admin.orderDetail', compact('allOrders'));
    }
}
