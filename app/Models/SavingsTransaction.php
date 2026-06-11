<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id', 'savings_program_id', 'amount', 'transaction_date', 
        'type', 'payment_method', 'midtrans_order_id', 'midtrans_snap_token',
        'reference_no', 'status', 'note', 'proof_path'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function program()
    {
        return $this->belongsTo(SavingsProgram::class, 'savings_program_id');
    }
}
