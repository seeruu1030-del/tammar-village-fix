<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::where('status', 'active')->with(['block', 'familyMembers', 'vehicles'])->get();
        $blocks = Block::all();
        return view('admin.residents.index', compact('residents', 'blocks'));
    }

    public function nonActive()
    {
        $residents = Resident::where('status', 'inactive')->with(['block', 'familyMembers', 'vehicles'])->get();
        return view('admin.residents.inactive', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nik' => 'required|unique:residents',
            'birth_place' => 'nullable',
            'birth_date' => 'nullable|date',
            'contact' => 'required',
            'email' => 'nullable|email',
            'block_id' => 'required',
            'unit_no' => 'required',
            'family_status' => 'required',
            'housing_status' => 'required',
            'document' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents', 'public');
            $validated['document'] = $path;
        }

        Resident::create($validated);

        return redirect()->back()->with('success', 'Warga berhasil ditambahkan');
    }

    public function show($id)
    {
        $resident = Resident::with(['block', 'familyMembers', 'vehicles'])->findOrFail($id);
        return response()->json($resident);
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required',
            'nik' => 'required|unique:residents,nik,' . $id,
            'birth_place' => 'nullable',
            'birth_date' => 'nullable|date',
            'contact' => 'required',
            'email' => 'nullable|email',
            'telegram_id' => 'nullable',
            'block_id' => 'required',
            'unit_no' => 'required',
            'family_status' => 'required',
            'housing_status' => 'required',
            'status' => 'required',
            'document' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('document')) {
            if ($resident->document) {
                Storage::disk('public')->delete($resident->document);
            }
            $path = $request->file('document')->store('documents', 'public');
            $validated['document'] = $path;
        }

        $resident->update($validated);

        return redirect()->back()->with('success', 'Data warga berhasil diperbarui');
    }

    public function registerOut(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:residents,id',
            'exit_date' => 'required|date',
            'exit_status' => 'required',
            'exit_reason' => 'nullable',
        ]);

        Resident::whereIn('id', $validated['ids'])->update([
            'status' => 'inactive',
            'exit_date' => $validated['exit_date'],
            'exit_status' => $validated['exit_status'],
            'exit_reason' => $validated['exit_reason'],
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        if ($resident->document) {
            Storage::disk('public')->delete($resident->document);
        }
        $resident->delete();
        return redirect()->back()->with('success', 'Warga berhasil dihapus');
    }
}
