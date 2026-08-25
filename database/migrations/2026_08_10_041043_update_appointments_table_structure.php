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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); // Foreign key to patients table
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table (doctors)
            $table->dateTime('scheduled_at'); // Scheduled appointment timestamp
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending'); // Appointment status enum
            $table->text('reason')->nullable(); // Reason for the appointment
            $table->timestamps();

            // Required indexes for performance optimization
            $table->index(['doctor_id', 'scheduled_at']);
            $table->index('patient_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};