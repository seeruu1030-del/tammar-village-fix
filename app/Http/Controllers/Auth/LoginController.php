<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        // Support login via email or username (which stores NIK for Warga)
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $input['login'], 'password' => $input['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            return match ($user->role) {
                'admin' => redirect()->intended('/admin'),
                'warga' => redirect()->intended('/warga'),
                'bank' => redirect()->intended('/bank'),
                'security' => redirect()->intended('/security'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
