<?php

namespace App\Http\Controllers;

use App\Models\kurir;
use Carbon\Carbon;
use Illuminate\Http\Request;

class panelController extends Controller
{
    public function panelView()
    {
        $kurirs = kurir::with(['user', 'orders' => function ($query) {
            $query->whereDate('created_at', Carbon::today());
        }])
            ->get()
            ->map(function ($kurir) {
                $totalOrders = $kurir->orders->count();

                $completedOrders = $kurir->orders
                    ->where('status_pengiriman', 'terkirim')
                    ->count();

                return [
                    'nama' => $kurir->user->name,
                    'status_kurir' => $kurir->status,
                    'total_order' => $totalOrders,
                    'order_selesai' => $completedOrders,
                    'login_terakhir' => $kurir->user->updated_at,
                ];
            });

        return view('admin.panel', compact('kurirs'));
    }
}
