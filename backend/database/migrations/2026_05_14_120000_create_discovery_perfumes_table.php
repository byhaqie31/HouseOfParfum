<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Discovery catalogue — a large, browsable set of real-world fragrances
 * sourced from the Fragrantica `fra_cleaned.csv` export.
 *
 * Deliberately separate from the `perfume` table: that one is the curated,
 * priced storefront; this one is reference data for the discovery section
 * and carries no commerce fields and no FK back to `perfume` / `brand`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discovery_perfumes', function (Blueprint $table) {
            $table->id();

            // Stable Fragrantica perfume id parsed from the source URL (e.g. "74630").
            // Unique so `discovery:import` can upsert on re-runs instead of duplicating.
            $table->string('source_id')->unique();
            $table->string('source_url');

            $table->string('name');
            $table->string('brand')->index();
            $table->string('country')->nullable();
            $table->string('gender')->nullable()->index();        // unisex | women | men

            $table->decimal('rating', 3, 2)->nullable();           // Fragrantica 1.00–5.00 scale
            $table->unsignedInteger('votes')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();

            // Note pyramid + accords + perfumers, each a JSON array of strings.
            $table->json('notes_top')->nullable();
            $table->json('notes_middle')->nullable();
            $table->json('notes_base')->nullable();
            $table->json('accords')->nullable();                   // coalesced mainaccord1..5
            $table->json('perfumers')->nullable();                 // Perfumer1/2, "unknown" dropped

            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discovery_perfumes');
    }
};
