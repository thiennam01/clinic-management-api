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
        Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('examination_id')->unique()->constrained('examinations')->onDelete('cascade');
        $table->string('invoice_code')->unique();
        $table->decimal('subtotal', 12, 2);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('total', 12, 2);
        $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');
        $table->timestamp('issued_at')->nullable();
        $table->timestamps();

        // Index by status to optimize filtering performance
        $table->index('status');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
