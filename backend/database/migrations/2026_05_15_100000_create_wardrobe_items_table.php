<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wardrobe_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Soft reference — no FK constraint so the discovery catalogue can be
            // re-imported (truncated + re-seeded) without breaking wardrobe rows.
            $table->unsignedBigInteger('catalog_id')->nullable();

            $table->string('brand');
            $table->string('name');
            $table->unsignedSmallInteger('size');       // ml
            $table->string('acquired')->nullable();     // free text, e.g. "March 2025"
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wardrobe_items');
    }
};
