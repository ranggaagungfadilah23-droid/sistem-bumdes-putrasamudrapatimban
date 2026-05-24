<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_periode', ['mingguan', 'bulanan']);
            $table->unsignedTinyInteger('minggu_ke')->nullable();  // 1–5, null jika bulanan
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->string('kategori');               // Operasional, Gaji, Pembelian, dsb
            $table->string('keterangan');
            $table->json('detail_item')->nullable();  // array [{nama, jumlah}]
            $table->unsignedBigInteger('total_pengeluaran')->default(0);
            $table->date('tanggal');                  // tanggal pencatatan
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['bulan', 'tahun']);
            $table->index('tipe_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_pengeluaran');
    }
};
