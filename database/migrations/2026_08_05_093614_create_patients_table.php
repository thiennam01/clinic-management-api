<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã bệnh nhân tự sinh (BN-000001)
            $table->string('full_name');
            $table->string('gender'); // male, female, other
            $table->date('date_of_birth');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index phục vụ tìm kiếm nhanh bệnh nhân
            $table->index('full_name');
            $table->index('phone');
        });

        // Bổ sung CHECK Constraint cho cột gender chuẩn PostgreSQL
        DB::statement("ALTER TABLE patients ADD CONSTRAINT check_patient_gender CHECK (gender IN ('male', 'female', 'other'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};