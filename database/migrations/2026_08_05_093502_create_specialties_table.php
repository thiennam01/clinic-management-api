<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();            // Thêm mã chuyên khoa (CK-0001,...)
            $table->string('name')->unique();            // Tên chuyên khoa
            $table->text('description')->nullable();    // Mô tả
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};