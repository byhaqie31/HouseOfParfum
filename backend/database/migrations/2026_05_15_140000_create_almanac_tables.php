<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almanac_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('number');
            $table->string('title');
            $table->text('intro');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('almanac_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('almanac_chapters')->cascadeOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almanac_entries');
        Schema::dropIfExists('almanac_chapters');
    }
};
