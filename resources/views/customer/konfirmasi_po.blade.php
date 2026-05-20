<h3>Konfirmasi Pre-Order</h3>
<p>Silakan cek detail pesanan Anda:</p>
<ul>
    <li>Produk: {{ $transaksi->detail->nama_produk }}</li>
    <li>Total: Rp {{ number_format($transaksi->total) }}</li>
</ul>

<form action="{{ route('customer.po.konfirmasi') }}" method="POST">
    @csrf
    <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
    <button type="submit" class="btn btn-success">Konfirmasi Pesanan</button>
</form>
