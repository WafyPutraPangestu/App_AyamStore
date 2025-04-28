<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidasiPembayaran
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1) Ambil instance Pembayaran hasil route-model binding
        $pembayaran = $request->route('pembayaran');

        // 2) Validasi keberadaan
        if (! $pembayaran) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        // 3) Validasi kepemilikan
        if ($pembayaran->order->user_id !== Auth::id()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pembayaran ini');
        }

        // 4) Block kalau status sudah 'berhasil' (atau 'selesai', sesuaikan enum kamu)
        if ($pembayaran->status === 'berhasil') {
            return redirect()->route('user.riwayat')
                ->with('error', 'Pembayaran sudah selesai dan tidak dapat diakses kembali.');
        }

        return $next($request);
    }
}
