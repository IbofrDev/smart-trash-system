<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $redirectRoute = match($user->role) {
                'admin' => 'admin.dashboard',
                'pengelola' => 'pengelola.dashboard',
                default => 'admin.dashboard',
            };
            return redirect()->route($redirectRoute);
        }

        return view('welcome'); 
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Cek apakah user aktif
            if (!$user->is_active) {
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
            }

            $request->session()->regenerate();

            // Log aktivitas
            LogAktivitas::create([
                'user_type' => $user->role,
                'user_id' => $user->id,
                'aktivitas' => 'Login ke sistem',
                'ip_address' => $request->ip(),
            ]);

            $redirectRoute = match($user->role) {
                'admin' => 'admin.dashboard',
                'pengelola' => 'pengelola.dashboard',
                default => 'admin.dashboard',
            };

            return redirect()->intended(route($redirectRoute))
                ->with('success', "Selamat datang, {$user->name}!");
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Log aktivitas
            LogAktivitas::create([
                'user_type' => $user->role,
                'user_id' => $user->id,
                'aktivitas' => 'Logout dari sistem',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

                return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}