<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores the season / time-of-day wear profile derived from each fragrance's
 * accords (see App\Support\AccordProfile).
 *
 * Previously computed on every request inside PerfumeTransformer — persisting
 * it lets the storefront *filter* by season and lets the transformer read
 * columns instead of recomputing. Populated by `php artisan discovery:import`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discovery_perfumes', function (Blueprint $table) {
            $table->string('suit_season')->nullable()->after('perfumers')->index();
            $table->string('suit_time')->nullable()->after('suit_season');

            // 0–100 wear-fit scores. Default 0 so rows are valid before the
            // first re-import backfills them.
            $table->unsignedTinyInteger('percent_summer')->default(0)->after('suit_time');
            $table->unsignedTinyInteger('percent_autumn')->default(0)->after('percent_summer');
            $table->unsignedTinyInteger('percent_winter')->default(0)->after('percent_autumn');
            $table->unsignedTinyInteger('percent_spring')->default(0)->after('percent_winter');
            $table->unsignedTinyInteger('percent_day')->default(0)->after('percent_spring');
            $table->unsignedTinyInteger('percent_night')->default(0)->after('percent_day');
        });
    }

    public function down(): void
    {
        Schema::table('discovery_perfumes', function (Blueprint $table) {
            $table->dropIndex(['suit_season']);
            $table->dropColumn([
                'suit_season', 'suit_time',
                'percent_summer', 'percent_autumn', 'percent_winter',
                'percent_spring', 'percent_day', 'percent_night',
            ]);
        });
    }
};
