<?php

namespace Database\Seeders;

use App\Models\Examination;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $examinations = Examination::orderBy('id')->take(2)->get();

        if ($examinations->isEmpty()) {
            return;
        }

        foreach ($examinations as $index => $examination) {
            $total = $index === 0 ? 100000 : 200000;

            Invoice::firstOrCreate(
                ['examination_id' => $examination->id],
                [
                    'invoice_code' => 'INV-' . str_pad(
                        (string) ($index + 1),
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'subtotal' => $total,
                    'discount' => 0,
                    'total' => $total,
                    'status' => $index === 0 ? 'unpaid' : 'paid',
                    'issued_at' => now(),
                ]
            );
        }
    }
}