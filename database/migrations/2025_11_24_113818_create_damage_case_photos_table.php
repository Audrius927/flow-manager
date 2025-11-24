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
        Schema::create('damage_case_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('damage_case_id')
                ->constrained('damage_cases')
                ->cascadeOnDelete();
            $table->string('disk')->default('private');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->timestamps();

            $table->index('damage_case_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_case_photos');
    }
};
