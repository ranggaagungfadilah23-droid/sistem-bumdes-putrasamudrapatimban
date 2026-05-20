<?php
// ================================================================
// FILE 1: database/migrations/xxxx_create_produks_table.php
// Jalankan: php artisan migrate
// ================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_produk');
            $table->decimal('harga', 12, 2);
            $table->integer('jumlah')->default(0);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
