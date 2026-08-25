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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
            ->constrained('invoices')
            ->onDelete('cascade');

            $table->decimal('amount', 12, 2);

            $table->enum('method', [
                'paypal',
                'visa',
            ]);

            $table->enum('status', [
                'pending',
                'completed',
                'failed',
                'cancelled',
            ])->default('pending');

            $table->string('provider')->nullable();

            $table->string('provider_order_id')->nullable();

            $table->string('provider_capture_id')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('invoice_id');
            $table->index('provider_order_id');
        });

        DB::statement('
            ALTER TABLE payments
            ADD CONSTRAINT payments_amount_positive
            CHECK (amount > 0)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};