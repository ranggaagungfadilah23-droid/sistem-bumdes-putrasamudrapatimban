<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_kas', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users (admin yang mengirim)
            $table->foreignId('dikirim_oleh')->constrained('users')->onDelete('cascade');

            $table->string('bulan_aktif');

            // Menggunakan bigInteger untuk nominal Rupiah agar lebih aman
            $table->bigInteger('total_kas_masuk');
            $table->bigInteger('total_omzet');

            $table->integer('total_mitra');
            $table->text('catatan')->nullable();

            $table->string('status')->default('terkirim');
            $table->timestamp('dikirim_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kas');
    }
};
