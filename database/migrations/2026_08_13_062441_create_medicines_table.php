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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Medicine code
            $table->string('name'); // Medicine name
            $table->string('unit'); // Unit of measurement (e.g., tablet, box, strip)
            $table->decimal('price', 10, 2); // Price of the medicine
            $table->integer('stock')->default(0); // Available stock quantity
            $table->boolean('is_active')->default(true); // Status indicating if the medicine is active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};