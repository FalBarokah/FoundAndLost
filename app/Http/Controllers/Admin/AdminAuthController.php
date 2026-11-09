<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if ($admin) {
            $storedPassword = $admin->getRawOriginal('password') ?? $admin->password;
            $isBcrypt = str_starts_with($storedPassword, '$2y$')
                || str_starts_with($storedPassword, '$2a$')
                || str_starts_with($storedPassword, '$2b$');

            if ($isBcrypt) {
                // Password sudah di-hash Bcrypt → gunakan Hash::check
                if (Hash::check($credentials['password'], $storedPassword)) {
                    Auth::guard('admin')->login($admin, $request->boolean('remember'));
                    $request->session()->regenerate();

                    return redirect()->intended(route('admin.dashboard'));
                }
            } else {
                // Password plaintext (diedit manual di phpMyAdmin)
                if ($credentials['password'] === $storedPassword) {
                    // Otomatis upgrade ke hash Bcrypt yang aman
                    $admin->password = Hash::make($credentials['password']);
                    $admin->save();

                    Auth::guard('admin')->login($admin, $request->boolean('remember'));
                    $request->session()->regenerate();

                    return redirect()->intended(route('admin.dashboard'));
                }
            }
        }

        return back()->withErrors([
            'login_error' => 'Email atau password yang Anda masukkan salah. Silakan periksa kembali.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
