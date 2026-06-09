<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Invoice;
use App\Models\SavingsTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $resident = $user->resident;

        if (!$resident) {
            // Handle case where user is not linked to a resident record
            return view('warga.dashboard', [
                'announcements' => Announcement::where('is_active', true)->latest()->take(5)->get(),
                'invoices' => collect(),
                'savings_balance' => 0,
                'savings_transactions' => collect(),
            ]);
        }

        $announcements = Announcement::where('is_active', true)->latest()->take(5)->get();
        
        $invoices = Invoice::where('resident_id', $resident->id)
            ->orderBy('id', 'desc')
            ->get();

        $savings_transactions = SavingsTransaction::where('resident_id', $resident->id)
            ->with('program')
            ->latest()
            ->get();

        $savings_balance = SavingsTransaction::where('resident_id', $resident->id)
            ->where('status', 'success')
            ->sum('amount');

        return view('warga.dashboard', compact(
            'resident', 
            'announcements', 
            'invoices', 
            'savings_transactions', 
            'savings_balance'
        ));
    }
}
