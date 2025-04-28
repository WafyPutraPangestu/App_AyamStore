<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;

class MidtransPaymentTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Setup Order factory jika belum ada
   */
  protected function setUp(): void
  {
    parent::setUp();
    // Pastikan Order factory ada sebelum digunakan dalam test
    if (!class_exists(\Database\Factories\OrderFactory::class)) {
      $this->markTestSkipped('Order factory belum dibuat');
    }
  }

  /**
   * Test halaman pembayaran menampilkan tombol bayar ketika snap_token tersedia
   */
  public function test_payment_page_displays_pay_button_when_token_exists()
  {
    // Buat Order terlebih dahulu
    $order = Order::factory()->create();

    // Buat pembayaran dengan nilai yang sesuai struktur database
    $pembayaran = Pembayaran::create([
      'order_id' => $order->id,
      'status' => 'pending',
      'snap_token' => 'test-snap-token-123',
    ]);

    // Tambahkan penanganan kesalahan untuk debug
    $this->withoutExceptionHandling();

    // Kunjungi halaman pembayaran dengan ID pembayaran
    $response = $this->get(route('user.payment', $pembayaran->id));

    // Pastikan status respon OK
    $response->assertStatus(200);

    // Pastikan halaman berisi tombol bayar
    $response->assertSee('Bayar Sekarang');

    // Pastikan script snap.js dimuat
    $response->assertSee('https://app.sandbox.midtrans.com/snap/snap.js');

    // Pastikan snap token ada dalam response
    $response->assertSee($pembayaran->snap_token);
  }

  /**
   * Test halaman pembayaran menampilkan pesan error ketika snap_token tidak tersedia
   */
  public function test_payment_page_displays_error_when_token_not_exists()
  {
    // Buat Order terlebih dahulu
    $order = Order::factory()->create();

    // Buat pembayaran tanpa snap_token
    $pembayaran = Pembayaran::create([
      'order_id' => $order->id,
      'status' => 'pending',
      'snap_token' => null
    ]);

    // Kunjungi halaman pembayaran dengan ID pembayaran
    $response = $this->get(route('user.payment', $pembayaran->id));

    // Pastikan status respon OK
    $response->assertStatus(200);

    // Pastikan halaman berisi pesan error
    $response->assertSee('Terjadi kesalahan. Token pembayaran tidak tersedia.');

    // Pastikan tombol kembali ke katalog ada
    $response->assertSee('Kembali ke Katalog');
  }

  /**
   * Test pembuatan snap token dari Midtrans
   */
  public function test_create_snap_token()
  {
    // Mock service Midtrans
    $midtransMock = Mockery::mock('overload:App\Services\MidtransService');

    // Buat Order terlebih dahulu
    $order = Order::factory()->create(['id' => 12345]);

    // Siapkan ekspektasi bahwa metode createSnapToken akan dipanggil dengan parameter tertentu
    // dan akan mengembalikan token dummy
    $midtransMock->shouldReceive('createSnapToken')
      ->once()
      ->with(Mockery::any())
      ->andReturn('test-snap-token-xyz');

    // Siapkan data request
    $orderData = [
      'order_id' => $order->id
    ];

    // Panggil controller atau service yang menangani pembuatan token
    // Sesuaikan route dan parameter sesuai implementasi Anda
    $response = $this->post(route('user.create-payment'), $orderData);

    // Pastikan redirect ke halaman pembayaran
    $response->assertRedirect(route('user.payment', Mockery::any()));

    // Verifikasi bahwa token disimpan ke database
    $this->assertDatabaseHas('pembayaran', [
      'order_id' => $order->id,
      'snap_token' => 'test-snap-token-xyz'
    ]);
  }

  /**
   * Test callback handler untuk pembayaran sukses
   */
  public function test_payment_success_callback()
  {
    // Buat Order terlebih dahulu
    $order = Order::factory()->create();

    // Siapkan pembayaran
    $pembayaran = Pembayaran::create([
      'order_id' => $order->id,
      'status' => 'pending',
    ]);

    // Simulasikan callback dari Midtrans untuk pembayaran sukses
    $callbackData = [
      'transaction_status' => 'settlement',
      'order_id' => $order->id,
      'payment_type' => 'gopay',
      'transaction_id' => 'mid-trx-12345',
      'gross_amount' => '40000.00'
    ];

    // Panggil endpoint callback
    // Sesuaikan route dan parameter sesuai implementasi Anda
    $response = $this->post(route('payment.notification'), $callbackData);

    // Pastikan status respon OK
    $response->assertStatus(200);

    // Verifikasi bahwa status pembayaran diupdate di database
    $this->assertDatabaseHas('pembayaran', [
      'order_id' => $order->id,
      'status' => 'berhasil'
    ]);
  }

  /**
   * Test handler untuk kasus pembayaran gagal
   */
  public function test_payment_failure_callback()
  {
    // Buat Order terlebih dahulu
    $order = Order::factory()->create();

    // Siapkan pembayaran
    $pembayaran = Pembayaran::create([
      'order_id' => $order->id,
      'status' => 'pending',
    ]);

    // Simulasikan callback dari Midtrans untuk pembayaran gagal
    $callbackData = [
      'transaction_status' => 'deny',
      'order_id' => $order->id,
      'payment_type' => 'gopay',
      'transaction_id' => 'mid-trx-12345',
      'gross_amount' => '40000.00'
    ];

    // Panggil endpoint callback
    $response = $this->post(route('payment.notification'), $callbackData);

    // Pastikan status respon OK
    $response->assertStatus(200);

    // Verifikasi bahwa status pembayaran diupdate di database
    $this->assertDatabaseHas('pembayaran', [
      'order_id' => $order->id,
      'status' => 'gagal'
    ]);
  }

  /**
   * Test handler untuk kasus QR code gagal dimuat
   */
  public function test_qr_code_loading_failure_handling()
  {
    // Buat Order terlebih dahulu
    $order = Order::factory()->create();

    // Siapkan pembayaran dengan snap_token
    $pembayaran = Pembayaran::create([
      'order_id' => $order->id,
      'status' => 'pending',
      'snap_token' => 'test-snap-token-123',
    ]);

    // Kunjungi halaman pembayaran
    $response = $this->get(route('user.payment', $pembayaran->id));

    // Verifikasi bahwa halaman memiliki elemen untuk menangani kegagalan QR
    $response->assertSee('Failed to Load QR', false);

    // Verifikasi bahwa halaman memiliki tombol reload
    $response->assertSee('Reload');
  }
}
