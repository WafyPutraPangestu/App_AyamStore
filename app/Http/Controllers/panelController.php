<?php

namespace App\Http\Controllers;

use App\Models\kurir; // Pastikan model Kurir di-import dengan benar
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class panelController extends Controller
{

    private function getStatusOnline(string $statusAsli): string
    {
        $statusAsli = strtolower(trim($statusAsli));
        if ($statusAsli === 'tersedia') {
            return 'online';
        } elseif ($statusAsli === 'tidak_aktif') {
            return 'offline';
        } elseif ($statusAsli === 'sedang_mengantar') {
            return 'online';
        }
        return 'offline';
    }

    private function calculatePerformanceMetrics($kurir)
    {
        // Ambil data order dalam 30 hari terakhir untuk analisis yang lebih komprehensif
        $orders = $kurir->orders()
            ->whereIn('status_pengiriman', ['terkirim', 'dalam_perjalanan'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();

        // Hitung rata-rata waktu pengantaran
        $averageDeliveryTime = 0;
        $deliveredOrders = $orders->where('status_pengiriman', 'terkirim');

        if ($deliveredOrders->count() > 0) {
            $totalDeliveryMinutes = 0;
            foreach ($deliveredOrders as $order) {
                if ($order->waktu_mulai_antar && $order->waktu_selesai_antar) {
                    $startTime = Carbon::parse($order->waktu_mulai_antar);
                    $endTime = Carbon::parse($order->waktu_selesai_antar);
                    $totalDeliveryMinutes += $startTime->diffInMinutes($endTime);
                }
            }
            $averageDeliveryTime = $deliveredOrders->count() > 0 ?
                round($totalDeliveryMinutes / $deliveredOrders->count()) : 0;
        }

        // Hitung waktu aktif harian (dari cache login activity)
        $dailyActiveHours = $this->calculateDailyActiveHours($kurir->user_id);

        // Hitung success rate (order terkirim vs total order)
        $totalOrders = $orders->count();
        $successfulOrders = $deliveredOrders->count();
        $successRate = $totalOrders > 0 ? ($successfulOrders / $totalOrders) * 100 : 0;

        // Hitung order per jam ketika aktif
        $ordersPerHour = $dailyActiveHours > 0 ? round($totalOrders / $dailyActiveHours, 1) : 0;

        // Rating performance berdasarkan multiple factors
        $performanceScore = $this->calculatePerformanceScore([
            'success_rate' => $successRate,
            'avg_delivery_time' => $averageDeliveryTime,
            'orders_per_hour' => $ordersPerHour,
            'daily_active_hours' => $dailyActiveHours
        ]);

        return [
            'avg_delivery_time_minutes' => $averageDeliveryTime,
            'avg_delivery_time_formatted' => $this->formatDeliveryTime($averageDeliveryTime),
            'daily_active_hours' => $dailyActiveHours,
            'success_rate' => round($successRate, 1),
            'orders_per_hour' => $ordersPerHour,
            'performance_score' => $performanceScore,
            'performance_grade' => $this->getPerformanceGrade($performanceScore),
            'total_orders_30days' => $totalOrders,
            'successful_orders_30days' => $successfulOrders
        ];
    }

    private function calculateDailyActiveHours($userId)
    {
        // Ambil data aktivitas login dari cache atau database
        $loginSessions = Cache::get("daily_sessions_user_{$userId}", []);

        if (empty($loginSessions)) {
            // Fallback: estimasi berdasarkan status kurir dan waktu login terakhir
            $lastLogin = Cache::get("last_login_at_user_{$userId}");
            if ($lastLogin && Carbon::parse($lastLogin)->isToday()) {
                return Carbon::parse($lastLogin)->diffInHours(Carbon::now());
            }
            return 0;
        }

        // Hitung total jam aktif hari ini
        $totalHours = 0;
        foreach ($loginSessions as $session) {
            if (isset($session['start']) && isset($session['end'])) {
                $start = Carbon::parse($session['start']);
                $end = Carbon::parse($session['end']);
                $totalHours += $start->diffInHours($end);
            }
        }

        return round($totalHours, 1);
    }

    private function formatDeliveryTime($minutes)
    {
        if ($minutes < 60) {
            return $minutes . ' menit';
        } else {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            return $hours . 'j ' . $remainingMinutes . 'm';
        }
    }

    private function calculatePerformanceScore($metrics)
    {
        $score = 0;

        // Success rate (40% dari total score)
        $score += ($metrics['success_rate'] / 100) * 40;

        // Delivery time efficiency (30% dari total score)
        // Semakin cepat semakin baik, max score jika < 30 menit
        $deliveryScore = max(0, (120 - $metrics['avg_delivery_time']) / 120) * 30;
        $score += $deliveryScore;

        // Activity level (20% dari total score)
        // Max score jika aktif > 8 jam per hari
        $activityScore = min(($metrics['daily_active_hours'] / 8), 1) * 20;
        $score += $activityScore;

        // Productivity (10% dari total score)
        // Max score jika > 2 order per jam
        $productivityScore = min(($metrics['orders_per_hour'] / 2), 1) * 10;
        $score += $productivityScore;

        return round($score, 1);
    }

    private function getPerformanceGrade($score)
    {
        if ($score >= 90) return ['grade' => 'A+', 'color' => 'green'];
        if ($score >= 80) return ['grade' => 'A', 'color' => 'green'];
        if ($score >= 70) return ['grade' => 'B+', 'color' => 'blue'];
        if ($score >= 60) return ['grade' => 'B', 'color' => 'blue'];
        if ($score >= 50) return ['grade' => 'C+', 'color' => 'yellow'];
        if ($score >= 40) return ['grade' => 'C', 'color' => 'orange'];
        return ['grade' => 'D', 'color' => 'red'];
    }

    public function panelView()
    {
        $kurirs = Kurir::with(['user', 'orders' => function ($query) {
            $query->whereIn('status_pengiriman', ['terkirim', 'dalam_perjalanan'])
                ->where('created_at', '>=', Carbon::now()->subDays(30));
        }])->get()
            ->map(function ($kurir) {
                // Data order hari ini untuk statistik cepat
                $todayOrders = $kurir->orders()
                    ->where('status_pengiriman', 'terkirim')
                    ->whereDate('updated_at', Carbon::today())
                    ->count();

                $lastLoginRaw = Cache::get("last_login_at_user_{$kurir->user_id}");

                // Status mapping
                $statusFrontend = 'tidak_aktif';
                if ($kurir->status === 'tersedia') {
                    $statusFrontend = 'tersedia';
                } elseif ($kurir->status === 'tidak aktif') {
                    $statusFrontend = 'tidak_aktif';
                } elseif ($kurir->status === 'sedang_mengantar') {
                    $statusFrontend = 'sedang_mengantar';
                }

                $statusOnline = $this->getStatusOnline($kurir->status);

                // Hitung performance metrics
                $performanceMetrics = $this->calculatePerformanceMetrics($kurir);

                return [
                    'id' => $kurir->id,
                    'nama' => $kurir->user ? $kurir->user->name : 'Nama Tidak Tersedia',
                    'status_kurir' => $statusFrontend,
                    'status_online' => $statusOnline,
                    'status' => $kurir->status,
                    'total_order' => $todayOrders, // Order hari ini
                    'order_selesai' => $todayOrders, // Semua order hari ini dianggap selesai
                    'login_terakhir' => $lastLoginRaw ? Carbon::parse($lastLoginRaw) : null,

                    // Performance metrics baru
                    'performance' => $performanceMetrics
                ];
            });

        return view('admin.panel', compact('kurirs'));
    }

    public function detailKurir($id)
    {
        $kurir = Kurir::with(['user'])->findOrFail($id);

        $ordersToday = $kurir->orders()
            ->where('status_pengiriman', 'terkirim')
            ->whereDate('updated_at', Carbon::today())
            ->get();

        $ordersAll = $kurir->orders()->get();

        $totalOrdersToday = $ordersToday->count();
        $completedOrdersToday = $totalOrdersToday;

        $lastLoginRaw = Cache::get("last_login_at_user_{$kurir->user_id}"); // Pastikan $kurir->user_id atau $kurir->user->id
        $lastLogin = $lastLoginRaw ? Carbon::parse($lastLoginRaw)->diffForHumans() : 'Belum pernah login';

        return view('admin.detailKurir', compact(
            'kurir',
            'ordersToday',
            'ordersAll',
            'totalOrdersToday',
            'completedOrdersToday',
            'lastLogin'
        ));
    }

    public function performaKurir($id)
    {
        return view('admin.performa');
    }
}
