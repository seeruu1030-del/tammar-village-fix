<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsProgram;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingsProgramController extends Controller
{
    public function index()
    {
        $programs = SavingsProgram::all();
        
        // Count unique active savers across all programs
        $totalActiveSavers = SavingsTransaction::where('status', 'success')
            ->distinct('resident_id')
            ->count('resident_id');

        // Calculate average deposit per transaction
        $avgDeposit = SavingsTransaction::where('status', 'success')
            ->avg('amount') ?? 0;

        $stats = [
            'total_dana' => $programs->sum('collected_amount'),
            'penabung_aktif' => $totalActiveSavers,
            'rata_setoran' => $avgDeposit
        ];

        return view('admin.savings.programs', compact('programs', 'stats'));
    }

    public function show($id)
    {
        $program = SavingsProgram::findOrFail($id);
        return response()->json($program);
    }

    public function details($id)
    {
        $program = SavingsProgram::findOrFail($id);
        
        // Get participants grouped by resident with their total savings
        $participants = SavingsTransaction::where('savings_program_id', $id)
            ->where('status', 'success')
            ->with(['resident.block'])
            ->select('resident_id', DB::raw('SUM(amount) as total_saved'), DB::raw('MAX(transaction_date) as last_deposit'))
            ->groupBy('resident_id')
            ->get();

        return view('admin.savings.program_details', compact('program', 'participants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|unique:savings_programs',
            'name' => 'required',
            'description' => 'nullable',
            'target_amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,locked,completed',
            'end_date' => 'nullable|date',
        ]);

        SavingsProgram::create($validated);
        return redirect()->back()->with('success', 'Program tabungan berhasil dibuat');
    }

    public function update(Request $request, $id)
    {
        $program = SavingsProgram::findOrFail($id);
        $validated = $request->validate([
            'program_id' => 'required|unique:savings_programs,program_id,' . $id,
            'name' => 'required',
            'description' => 'nullable',
            'target_amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,locked,completed',
            'end_date' => 'nullable|date',
        ]);

        $program->update($validated);
        return redirect()->back()->with('success', 'Program tabungan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $program = SavingsProgram::findOrFail($id);
        $program->delete();
        return redirect()->back()->with('success', 'Program tabungan berhasil dihapus');
    }
}
