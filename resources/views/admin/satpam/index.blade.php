@extends('layouts.admin')

@section('title', 'Kelola Akun Satpam - Admin')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-[24px] font-bold text-[#0f172a] mb-1">Kelola Akun Petugas Satpam</h1>
                <p class="text-xs sm:text-[14px] text-[#64748b]">
                    Kelola data akses login bagi petugas satpam yang bertugas memproses laporan barang temuan &amp; serah terima.
                </p>
            </div>
            <a href="{{ route('admin.satpam.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-[10px] transition-colors shadow-2xs shrink-0 cursor-pointer">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Akun Satpam</span>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[14px] min-w-[650px] sm:min-w-0">
                <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5">Nama Petugas</th>
                        <th class="px-4 sm:px-6 py-3.5">Email Login</th>
                        <th class="px-4 sm:px-6 py-3.5">Role</th>
                        <th class="px-4 sm:px-6 py-3.5">Serah Terima Diproses</th>
                        <th class="px-4 sm:px-6 py-3.5">Terdaftar Sejak</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e8f0]">
                    @forelse($satpams as $satpam)
                        <tr class="hover:bg-white/60 transition-colors">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-[#0f172a]">
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($satpam->name, 0, 2) }}
                                    </div>
                                    <span>{{ $satpam->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ $satpam->email }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 text-[11px] font-bold px-2.5 py-1 rounded-full border border-blue-200">
                                    Satpam
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#0f172a] font-semibold whitespace-nowrap text-xs sm:text-sm">
                                {{ $satpam->handovers_count }} laporan
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ $satpam->created_at ? $satpam->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.satpam.edit', $satpam->admin_id) }}"
                                       class="inline-flex items-center gap-1 bg-[#f1f5f9] hover:bg-slate-200 text-[#0f172a] text-[12px] font-semibold px-3 py-1.5 rounded-[8px] transition-colors">
                                        <svg class="size-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <form action="{{ route('admin.satpam.destroy', $satpam->admin_id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Satpam \'{{ $satpam->name }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-[12px] font-semibold px-3 py-1.5 rounded-[8px] transition-colors cursor-pointer">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#94a3b8]">
                                Belum ada akun Satpam yang terdaftar. Klik tombol Tambah di atas untuk membuat akun baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($satpams->hasPages())
            <div class="p-4 sm:px-6 sm:py-4 border-t border-[#e2e8f0]">
                {{ $satpams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
