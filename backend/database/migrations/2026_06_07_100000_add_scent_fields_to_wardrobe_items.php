<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wardrobe_items', function (Blueprint $table) {
            // Scent family drives the per-bottle colour world (see frontend scentWorld()).
            // floral|woody|aquatic|spicy|powdery|citrus|gourmand. Nullable so existing
            // rows keep working — the frontend falls back to the linked catalogue accords.
            $table->string('family')->nullable()->after('name');

            $table->string('tagline')->nullable()->after('family');
            $table->string('concentration')->nullable()->after('tagline');

            // Notes pyramid — arrays of note names for top / heart / base tiers.
            $table->json('notes_top')->nullable()->after('concentration');
            $table->json('notes_heart')->nullable()->after('notes_top');
            $table->json('notes_base')->nullable()->after('notes_heart');
        });
    }

    public function down(): void
    {
        Schema::table('wardrobe_items', function (Blueprint $table) {
            $table->dropColumn([
                'family',
                'tagline',
                'concentration',
                'notes_top',
                'notes_heart',
                'notes_base',
            ]);
        });
    }
};
