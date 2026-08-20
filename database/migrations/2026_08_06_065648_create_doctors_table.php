<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys linking to User and Specialty
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained('specialties')->cascadeOnDelete();

            $table->string('license_number')->unique();     // License number
            $table->integer('experience_years')->default(0); // Years of experience
            $table->text('bio')->nullable();                // Bio
            $table->decimal('consultation_fee', 12, 2)->default(0); // Consultation fee (VNĐ)
            $table->boolean('is_active')->default(true);    // Active status

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};