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
        Schema::create('perfume', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('name');
            $table->string('image');
            $table->double('price');
            $table->double('rrp')->nullable();
            $table->double('rrp_rm')->nullabe();
            $table->integer('size');
            $table->string('quality');
            $table->string('year');
            $table->string('suit');
            $table->longText('main_accord')->nullable();
            $table->longText('top_notes')->nullable();
            $table->longText('middle_notes')->nullable();
            $table->longText('base_notes')->nullable();
            $table->longText('history')->nullable();
            $table->string('suit_season');
            $table->string('suit_time');
            $table->string('ref_shop')->nullabe();

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
        Schema::dropIfExists('perfume');
    }
};
