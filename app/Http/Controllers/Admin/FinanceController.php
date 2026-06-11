<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $paidInvoices = Invoice::where('status', 'paid')
            ->with('resident')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('admin.finance.index', compact('paidInvoices'));
    }

    public function generateMassInvoices()
    {
        $residents = \App\Models\Resident::where('status', 'active')->get();
        $count = 0;
        
        foreach ($residents as $resident) {
            $invoice = Invoice::generateMonthlyInvoice($resident->id);
            if ($invoice) $count++;
        }
        
        return redirect()->back()->with('success', $count . ' tagihan baru berhasil digenerate untuk bulan ini.');
    }

    public function verification()
    {
        $pendingInvoices = Invoice::where('status', 'pending_verification')
            ->with('resident')
            ->orderBy('payment_date', 'asc')
            ->get();
            
        return view('admin.finance.verification', compact('pendingInvoices'));
    }

    public function approve($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'paid']);

        // Record to general transaction table if needed
        Transaction::create([
            'date' => now(),
            'description' => 'Pembayaran Iuran IPL - ' . $invoice->resident->name . ' (' . $invoice->period . ')',
            'type' => 'credit',
            'amount' => $invoice->amount,
            'balance' => Transaction::orderBy('id', 'desc')->first()->balance ?? 0 + $invoice->amount,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'status' => 'unpaid',
            'proof_path' => null,
            'payment_date' => null
        ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak. Status invoice kembali ke Belum Bayar.');
    }
}
