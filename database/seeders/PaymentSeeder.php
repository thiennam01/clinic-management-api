<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $invoice = Invoice::where('status', 'paid')
            ->orderBy('id')
            ->first();

        if (!$invoice) {
            return;
        }

        Payment::firstOrCreate(
            [
                'invoice_id' => $invoice->id,
                'status' => 'completed',
            ],
            [
                'amount' => $invoice->total,
                'method' => 'paypal',
                'provider' => 'paypal',
                'provider_order_id' => 'SEED-ORDER-' . $invoice->id,
                'provider_capture_id' => 'SEED-CAPTURE-' . $invoice->id,
                'paid_at' => now(),
                'note' => 'Seed payment',
            ]
        );
    }
}