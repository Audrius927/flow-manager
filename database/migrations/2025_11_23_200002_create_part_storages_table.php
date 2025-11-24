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
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
            $table->foreignId('car_model_id')->nullable()->constrained('car_models')->nullOnDelete()->comment('Suderinamas su automobilių modeliu');
            $table->foreignId('engine_id')->nullable()->constrained('engines')->nullOnDelete()->comment('Suderinamas su varikliu');
            $table->foreignId('fuel_type_id')->nullable()->constrained('fuel_types')->nullOnDelete()->comment('Suderinamas su kuro tipu');
            $table->foreignId('body_type_id')->nullable()->constrained('body_types')->nullOnDelete()->comment('Suderinamas su kėbulo tipu');
            
            $table->string('storage_location', 100)->nullable()->comment('Vieta sandėlyje');
            $table->integer('quantity')->default(1)->comment('Kiekis');
            $table->enum('condition', ['new', 'used', 'damaged', 'repaired'])->default('new')->comment('Būklė: nauja, naudota, sugedusi, suremontuota');
            $table->dateTime('received_at')->nullable()->comment('Kada gauta į sandėlį');
            $table->text('notes')->nullable()->comment('Pastabos');
            $table->timestamps();
            
            // Indexes
            $table->index('part_id');
            $table->index('car_model_id');
            $table->index('storage_location');
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

