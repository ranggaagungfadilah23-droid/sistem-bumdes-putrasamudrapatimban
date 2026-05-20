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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
        $table->decimal('total_amount', 15, 2);
        $table->string('payment_status')->default('pending'); // pending, dibayar, batal
        $table->timestamps();
    });
}
};
