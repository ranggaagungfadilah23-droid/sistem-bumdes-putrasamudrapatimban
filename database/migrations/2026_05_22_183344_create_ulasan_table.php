<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();

            // Relasi ke transaksi via invoice_number
            $table->string('invoice_number');
            $table->foreign('invoice_number')
                  ->references('invoice_number')
                  ->on('transaksi')
                  ->onDelete('cascade');

            // Relasi ke customer & mitra
            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('mitra_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Isi ulasan
            $table->tinyInteger('bintang')->unsigned()->comment('1-5');
            $table->text('pesan')->nullable();

            // Balasan dari mitra (opsional)
            $table->text('balasan_mitra')->nullable();
            $table->timestamp('dibalas_at')->nullable();

            $table->timestamps();

            // Satu invoice hanya boleh 1 ulasan
            $table->unique('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
