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
    Schema::table('users', function (Blueprint $table) {
        $table->string('nama_usaha')->nullable()->after('password');
        $table->string('jenis_usaha')->nullable()->after('nama_usaha');
        $table->string('alamat_usaha')->nullable()->after('jenis_usaha');
        $table->string('rt_rw')->nullable()->after('alamat_usaha');
        $table->string('dusun')->nullable()->after('rt_rw');
        $table->string('no_hp')->nullable()->after('dusun');
        $table->string('dokumen')->nullable()->after('no_hp'); // Untuk KTP
        $table->string('sku')->nullable()->after('dokumen');    // Untuk SKU
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['nama_usaha', 'jenis_usaha', 'alamat_usaha', 'rt_rw', 'dusun', 'no_hp', 'dokumen', 'sku']);
    });
}
};
