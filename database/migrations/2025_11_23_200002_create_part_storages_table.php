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
        Schema::create('part_storages', function (Blueprint $table) {
            $table->id();
            $table->string('part_number', 100)->nullable()->comment('Detalės numeris / artikulas');
            $table->foreignId('part_category_id')
                ->constrained('part_categories')
                ->cascadeOnDelete()
                ->comment('Detalės kategorija');
            $table->foreignId('car_model_id')
                ->nullable()
                ->constrained('car_models')
                ->nullOnDelete()
                ->comment('Suderinamas su automobilių modeliu');
            $table->foreignId('engine_id')
                ->nullable()
                ->constrained('engines')
                ->nullOnDelete()
                ->comment('Suderinamas su varikliu');
            $table->foreignId('fuel_type_id')
                ->nullable()
                ->constrained('fuel_types')
                ->nullOnDelete()
                ->comment('Suderinamas su kuro tipu');
            $table->foreignId('body_type_id')
                ->nullable()
                ->constrained('body_types')
                ->nullOnDelete()
                ->comment('Suderinamas su kėbulo tipu');
            $table->integer('year')->nullable()->comment('Metai');
            $table->integer('quantity')->default(1)->comment('Kiekis sandėlyje');
            $table->string('vin_code', 50)->nullable();
            $table->text('notes')->nullable()->comment('Pastabos');
            $table->timestamps();

            $table->index('part_number');
            $table->index('part_category_id');
            $table->index('car_model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_storages');
    }
};

