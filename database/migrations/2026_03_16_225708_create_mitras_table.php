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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id(); // ID (1, 2, 3...)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->string('nama_usaha');
            $table->string('nama_pemilik');
            $table->string('no_hp');
            $table->string('nik'); // Sesuai dengan file SQL
            $table->string('jenis_usaha');
            $table->text('alamat_usaha');
            $table->string('sku'); // Sesuai dengan file SQL
            $table->string('dusun');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
