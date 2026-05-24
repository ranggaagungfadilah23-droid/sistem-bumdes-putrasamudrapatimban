<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    // ✅ Paksa nama tabel yang benar
    protected $table = 'transaksi';

    protected $fillable = [
        'invoice_number', 'customer_id', 'mitra_id', 'alamat',
        'produk_id', 'jasa_id', 'jumlah', 'harga', 'total',
        'status_pembayaran', 'status_pengiriman', 'metode_pembayaran',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'jasa_id');
    }

    public function bagiHasil()
    {
        return $this->hasOne(BagiHasil::class, 'transaksi_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

     public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'invoice_number', 'invoice_number');
    }

    // ===== HELPER =====

    /**
     * Cek apakah transaksi sudah bisa diulas
     * (status pengiriman Selesai atau Diterima)
     */
    public function bisaDiulas(): bool
    {
        return in_array($this->status_pengiriman, ['Selesai', 'Diterima']);
    }

    /**
     * Cek apakah transaksi sudah diulas
     */
    public function sudahDiulas(): bool
    {
        return $this->ulasan()->exists();
    }
}
