<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $staffs = User::whereIn('role', ['admin', 'bank', 'security'])
            ->where('id', '!=', $user->id)
            ->get();
            
        return view('admin.settings.index', compact('user', 'staffs'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|confirmed',
            ]);
            
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|max:255|unique:users',
            'role' => 'required|in:admin,bank,security',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);

        return redirect()->back()->with('success', 'Staff baru berhasil ditambahkan');
    }

    public function destroyStaff($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun staff berhasil dihapus');
    }

    public function updateFinance(Request $request)
    {
        // Placeholder for finance settings
        // In a real app, you might save these to a 'settings' table
        return redirect()->back()->with('success', 'Konfigurasi keuangan berhasil disimpan');
    }
}
