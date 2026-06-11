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
    public function index(Request $request)
    {
        $selected_program_id = $request->get('program_id');
        $programs = SavingsProgram::where('status', 'active')->get();
        $selected_program = $selected_program_id ? SavingsProgram::find($selected_program_id) : null;
        
        $query = Resident::where('status', 'active')->with(['user', 'block']);

        if ($selected_program) {
            $residents = $query->get()->map(function($resident) use ($selected_program) {
                $resident->current_balance = SavingsTransaction::where('resident_id', $resident->id)
                    ->where('savings_program_id', $selected_program->id)
                    ->where('status', 'success')
                    ->sum('amount');
                
                $target = $selected_program->target_amount;
                $resident->progress_percentage = $target > 0 ? round(min(($resident->current_balance / $target) * 100, 100), 1) : 0;
                
                return $resident;
            });
        } else {
            $residents = $query->get()->map(function($resident) {
                $resident->current_balance = SavingsTransaction::where('resident_id', $resident->id)
                    ->where('status', 'success')
                    ->sum('amount');
                $resident->progress_percentage = 0;
                return $resident;
            });
        }

        $transactions = SavingsTransaction::with(['resident', 'program'])
            ->when($selected_program_id, function($q) use ($selected_program_id) {
                return $q->where('savings_program_id', $selected_program_id);
            })
            ->orderBy('transaction_date', 'desc')
            ->take(100)
            ->get();

        return view('admin.savings.deposits', compact('transactions', 'residents', 'programs', 'selected_program_id', 'selected_program'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'savings_program_id' => 'required|exists:savings_programs,id',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|in:Cash,Transfer',
            'note' => 'nullable|string',
        ]);

        $program = SavingsProgram::findOrFail($validated['savings_program_id']);

        $validated['type'] = 'deposit';
        $validated['status'] = 'success';

        DB::transaction(function() use ($validated, $program) {
            $transaction = SavingsTransaction::create($validated);
            
            // Update collected amount in program
            $program->collected_amount += $validated['amount'];
            $program->save();
        });

        return redirect()->back()->with('success', 'Setoran warga berhasil dicatat');
    }

    public function approve($id)
    {
        $transaction = SavingsTransaction::findOrFail($id);
        
        if ($transaction->status == 'success') {
            return redirect()->back()->with('error', 'Transaksi sudah disetujui sebelumnya.');
        }

        DB::transaction(function() use ($transaction) {
            $transaction->update(['status' => 'success']);
            
            // Update collected amount in program
            $program = $transaction->program;
            $program->collected_amount += $transaction->amount;
            $program->save();
        });

        return redirect()->back()->with('success', 'Setoran tabungan berhasil disetujui.');
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
