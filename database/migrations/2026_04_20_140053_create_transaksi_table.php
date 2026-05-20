<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('mitra_id');
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->unsignedBigInteger('jasa_id')->nullable();
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('status_pembayaran')->default('pending');
            $table->string('status_pengiriman')->default('dikemas');
            $table->string('metode_pembayaran');
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('transaksi'); }
};
