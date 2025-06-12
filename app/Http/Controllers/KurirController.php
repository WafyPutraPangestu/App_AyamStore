<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KurirController extends Controller
{
    public function profileKurir()
    {
        $user  = Auth::user();
        $kurir = $user->kurir
            ?? Kurir::create([
                'user_id'        => $user->id,
                'kendaraan_info' => null,
                'status'         => 'tersedia',
            ]);

        return view('kurir.profile', compact('kurir'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'telepon'        => 'nullable|string|max:20',
            'kendaraan_info' => 'nullable|string|max:255',
            'status'         => 'required|in:tersedia,sedang_mengantar,tidak_aktif',
        ]);

        $user  = Auth::user();
        $kurir = $user->kurir;

        if (! $kurir) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Profil kurir tidak ditemukan'], 404)
                : redirect()->route('kurir.profile')->with('error', 'Profil kurir tidak ditemukan.');
        }

        $user->update(['telepon' => $data['telepon']]);
        $kurir->update([
            'kendaraan_info' => $data['kendaraan_info'],
            'status'         => $data['status'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'status'  => $kurir->status,
            ]);
        }

        return redirect()->route('kurir.profile')->with('success', 'Profil kurir berhasil diperbarui.');
    }

    public function tugasView()
    {
        $user = Auth::user();

        if ($user->role !== 'kurir') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
        }

        $availableOrders = Order::with('items.produk')
            ->where('status', 'selesai')
            ->whereNull('kurir_id')
            ->where('status_pengiriman', 'mencari_kurir')
            ->orderBy('created_at')
            ->simplePaginate(10);

        return view('kurir.tugas', compact('availableOrders'));
    }

    public function ambilTugas(Request $request)
    {
        $request->validate([
            'selected_orders'   => 'required|array',
            'selected_orders.*' => 'exists:orders,id',
        ]);

        $kurir = Auth::user()->kurir;
        if (! $kurir) {
            return redirect()->back()->with('error', 'Profil kurir tidak ditemukan');
        }

        $assignedCount = 0;
        $alreadyTakenCount = 0;

        DB::beginTransaction();
        try {
            foreach ($request->input('selected_orders') as $id) {
                $order = Order::lockForUpdate()->find($id);

                if ($order && is_null($order->kurir_id) && $order->status_pengiriman === 'mencari_kurir') {
                    $order->update([
                        'kurir_id'          => $kurir->id,
                        'status_pengiriman' => 'menunggu_pickup',
                    ]);
                    $assignedCount++;
                } elseif ($order && $order->kurir_id) {
                    $alreadyTakenCount++;
                }
            }

            if ($assignedCount > 0) {
                $kurir->update(['status' => 'sedang_mengantar']);
            }



            DB::commit();

            $msg = "$assignedCount pesanan berhasil diambil.";
            if ($alreadyTakenCount) {
                $msg .= " $alreadyTakenCount sudah diambil.";
            }

            return redirect()->route('kurir.tugas')->with('success', $msg);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function manajemenTugasView()
    {
        $user = Auth::user();
        $orders = Order::where('kurir_id', $user->kurir->id)
            ->whereIn('status_pengiriman', ['menunggu_pickup', 'sedang_diantar'])
            ->orderBy('created_at', 'desc')
            ->latest()->simplePaginate(10);

        return view('kurir.manajemen', compact('orders'));
    }

    public function updateTugas(Request $request, Order $order)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:menunggu_pickup,sedang_diantar,terkirim,gagal_kirim',
            'bukti_pengiriman' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $newStatus = $request->input('status_pengiriman');
        $updateData = ['status_pengiriman' => $newStatus];

        // Logika untuk mengisi timestamp berdasarkan perubahan status
        switch ($newStatus) {
            case 'sedang_diantar':
                // Isi waktu mulai HANYA JIKA belum pernah diisi sebelumnya
                if (!$order->waktu_mulai_antar) {
                    $updateData['waktu_mulai_antar'] = Carbon::now();
                }
                break;

            case 'terkirim':
                // Validasi bukti pengiriman wajib ada
                if (!$request->hasFile('bukti_pengiriman')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gambar bukti pengiriman wajib diupload saat status terkirim.'
                    ], 422);
                }

                // Upload gambar baru dan tambahkan ke data update
                $path = $request->file('bukti_pengiriman')->store('bukti_pengiriman', 'public');
                $updateData['bukti_pengiriman'] = $path;

                // Isi waktu selesai HANYA JIKA belum pernah diisi
                if (!$order->waktu_selesai_antar) {
                    $updateData['waktu_selesai_antar'] = Carbon::now();
                }
                // Update juga status utama order menjadi 'selesai'
                $updateData['status'] = 'selesai';
                break;

            case 'gagal_kirim':
                // Isi waktu selesai HANYA JIKA belum pernah diisi
                if (!$order->waktu_selesai_antar) {
                    $updateData['waktu_selesai_antar'] = Carbon::now();
                }
                // Update juga status utama order menjadi 'gagal'
                $updateData['status'] = 'gagal';
                break;
        }

        // Lakukan satu kali update ke database
        $order->update($updateData);

        // Cek apakah status baru adalah 'terkirim' atau 'gagal_kirim'
        // untuk mengubah status kurir
        if (in_array($newStatus, ['terkirim', 'gagal_kirim'])) {
            if ($order->kurir) {
                $activeOrders = Order::where('kurir_id', $order->kurir_id)
                    ->whereNotIn('status_pengiriman', ['terkirim', 'gagal_kirim'])
                    ->count();

                if ($activeOrders === 0) {
                    $order->kurir->update(['status' => 'tersedia']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pengiriman berhasil diupdate.',
            // Reload order data untuk mendapatkan timestamp terbaru di response
            'data' => $order->fresh()
        ]);
    }

    public function riwayatKurir()
    {
        $user = Auth::user();
        $orders = Order::where('kurir_id', $user->kurir->id)
            ->whereIn('status_pengiriman', ['terkirim', 'gagal_kirim'])
            ->orderBy('created_at', 'desc')
            ->latest()->simplePaginate(10);

        return view('kurir.riwayat', compact('orders'));
    }
}
