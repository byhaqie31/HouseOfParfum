<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfume', function (Blueprint $table) {
            if (! Schema::hasColumn('perfume', 'percent_spring')) {
                $table->integer('percent_spring')->default(0)->after('percent_autumn');
            }
        });
    }

    public function down(): void
    {
        Schema::table('perfume', function (Blueprint $table) {
            if (Schema::hasColumn('perfume', 'percent_spring')) {
                $table->dropColumn('percent_spring');
            }
        });
    }
};
