<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidasiOrder
{
    public function handle(Request $request, Closure $next): Response
    {
        $orderId = $request->route('id');
        $order   = Order::find($orderId);

        // 1) Order must exist
        if (! $order) {
            return redirect()->route('user.katalog')
                ->with('error', 'Pesanan tidak ditemukan');
        }

        // 2) Only owner can access
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('user.katalog')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        // 3) Block only when status = 'selesai'
        if ($order->status === 'selesai') {
            return redirect()->route('user.katalog')
                ->with('error', 'Pesanan sudah selesai dan tidak dapat diakses');
        }

        // All other statuses (pending) are allowed
        return $next($request);
    }
}
