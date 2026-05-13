<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('cust_id');
            $table->string('cart_id');
            $table->string('perfume_id');
            $table->string('quantity');
            $table->string('total_price');
            $table->string('payment_mode');
            $table->string('payment_status');
            $table->string('payment_receipt');
            $table->string('tracking_no')->default(0);
            $table->string('order_status')->default(0); // 0 for pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
};
