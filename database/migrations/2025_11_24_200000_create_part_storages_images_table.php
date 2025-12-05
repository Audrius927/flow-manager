<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('part_storages_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_storage_id')
                ->constrained('part_storages')
                ->cascadeOnDelete();
            $table->string('disk')->default('private');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->integer('sort_order')->default(0)->comment('Rūšiavimo tvarka');
            $table->timestamps();

            $table->index('part_storage_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_storages_images');
    }
};
