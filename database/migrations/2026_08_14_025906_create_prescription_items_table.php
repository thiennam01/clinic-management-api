<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->integer('quantity'); 
            $table->string('dosage')->nullable();
            $table->text('usage_instruction')->nullable();
            $table->timestamps();

            // Composite UNIQUE constraint between prescription_id and medicine_id
            $table->unique(['prescription_id', 'medicine_id']);
        });

        // Add a CHECK constraint > 0 for quantity
        DB::statement('ALTER TABLE prescription_items ADD CONSTRAINT check_quantity_positive CHECK (quantity > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};