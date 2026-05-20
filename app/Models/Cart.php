<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'produk_id', 'jasa_id', 'jumlah'];

 public function produk() {
    return $this->belongsTo(Produk::class, 'produk_id', 'id');
}
public function jasa() {
    return $this->belongsTo(Jasa::class, 'jasa_id', 'id');
}
}
