<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Scent family on the discovery registry — the keystone curation field.
 *
 * floral|woody|aquatic|spicy|powdery|citrus|gourmand. This is what drives the
 * per-fragrance colour world in the app (frontend scentWorld()): a registry row
 * with no family generates no colour, so the admin's curation screen exists to
 * fill this in. Nullable + indexed (the dashboard groups the catalogue by it).
 * The importer never touches it, so re-importing the Fragrantica export is safe.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discovery_perfumes', function (Blueprint $table) {
            $table->string('family')->nullable()->after('history')->index();
        });
    }

    public function down(): void
    {
        Schema::table('discovery_perfumes', function (Blueprint $table) {
            $table->dropColumn('family');
        });
    }
};
