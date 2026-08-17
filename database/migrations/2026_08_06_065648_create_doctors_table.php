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
            
            // Khóa ngoại liên kết tới người dùng (User) và chuyên khoa (Specialty)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained('specialties')->cascadeOnDelete();

            $table->string('license_number')->unique();     // Số giấy phép hành nghề
            $table->integer('experience_years')->default(0); // Số năm kinh nghiệm
            $table->text('bio')->nullable();                // Giới thiệu bản thân / tiểu sử
            $table->decimal('consultation_fee', 12, 2)->default(0); // Phí khám (VNĐ)
            $table->boolean('is_active')->default(true);    // Trạng thái hoạt động

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};