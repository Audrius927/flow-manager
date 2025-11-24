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
        Schema::create('role_resource_field_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->string('resource', 100); // pvz., 'document_flows'
            $table->string('field_name', 100)->nullable(); // jei null → page access
            $table->boolean('can_access')->default(false); // page access (jei field_name = null)
            $table->boolean('can_view')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('required')->default(false); // required field
            $table->timestamps();
            $table->unique(['role_id', 'resource', 'field_name'], 'rrfp_role_res_field_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_resource_field_permissions');
    }
};
