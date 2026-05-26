<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';

        $credentials = [
            $field => $login,
            'password' => $request->password,
            'status' => 'active',
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect berdasarkan role
            if ($user->hasRole('super_admin')) {
                return redirect()->intended('/super-admin/dashboard');
            } elseif ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('wakasek_kurikulum')) {
                return redirect()->intended('/wakasek-kurikulum/dashboard');
            } elseif ($user->hasRole('wali_kelas')) {
                return redirect()->intended('/wali-kelas/dashboard');
            } elseif ($user->hasRole('teacher')) {
                return redirect()->intended('/guru/dashboard');
            } elseif ($user->hasRole('sekretaris')) {
                return redirect()->intended('/sekretaris/dashboard');
            } elseif ($user->hasRole('siswa')) {
                return redirect()->intended('/siswa/dashboard');
            }
            
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}