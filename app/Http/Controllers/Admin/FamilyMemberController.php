<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'name' => 'required',
            'nik' => 'nullable',
            'birth_place' => 'nullable',
            'birth_date' => 'nullable|date',
            'relationship' => 'required',
            'status' => 'required|in:Verifikasi,Belum Verifikasi',
        ]);

        $member = FamilyMember::create($validated);
        return response()->json($member);
    }

    public function show($id)
    {
        $member = FamilyMember::findOrFail($id);
        return response()->json($member);
    }

    public function update(Request $request, $id)
    {
        $member = FamilyMember::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required',
            'nik' => 'nullable',
            'birth_place' => 'nullable',
            'birth_date' => 'nullable|date',
            'relationship' => 'required',
            'status' => 'required|in:Verifikasi,Belum Verifikasi',
        ]);

        $member->update($validated);
        return response()->json($member);
    }

    public function destroy($id)
    {
        $member = FamilyMember::findOrFail($id);
        $member->delete();
        return response()->json(['success' => true]);
    }
}
