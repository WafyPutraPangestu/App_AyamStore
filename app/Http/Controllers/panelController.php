<?php

namespace App\Http\Controllers;

use App\Models\kurir; // Pastikan model Kurir di-import dengan benar
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        Log::info("Kurir ID: {$kurir->id}, Total orders in last 30 days: {$orders->count()}");

        // Hitung rata-rata waktu pengantaran
        $averageDeliveryTime = 0;
        $deliveredOrders = $orders->where('status_pengiriman', 'terkirim');
        Log::info("Kurir ID: {$kurir->id}, Delivered orders: {$deliveredOrders->count()}");

        if ($deliveredOrders->count() > 0) {
            $totalDeliveryMinutes = 0;
            $validOrders = 0;

            foreach ($deliveredOrders as $order) {
                Log::info("Order ID: {$order->id}, waktu_mulai_antar: {$order->waktu_mulai_antar}, waktu_selesai_antar: {$order->waktu_selesai_antar}");

                if ($order->waktu_mulai_antar && $order->waktu_selesai_antar) {
                    try {
                        $startTime = Carbon::parse($order->waktu_mulai_antar);
                        $endTime = Carbon::parse($order->waktu_selesai_antar);

                        if ($endTime->greaterThan($startTime)) {
                            $deliveryMinutes = $startTime->diffInMinutes($endTime);
                            $totalDeliveryMinutes += $deliveryMinutes;
                            $validOrders++;
                            Log::info("Order ID: {$order->id}, Delivery time: {$deliveryMinutes} minutes");
                        } else {
                            Log::warning("Order ID: {$order->id}, Invalid time: End time ({$endTime}) <= Start time ({$startTime})");
                        }
                    } catch (\Exception $e) {
                        Log::error("Order ID: {$order->id}, Error parsing time: {$e->getMessage()}");
                    }
                } else {
                    Log::warning("Order ID: {$order->id}, Missing waktu_mulai_antar or waktu_selesai_antar");
                }
            }

            if ($validOrders > 0) {
                $averageDeliveryTime = $totalDeliveryMinutes / $validOrders;
                Log::info("Kurir ID: {$kurir->id}, Total delivery minutes: {$totalDeliveryMinutes}, Valid orders: {$validOrders}, Avg: {$averageDeliveryTime}");
                $averageDeliveryTime = round($averageDeliveryTime, 1);
            } else {
                Log::info("Kurir ID: {$kurir->id}, No valid orders with complete delivery time data");
            }
        } else {
            Log::info("Kurir ID: {$kurir->id}, No delivered orders found");
        }

        // Hitung waktu aktif harian (dari cache login activity)
        $dailyActiveHours = $this->calculateDailyActiveHours($kurir->user_id);
        Log::info("Kurir ID: {$kurir->id}, Daily active hours: {$dailyActiveHours}");

        // Hitung success rate (order terkirim vs total order)
        $totalOrders = $orders->count();
        $successfulOrders = $deliveredOrders->count();
        $successRate = $totalOrders > 0 ? ($successfulOrders / $totalOrders) * 100 : 0;
        Log::info("Kurir ID: {$kurir->id}, Success rate: {$successRate}% (Successful: {$successfulOrders}, Total: {$totalOrders})");

        // Hitung order per jam ketika aktif
        $ordersPerHour = $dailyActiveHours > 0 ? round($totalOrders / $dailyActiveHours, 1) : 0;
        Log::info("Kurir ID: {$kurir->id}, Orders per hour: {$ordersPerHour}");

        // Rating performance berdasarkan multiple factors
        $performanceScore = $this->calculatePerformanceScore([
            'success_rate' => $successRate,
            'avg_delivery_time' => $averageDeliveryTime,
            'orders_per_hour' => $ordersPerHour,
            'daily_active_hours' => $dailyActiveHours
        ]);
        Log::info("Kurir ID: {$kurir->id}, Performance score: {$performanceScore}");

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
        Log::info("User ID: {$userId}, Login sessions: " . json_encode($loginSessions));

        if (empty($loginSessions)) {
            // Fallback: estimasi berdasarkan status kurir dan waktu login terakhir
            $lastLogin = Cache::get("last_login_at_user_{$userId}");
            Log::info("User ID: {$userId}, Last login: {$lastLogin}");
            if ($lastLogin && Carbon::parse($lastLogin)->isToday()) {
                $hours = Carbon::parse($lastLogin)->diffInHours(Carbon::now());
                Log::info("User ID: {$userId}, Fallback active hours: {$hours}");
                return $hours;
            }
            Log::info("User ID: {$userId}, No active hours (no login today)");
            return 0;
        }

        // Hitung total jam aktif hari ini
        $totalHours = 0;
        foreach ($loginSessions as $session) {
            if (isset($session['start']) && isset($session['end'])) {
                try {
                    $start = Carbon::parse($session['start']);
                    $end = Carbon::parse($session['end']);
                    if ($end->greaterThan($start)) {
                        $hours = $start->diffInHours($end);
                        $totalHours += $hours;
                        Log::info("User ID: {$userId}, Session from {$start} to {$end}, Hours: {$hours}");
                    } else {
                        Log::warning("User ID: {$userId}, Invalid session time: End ({$end}) <= Start ({$start})");
                    }
                } catch (\Exception $e) {
                    Log::error("User ID: {$userId}, Error parsing session time: {$e->getMessage()}");
                }
            } else {
                Log::warning("User ID: {$userId}, Invalid session data: " . json_encode($session));
            }
        }

        Log::info("User ID: {$userId}, Total active hours: {$totalHours}");
        return round($totalHours, 1);
    }

    private function formatDeliveryTime($minutes)
    {
        if ($minutes <= 0) {
            return 'Belum ada data';
        } elseif ($minutes < 60) {
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
                Log::info("Kurir ID: {$kurir->id}, Today orders: {$todayOrders}");

                $lastLoginRaw = Cache::get("last_login_at_user_{$kurir->user_id}");
                Log::info("Kurir ID: {$kurir->id}, Last login: {$lastLoginRaw}");

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
                    'performance' => $performanceMetrics
                ];
            });

        return view('admin.panel', compact('kurirs'));
    }

    public function detailKurir($id)
    {
        // Ambil data kurir beserta relasi user-nya
        $kurir = Kurir::with('user')->findOrFail($id);

        // --- Eager Loading untuk efisiensi ---
        // Kita memuat relasi 'user' (untuk nama pelanggan) dan
        // 'items.produk' (untuk detail item dalam setiap order)
        $relationsToLoad = ['user', 'items.produk'];

        // Ambil semua order yang di-update hari ini untuk kurir ini
        $ordersTodayQuery = $kurir->orders()
            ->with($relationsToLoad)
            ->whereDate('updated_at', Carbon::today());

        // Kloning query untuk mendapatkan data tanpa dieksekusi
        $ordersToday = (clone $ordersTodayQuery)->latest('updated_at')->get();

        // Ambil semua order sepanjang waktu untuk kurir ini
        $ordersAll = $kurir->orders()
            ->with($relationsToLoad)
            ->latest('updated_at')
            ->get();

        // --- Kalkulasi Statistik ---
        // Total order yang ditugaskan hari ini (status apapun)
        $totalOrdersToday = $ordersToday->count();

        // Order yang selesai (terkirim) hari ini
        $completedOrdersToday = $ordersToday->where('status_pengiriman', 'terkirim')->count();

        // Ambil data login terakhir dari cache
        $lastLoginRaw = Cache::get("last_login_at_user_{$kurir->user_id}");
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
}
