<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Kita set default ke 'pesanan_baru' agar saat data masuk,
            // statusnya langsung terbaca sebagai pesanan baru
            $table->string('status_pesanan')->default('pesanan_baru')->after('status_pengiriman');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('status_pesanan');
        });
    }
};
