<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id', 'period', 'description', 'amount', 'status', 'proof_path', 'payment_date',
        'payment_method', 'midtrans_order_id', 'midtrans_snap_token'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public static function generateMonthlyInvoice($residentId, $period = null)
    {
        $period = $period ?? \Carbon\Carbon::now()->format('Y-m');
        
        // Check if invoice already exists for this period
        $exists = self::where('resident_id', $residentId)
            ->where('period', $period)
            ->exists();
            
        if ($exists) return null;

        $security_fee = (int) \App\Models\Setting::get('security_fee', 150000);
        $waste_fee = (int) \App\Models\Setting::get('waste_fee', 50000);
        $total = $security_fee + $waste_fee;

        $description = "Iuran Keamanan (Rp " . number_format($security_fee, 0, ',', '.') . ") + Iuran Kebersihan (Rp " . number_format($waste_fee, 0, ',', '.') . ")";

        return self::create([
            'resident_id' => $residentId,
            'period' => $period,
            'description' => $description,
            'amount' => $total,
            'status' => 'unpaid',
        ]);
    }
}
