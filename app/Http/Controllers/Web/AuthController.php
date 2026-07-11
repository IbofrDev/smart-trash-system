<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

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

    /**
     * Tampilkan halaman forgot password
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email
     */
      public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email tidak terdaftar di sistem.']);
        }

        if (!$user->is_active) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Akun tidak aktif. Hubungi administrator.']);
        }

        // Generate token langsung (tanpa kirim email)
        $token = Password::broker()->createToken($user);

        LogAktivitas::create([
            'user_type' => $user->role,
            'user_id' => $user->id,
            'aktivitas' => 'Request reset password',
            'ip_address' => $request->ip(),
        ]);

        // Langsung redirect ke halaman reset password
        return redirect()->route('password.reset', ['token' => $token])
            ->with(['email' => $user->email, 'success' => 'Silakan buat password baru Anda.']);
    }

    /**
     * Tampilkan halaman reset password
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                LogAktivitas::create([
                    'user_type' => $user->role,
                    'user_id' => $user->id,
                    'aktivitas' => 'Reset password berhasil',
                    'ip_address' => request()->ip(),
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.')
            : back()->withInput()->withErrors(['email' => [__($status)]]);
    }
}
