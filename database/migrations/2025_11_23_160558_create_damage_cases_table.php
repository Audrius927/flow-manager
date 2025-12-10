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
        Schema::create('damage_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_company_id')->nullable()->constrained('insurance_companies')->nullOnDelete()->comment('Draudimo kompanija');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete()->comment('Produktas');
            $table->foreignId('product_sub_id')->nullable()->constrained('products')->nullOnDelete()->comment('Subproduktas (pvz. KASKO, MTPL)');
            $table->string('damage_number', 255)->nullable();
            $table->foreignId('car_mark_id')->nullable()->constrained('car_marks')->nullOnDelete()->comment('Automobilio markė');
            $table->foreignId('car_model_id')->nullable()->constrained('car_models')->nullOnDelete()->comment('Automobilio modelis');
            $table->string('license_plate', 20)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('order_date')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete()->comment('Miestas');
            $table->string('received_location', 255)->nullable();
            $table->string('storage_location', 255)->nullable();
            $table->date('removed_from_storage_at')->nullable();
            $table->date('returned_to_storage_at')->nullable();
            $table->date('returned_to_client_at')->nullable();
            $table->foreignId('repair_company_id')->nullable()->constrained('repair_companies')->nullOnDelete()->comment('Remonto įmonė');
            $table->date('planned_repair_start')->nullable();
            $table->date('planned_repair_end')->nullable();
            $table->date('finished_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('car_mark_id');
            $table->index('car_model_id');
            $table->index('repair_company_id');
            $table->index('insurance_company_id');
            $table->index('product_id');
            $table->index('product_sub_id');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_cases');
    }
};
