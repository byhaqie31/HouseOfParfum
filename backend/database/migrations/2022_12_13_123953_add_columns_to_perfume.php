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
        Schema::table('perfume', function (Blueprint $table) {
            $table->integer('percent_summer')->default(0);
            $table->integer('percent_autumn')->default(0);
            $table->integer('percent_winter')->default(0);
            $table->integer('percent_day')->default(0);
            $table->integer('percent_night')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfume', function (Blueprint $table) {
            $table->dropColumn('percent_summer')->default(0);
            $table->dropColumn('percent_autumn')->default(0);
            $table->dropColumn('percent_winter')->default(0);
            $table->dropColumn('percent_day')->default(0);
            $table->dropColumn('percent_night')->default(0);
        });
    }
};
