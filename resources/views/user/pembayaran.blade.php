<x-layout>
  <h1>test</h1>
   @if(isset($pembayaran) && $pembayaran->snap_token)
  <div class="mt-4">
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
  </div>

  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

  <script type="text/javascript">
    document.getElementById('pay-button').onclick = function(event) {
      event.preventDefault();

      snap.pay("{{ $pembayaran->snap_token }}", {
        onSuccess: function(result) {
          document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
          // Redirect atau update status pembayaran disini
          window.location.href = "{{ route('user.order-success', $pembayaran) }}";
        },
        onPending: function(result) {
          document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
        },
        onError: function(result) {
          document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
        }
      });
    };
  </script>

  <pre id="result-json" class="mt-3"></pre>
  @else
  <div class="alert alert-danger">
    Terjadi kesalahan. Token pembayaran tidak tersedia.
  </div>
  @endif
</x-layout>