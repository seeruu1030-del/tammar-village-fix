<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\SavingsTransaction;
use App\Models\Invoice;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $total_residents = Resident::count();
        $total_va = Resident::whereNotNull('nik')->count(); // Assume VA is issued if NIK is present
        
        $today_transactions = SavingsTransaction::whereDate('transaction_date', now())
            ->where('status', 'success')
            ->count() + 
            Invoice::whereDate('updated_at', now())
            ->where('status', 'paid')
            ->count();

        $today_volume = SavingsTransaction::whereDate('transaction_date', now())
            ->where('status', 'success')
            ->sum('amount') + 
            Invoice::whereDate('updated_at', now())
            ->where('status', 'paid')
            ->sum('amount');

        $residents = Resident::with(['user', 'block'])->get();
        
        // Mock data for the chart or fetch from Transactions if available
        $recent_transactions = Transaction::latest()->take(10)->get();

        return view('bank.dashboard', compact(
            'total_residents',
            'total_va',
            'today_transactions',
            'today_volume',
            'residents',
            'recent_transactions'
        ));
    }
}
