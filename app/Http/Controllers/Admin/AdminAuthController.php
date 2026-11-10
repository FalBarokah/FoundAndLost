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
            'role' => 'nullable|string|in:super_admin,admin,satpam',
        ]);

        $inputEmail = trim(strtolower($credentials['email']));
        $selectedRole = $request->input('role', 'super_admin');

        // Cari admin berdasarkan email (case-insensitive & trim)
        $admin = Admin::whereRaw('LOWER(TRIM(email)) = ?', [$inputEmail])->first();

        if ($admin) {
            $storedPassword = $admin->getRawOriginal('password') ?? $admin->password;
            $isBcrypt = str_starts_with($storedPassword, '$2y$')
                || str_starts_with($storedPassword, '$2a$')
                || str_starts_with($storedPassword, '$2b$');

            $passwordMatches = false;

            if ($isBcrypt) {
                // Password sudah di-hash Bcrypt → gunakan Hash::check
                $passwordMatches = Hash::check($credentials['password'], $storedPassword);
            } else {
                // Mendukung password plaintext, MD5, atau SHA1 (sering diedit langsung di phpMyAdmin)
                if (
                    $credentials['password'] === $storedPassword
                    || md5($credentials['password']) === $storedPassword
                    || sha1($credentials['password']) === $storedPassword
                ) {
                    // Otomatis upgrade ke hash Bcrypt yang aman
                    $admin->password = Hash::make($credentials['password']);
                    $admin->save();
                    $passwordMatches = true;
                }
            }

            if ($passwordMatches) {
                // Periksa apakah role akun sesuai dengan tab toggle yang dipilih saat login
                if ($request->filled('role')) {
                    $selectedRole = $request->input('role');
                    $selectedIsAdmin = in_array($selectedRole, ['super_admin', 'admin'], true);
                    $userIsAdmin = $admin->isSuperAdmin();

                    if ($selectedIsAdmin && ! $userIsAdmin) {
                        return back()->withErrors([
                            'login_error' => "Akun ini terdaftar sebagai Satpam, bukan Admin. Silakan alihkan pilihan login ke tab 'Satpam'.",
                        ])->withInput($request->only('email', 'role'));
                    }

                    if (! $selectedIsAdmin && $userIsAdmin) {
                        return back()->withErrors([
                            'login_error' => "Akun ini terdaftar sebagai Admin, bukan Satpam. Silakan alihkan pilihan login ke tab 'Admin'.",
                        ])->withInput($request->only('email', 'role'));
                    }
                }

                Auth::guard('admin')->login($admin, $request->boolean('remember'));
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'login_error' => 'Email atau password yang Anda masukkan salah. Silakan periksa kembali.',
        ])->withInput($request->only('email', 'role'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
