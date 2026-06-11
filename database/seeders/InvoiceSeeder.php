<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Resident;
use App\Models\Setting;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $resident = Resident::first();
        if (!$resident) return;

        // Clear existing invoices to reset state as requested
        Invoice::truncate();

        // Get fees from settings table
        $security_fee = (int) Setting::get('security_fee', 150000);
        $waste_fee = (int) Setting::get('waste_fee', 50000);
        $total = $security_fee + $waste_fee;

        $description = "Iuran Keamanan (Rp " . number_format($security_fee, 0, ',', '.') . ") + Iuran Kebersihan (Rp " . number_format($waste_fee, 0, ',', '.') . ")";

        // Only create invoice for CURRENT month as requested
        Invoice::create([
            'resident_id' => $resident->id,
            'period' => Carbon::now()->format('Y-m'),
            'description' => $description,
            'amount' => $total,
            'status' => 'unpaid',
        ]);
    }
}
