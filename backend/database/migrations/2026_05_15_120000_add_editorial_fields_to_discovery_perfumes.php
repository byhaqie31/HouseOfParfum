<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Editorial enrichment fields — filled in manually by an admin after import.
 * The importer never touches these so re-importing is safe (no data loss).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discovery_perfumes', function (Blueprint $table) {
            $table->string('image')->nullable()->after('source_url');
            $table->text('history')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('discovery_perfumes', function (Blueprint $table) {
            $table->dropColumn(['image', 'history']);
        });
    }
};
