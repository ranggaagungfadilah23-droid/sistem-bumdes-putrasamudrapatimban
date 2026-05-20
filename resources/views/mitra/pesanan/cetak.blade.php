<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $t->invoice_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #333; line-height: 1.5; }
        .container { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        h1 { color: #ee4d2d; /* Warna khas Shopee */ }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ee4d2d; padding-bottom: 10px; margin-bottom: 20px; }
        .info-box { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8f8f8; border-bottom: 2px solid #eee; padding: 12px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 12px; }
        .total { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; }
        .total span { color: #ee4d2d; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h1>Nota Pesanan</h1>
            <p><strong>#{{ $t->invoice_number }}</strong></p>
        </div>

        <div class="info-box">
            <div>
                <p><strong>Pembeli:</strong><br>{{ $t->customer->name ?? '-' }}</p>
            </div>
            <div>
                <p><strong>Alamat Pengiriman:</strong><br>{{ $t->alamat ?? '-' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $t->produk->nama_produk ?? $t->jasa->nama_jasa ?? '-' }}</td>
                    <td>Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                    <td>{{ $t->jumlah }}</td>
                    <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            <p>Total Pembayaran: <span>Rp {{ number_format($t->total, 0, ',', '.') }}</span></p>
        </div>
    </div>
</body>
</html>
