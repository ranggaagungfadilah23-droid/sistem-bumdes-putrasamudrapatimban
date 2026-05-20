<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{

public function detail() {
    return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
}
    protected $table = 'detail_transaksi';

    protected $fillable = [
        'transaksi_id', 'produk_id', 'jasa_id', 'jumlah', 'harga', 'subtotal'
    ];

    // Relasi ke Transaksi
    public function transaksi() {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke Produk
    public function produk() {
        return $this->belongsTo(Produk::class);
    }

    // Relasi ke Jasa
    public function jasa() {
        return $this->belongsTo(Jasa::class);
    }
}
