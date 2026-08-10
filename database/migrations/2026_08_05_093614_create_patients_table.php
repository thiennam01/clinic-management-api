<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            // Automatically generated patient code (e.g., BN-000001)
            $table->string('code')->unique(); 
            $table->string('full_name');
            // Gender options: male, female, other
            $table->string('gender'); 
            $table->date('date_of_birth');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for fast patient searching
            $table->index('full_name');
            $table->index('phone');
        });

        // Add CHECK Constraint for gender to ensure data integrity in PostgreSQL
        DB::statement("ALTER TABLE patients ADD CONSTRAINT check_patient_gender CHECK (gender IN ('male', 'female', 'other'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};