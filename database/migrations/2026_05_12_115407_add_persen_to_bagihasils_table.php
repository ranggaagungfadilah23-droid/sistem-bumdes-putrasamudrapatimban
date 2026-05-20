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
        $table->float('persen_bumdes')->default(10)->after('total_omzet');
        $table->float('persen_mitra')->default(90)->after('persen_bumdes');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bagihasils', function (Blueprint $table) {
            //
        });
    }
};
