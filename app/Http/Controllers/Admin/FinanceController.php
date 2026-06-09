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
        $invoices = Invoice::with('resident')->latest()->take(10)->get();
        $transactions = Transaction::latest()->take(10)->get();
        return view('admin.finance.index', compact('invoices', 'transactions'));
    }
}
