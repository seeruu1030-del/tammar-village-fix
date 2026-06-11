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

    public function pending()
    {
        $residents = Resident::where('status', 'pending')->with(['block'])->get();
        return view('admin.residents.pending', compact('residents'));
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
            'contact' => 'required',
            'email' => 'nullable|email',
            'block_id' => 'required',
            'unit_no' => 'required',
            'family_status' => 'required',
            'housing_status' => 'required',
        ]);

        // Auto-generate password based on NIK or a generic one
        $generatedPassword = 'tamar' . substr($validated['nik'], -4);

        // Always create a User account using NIK as username
        // Use updateOrCreate to gracefully handle orphaned users from previous failed attempts
        $user = \App\Models\User::updateOrCreate(
            ['username' => $validated['nik']], // Search by NIK
            [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'password' => \Illuminate\Support\Facades\Hash::make($generatedPassword),
                'role' => 'warga',
            ]
        );
        
        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        Resident::create($validated);

        return redirect()->back()->with('success', 'Warga berhasil ditambahkan ke daftar persetujuan. Password akses: ' . $generatedPassword);
    }

    public function approve($id)
    {
        $resident = Resident::findOrFail($id);
        $resident->update(['status' => 'active']);
        
        // Generate initial invoice for current month
        \App\Models\Invoice::generateMonthlyInvoice($resident->id);

        return redirect()->route('admin.residents.index')->with('success', 'Warga berhasil disetujui dan tagihan bulan ini telah digenerate.');
    }

    public function show($id)
    {
        $resident = Resident::with(['block', 'familyMembers', 'vehicles', 'user'])->findOrFail($id);
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
            'email' => 'nullable|email|unique:users,email,' . ($resident->user_id ?? 'NULL'),
            'password' => 'nullable|min:8',
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

        // Handle User account update or creation
        if ($request->filled('email')) {
            if ($resident->user) {
                $userData = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ];
                if ($request->filled('password')) {
                    $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
                }
                $resident->user->update($userData);
            } elseif ($request->filled('password')) {
                // Create new user if it didn't exist
                $user = \App\Models\User::create([
                    'name' => $validated['name'],
                    'username' => explode('@', $validated['email'])[0] . '_' . rand(100, 999),
                    'email' => $validated['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                    'role' => 'warga',
                ]);
                $validated['user_id'] = $user->id;
            }
        }

        $resident->update($validated);

        return redirect()->back()->with('success', 'Data warga dan akun akses berhasil diperbarui');
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
