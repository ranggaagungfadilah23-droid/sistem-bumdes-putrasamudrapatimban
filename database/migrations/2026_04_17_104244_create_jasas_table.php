<?php
// ================================================================
// FILE 1: database/migrations/xxxx_create_jasas_table.php
// Jalankan: php artisan migrate
// ================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jasas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_jasa');
            $table->decimal('harga', 12, 2);
            $table->enum('satuan', ['Layanan', 'Jam', 'Hari'])->default('Layanan');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jasas');
    }
};
