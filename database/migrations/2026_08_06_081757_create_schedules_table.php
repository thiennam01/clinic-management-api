<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_patients')->default(10);
            $table->integer('current_patients')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Prevent a doctor from creating overlapping time slots on the same day
            $table->unique(['doctor_id', 'date', 'start_time', 'end_time']);
            $table->index(['doctor_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};