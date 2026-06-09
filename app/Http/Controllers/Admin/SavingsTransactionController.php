<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsTransaction;
use App\Models\SavingsProgram;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingsTransactionController extends Controller
{
    public function index()
    {
        $transactions = SavingsTransaction::with(['resident', 'program'])->orderBy('transaction_date', 'desc')->get();
        $residents = Resident::where('status', 'active')->get();
        $programs = SavingsProgram::where('status', 'active')->get();
        return view('admin.savings.deposits', compact('transactions', 'residents', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'savings_program_id' => 'required|exists:savings_programs,id',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'method' => 'required|in:Cash,Transfer',
            'note' => 'nullable|string',
        ]);

        $program = SavingsProgram::findOrFail($validated['savings_program_id']);

        // Check if program is full and this is a NEW participant
        $isExistingParticipant = SavingsTransaction::where('savings_program_id', $program->id)
            ->where('resident_id', $validated['resident_id'])
            ->exists();

        if ($program->is_full && !$isExistingParticipant) {
            return redirect()->back()->with('error', 'Gagal: Kuota program tabungan ini sudah penuh!');
        }

        $validated['type'] = 'deposit';
        $validated['status'] = 'completed';

        DB::transaction(function() use ($validated, $program) {
            $transaction = SavingsTransaction::create($validated);
            
            // Update collected amount in program
            $program->collected_amount += $validated['amount'];
            $program->save();
        });

        return redirect()->back()->with('success', 'Setoran warga berhasil dicatat');
    }

    public function destroy($id)
    {
        $transaction = SavingsTransaction::findOrFail($id);
        
        DB::transaction(function() use ($transaction) {
            // Revert collected amount in program
            $program = $transaction->program;
            $program->collected_amount -= $transaction->amount;
            $program->save();
            
            $transaction->delete();
        });

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }
}
