<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade'); // Mengarah ke tabel mitras

        // Karena Mitra bisa Jasa atau Produk, kita buat nullable keduanya
        $table->foreignId('produk_id')->nullable()->constrained('produks')->onDelete('set null');
        $table->foreignId('jasa_id')->nullable()->constrained('jasas')->onDelete('set null');

        $table->integer('quantity');
        $table->decimal('price', 15, 2); // Harga satuan saat dibeli
        $table->decimal('subtotal', 15, 2); // quantity * price

        // Status khusus untuk Mitra memproses pesanannya
        $table->string('status')->default('menunggu'); // menunggu, diproses, dikirim, selesai, dibatalkan

        $table->timestamps();
    });
}
};
