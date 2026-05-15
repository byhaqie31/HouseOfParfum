<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Soft reference — uuid string, not a FK, so deleting a wardrobe item
            // does not cascade-delete the wear history.
            $table->string('wardrobe_item_id', 36)->nullable();

            $table->string('brand');
            $table->string('name');
            $table->timestamp('worn_at');

            // Diary fields — all optional, filled in progressively during the day.
            $table->text('experience')->nullable();
            $table->text('compliments')->nullable();
            $table->string('longevity')->nullable();    // brief|settled|lasting|all-day|into-night
            $table->text('notes')->nullable();          // legacy single-field note

            $table->timestamps();

            $table->index('user_id');
            $table->index('worn_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
