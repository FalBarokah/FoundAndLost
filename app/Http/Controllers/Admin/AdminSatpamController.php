<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSatpamController extends Controller
{
    public function index()
    {
        $satpams = Admin::where('role', Admin::ROLE_SATPAM)
            ->withCount('handovers')
            ->latest('admin_id')
            ->paginate(10);

        return view('admin.satpam.index', compact('satpams'));
    }

    public function create()
    {
        return view('admin.satpam.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => Admin::ROLE_SATPAM,
        ]);

        return redirect()->route('admin.satpam.index')
            ->with('success', "Akun Satpam '{$validated['name']}' berhasil ditambahkan.");
    }

    public function edit(int $id)
    {
        $satpam = Admin::where('role', Admin::ROLE_SATPAM)->findOrFail($id);

        return view('admin.satpam.edit', compact('satpam'));
    }

    public function update(Request $request, int $id)
    {
        $satpam = Admin::where('role', Admin::ROLE_SATPAM)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('admins', 'email')->ignore($satpam->admin_id, 'admin_id')],
            'password' => 'nullable|string|min:6',
        ]);

        $satpam->name = $validated['name'];
        $satpam->email = $validated['email'];

        if (! empty($validated['password'])) {
            $satpam->password = Hash::make($validated['password']);
        }

        $satpam->save();

        return redirect()->route('admin.satpam.index')
            ->with('success', "Data akun Satpam '{$satpam->name}' berhasil diperbarui.");
    }

    public function destroy(int $id)
    {
        $satpam = Admin::where('role', Admin::ROLE_SATPAM)->findOrFail($id);

        // Pencegahan: jangan hapus akun yang sedang login
        if ($satpam->admin_id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $satpam->name;
        $satpam->delete();

        return redirect()->route('admin.satpam.index')
            ->with('success', "Akun Satpam '{$name}' berhasil dihapus.");
    }
}
