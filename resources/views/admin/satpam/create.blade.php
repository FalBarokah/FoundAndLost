@extends('layouts.admin')

@section('title', 'Tambah Akun Satpam - Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.satpam.index') }}"
           class="inline-flex items-center text-xs sm:text-sm font-semibold text-[#64748b] hover:text-[#059669] transition-colors">
            &larr; Kembali ke Daftar Satpam
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 sm:p-8 shadow-2xs">
        <div class="mb-6 pb-4 border-b border-[#e2e8f0]">
            <h1 class="text-xl font-bold text-[#0f172a]">Tambah Akun Satpam Baru</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-1">
                Buat kredensial login untuk petugas satpam pos pengamanan.
            </p>
        </div>

        <form action="{{ route('admin.satpam.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#0f172a] mb-1.5 uppercase">
                    Nama Petugas Satpam <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso (Pos 1)"
                       class="w-full bg-white border @error('name') border-red-500 @else border-slate-300 @enderror rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#059669]">
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#0f172a] mb-1.5 uppercase">
                    Email Login <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="satpam.pos1@gmail.com"
                       class="w-full bg-white border @error('email') border-red-500 @else border-slate-300 @enderror rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#059669]">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#0f172a] mb-1.5 uppercase">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6"
                       class="w-full bg-white border @error('password') border-red-500 @else border-slate-300 @enderror rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#059669]">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-[#64748b] mt-1">Petugas dapat menggunakan email dan password ini untuk masuk melalui toggle login 'Satpam'.</p>
            </div>

            <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-end gap-3">
                <a href="{{ route('admin.satpam.index') }}"
                   class="px-4 py-2.5 rounded-[10px] text-xs font-semibold text-[#64748b] hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-[10px] text-xs font-bold bg-[#059669] hover:bg-[#047857] text-white transition-all shadow-xs cursor-pointer">
                    Simpan Akun Satpam
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
