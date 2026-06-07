<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // Per-wear detail captured in the log-wear flow. All nullable so existing
            // entries (logged before this redesign) keep displaying without backfill.
            $table->unsignedTinyInteger('sprays')->nullable()->after('worn_at');
            $table->string('mood')->nullable()->after('sprays');         // Confident|Calm|Playful|Romantic|Focused|Cosy
            $table->string('occasion')->nullable()->after('mood');       // Everyday|Work|Date|Evening out|Travel|At home
            $table->string('weather')->nullable()->after('occasion');    // Humid day|After rain|Cool evening|Hot noon
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropColumn(['sprays', 'mood', 'occasion', 'weather']);
        });
    }
};
