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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->string('perfume_id');
            $table->string('perfume_name');
            $table->string('perfume_img'); // inc image
            $table->string('cust_id');
            $table->double('price_per_unit') ;// tarik from FK perfume table
            $table->integer('quantity');
            $table->double('total_price') ;

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
        Schema::dropIfExists('cart');
    }
};
