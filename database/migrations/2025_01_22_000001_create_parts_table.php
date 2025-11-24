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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number', 255)->unique()->comment('Detalės numeris');
            $table->string('title', 255)->comment('Detalės pavadinimas');
            $table->foreignId('part_category_id')->nullable()->constrained('part_categories')->nullOnDelete();
            $table->text('description')->nullable()->comment('Aprašymas');
            $table->decimal('price', 10, 2)->nullable()->comment('Kaina');
            $table->string('manufacturer', 255)->nullable()->comment('Gamintojas');
            $table->timestamps();
            
            // Indexes
            $table->index('part_number');
            $table->index('part_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};

