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
    Schema::table('bagihasils', function (Blueprint $table) {
        $table->unsignedBigInteger('mitra_id')->after('id');
        $table->bigInteger('total_omzet')->default(0);
        $table->bigInteger('nominal_bumdes')->default(0);
        $table->bigInteger('nominal_mitra')->default(0);
        $table->string('status')->default('PENDING');
        $table->timestamp('tanggal')->nullable();
    });
}

public function down()
{
    Schema::table('bagihasils', function (Blueprint $table) {
        $table->dropColumn(['mitra_id', 'total_omzet', 'nominal_bumdes', 'nominal_mitra', 'status', 'tanggal']);
    });
}
};
