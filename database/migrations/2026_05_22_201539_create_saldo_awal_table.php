<?php
// ═══════════════════════════════════════════════════════════
// FILE 1 — Migration: create_saldo_awal_table
// Path: database/migrations/xxxx_xx_xx_create_saldo_awal_table.php
// ═══════════════════════════════════════════════════════════

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_awal', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan');   // 1–12
            $table->unsignedSmallInteger('tahun');  // cth: 2025
            $table->unsignedBigInteger('saldo_awal')->default(0); // dalam rupiah
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Satu data saldo awal per bulan+tahun
            $table->unique(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_awal');
    }
};
 