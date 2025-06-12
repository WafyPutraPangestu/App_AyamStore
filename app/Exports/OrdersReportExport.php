<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class OrdersReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    public function query()
    {
        return OrderItem::with([
            'order.user',
            'order.kurir.user',
            'order.pembayaran',
            'produk'
        ])->orderBy('orders_id', 'asc')->orderBy('id', 'asc');
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Tanggal Pesanan',
            'Nama Pelanggan',
            'Telepon Pelanggan',
            'Email Pelanggan',
            'Alamat Pengiriman',
            'Nama Produk',
            'Kuantitas',
            'Harga Satuan Produk (Rp)',
            'Subtotal Produk (Rp)',
            'Ongkir (Rp)',
            'Total Harga Pesanan (Rp)',
            'Total Keseluruhan (Rp)',
            'Status Pesanan',
            'Status Pengiriman',
            'Nama Kurir',
            'Telepon Kurir',
            'Status Pembayaran',
            'Tanggal Dibuat Item',
        ];
    }

    public function map($orderItem): array
    {
        $order = $orderItem->order;
        $user = optional($order)->user;
        $produk = $orderItem->produk;

        $kurirModel = optional($order)->kurir;
        $kurirUser = optional($kurirModel)->user;

        $pembayaran = optional(optional($order)->pembayaran)->first();

        $hargaSatuan = $produk ? (float)$produk->harga : 0;
        $kuantitas = (float)$orderItem->quantity;
        $subtotalProduk = $hargaSatuan * $kuantitas;

        // SOLUSI: Format nomor HP sebagai string dengan spasi atau strip
        $teleponPelanggan = $user->telepon ?? 'N/A';
        $teleponKurir = $kurirUser->telepon ?? 'N/A';

        // Format nomor HP dengan menambahkan spasi untuk mencegah scientific notation
        if ($teleponPelanggan !== 'N/A' && is_numeric($teleponPelanggan)) {
            // Contoh: 081234567890 menjadi "0812-3456-7890" atau " 081234567890"
            $teleponPelanggan = ' ' . $teleponPelanggan; // Tambah spasi di depan
        }

        if ($teleponKurir !== 'N/A' && is_numeric($teleponKurir)) {
            $teleponKurir = ' ' . $teleponKurir; // Tambah spasi di depan
        }

        return [
            $order->id ?? 'N/A',
            $order ? $order->created_at->format('d-m-Y H:i:s') : 'N/A',
            $user->name ?? 'N/A',
            $teleponPelanggan, // Telepon Pelanggan dengan prefix
            $user->email ?? 'N/A',
            $order->alamat_pengiriman ?? 'N/A',
            $produk->nama_produk ?? 'N/A',
            $kuantitas,
            $hargaSatuan,
            $subtotalProduk,
            $order ? (float)$order->ongkir : 0,
            $order ? (float)$order->total_harga : 0,
            $order ? (float)$order->total : 0,
            $order->status ?? 'N/A',
            $order->status_pengiriman ?? 'N/A',
            $kurirUser->name ?? 'N/A',
            $teleponKurir, // Telepon Kurir dengan prefix
            $pembayaran->status ?? 'N/A',
            $orderItem->created_at->format('d-m-Y H:i:s'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT, // Telepon Pelanggan
            'Q' => NumberFormat::FORMAT_TEXT, // Telepon Kurir  
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
