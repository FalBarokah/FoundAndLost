@extends('layouts.admin')

@php
    $currentUser = auth('admin')->user();
    $isSuper = $currentUser && $currentUser->isSuperAdmin();
@endphp

@section('title', ($isSuper ? 'Dashboard Admin' : 'Dashboard Satpam') . ' - Found & Lost')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards Grid (Gambar 1: Tetap dipertahankan) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs">
            <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Total Seluruh Laporan</p>
            <p class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mt-1">{{ $totalReports }}</p>
        </div>

        @if($isSuper)
            <a href="{{ route('admin.verification.index') }}" class="bg-[#f7f8fa] hover:bg-white border {{ $pendingVerificationCount > 0 ? 'border-amber-300 bg-amber-50/40' : 'border-[#e2e8f0]' }} rounded-[16px] p-4 sm:p-5 shadow-2xs transition-colors block">
                <div class="flex items-center justify-between">
                    <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Menunggu Verifikasi</p>
                    @if($pendingVerificationCount > 0)
                        <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                    @endif
                </div>
                <p class="text-2xl sm:text-[28px] font-bold text-amber-600 mt-1">{{ $pendingVerificationCount }}</p>
            </a>

            <a href="{{ route('admin.history.index') }}" class="bg-[#f7f8fa] hover:bg-white border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs transition-colors block">
                <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Selesai (History)</p>
                <p class="text-2xl sm:text-[28px] font-bold text-[#059669] mt-1">{{ $completedReports }}</p>
            </a>

            <a href="{{ route('admin.satpam.index') }}" class="bg-[#f7f8fa] hover:bg-white border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs transition-colors block">
                <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Akun Petugas Satpam</p>
                <p class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mt-1">{{ $totalSatpam }}</p>
            </a>
        @else
            <a href="{{ route('admin.handover.index') }}" class="bg-[#f7f8fa] hover:bg-white border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs transition-colors block">
                <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Siap Diserahkan</p>
                <p class="text-2xl sm:text-[28px] font-bold text-[#2563eb] mt-1">{{ $readyToHandover }}</p>
            </a>

            <a href="{{ route('admin.handover.index') }}" class="bg-[#f7f8fa] hover:bg-white border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs transition-colors block">
                <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Menunggu Verifikasi</p>
                <p class="text-2xl sm:text-[28px] font-bold text-amber-600 mt-1">{{ $pendingVerificationCount }}</p>
            </a>

            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs">
                <p class="text-[12px] sm:text-[13px] font-medium text-[#64748b]">Laporan Selesai</p>
                <p class="text-2xl sm:text-[28px] font-bold text-[#16a34a] mt-1">{{ $completedReports }}</p>
            </div>
        @endif
    </div>

    @if($isSuper)
        <!-- ================= KONTEN KHUSUS ADMIN ================= -->
        <!-- Pengajuan Serah Terima Menunggu Verifikasi -->
        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
            <div class="p-4 sm:px-6 sm:py-4 border-b border-[#e2e8f0] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                <div>
                    <h2 class="text-base font-bold text-[#0f172a] flex items-center gap-2">
                        <svg class="size-5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pengajuan Serah Terima Menunggu Verifikasi
                    </h2>
                    <span class="text-xs sm:text-[13px] text-[#64748b]">
                        Laporan serah terima dari satpam yang membutuhkan persetujuan Admin
                    </span>
                </div>
                <a href="{{ route('admin.verification.index') }}" class="text-xs sm:text-[13px] font-bold text-[#059669] hover:underline shrink-0">
                    Lihat Semua Antrean &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[14px] min-w-[700px] sm:min-w-0">
                    <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-3.5">Kode</th>
                            <th class="px-4 sm:px-6 py-3.5">Nama Barang</th>
                            <th class="px-4 sm:px-6 py-3.5">Jenis</th>
                            <th class="px-4 sm:px-6 py-3.5">Diajukan Satpam</th>
                            <th class="px-4 sm:px-6 py-3.5">Calon Penerima</th>
                            <th class="px-4 sm:px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @forelse($pendingHandovers as $handover)
                            @php $rep = $handover->report; @endphp
                            <tr class="hover:bg-white/60 transition-colors">
                                <td class="px-4 sm:px-6 py-4 font-semibold text-[#059669] whitespace-nowrap text-xs">
                                    {{ $rep->code }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 font-medium text-[#0f172a]">
                                    <div class="flex items-center gap-3">
                                        @if($rep->photo_url)
                                            <img src="{{ $rep->photo_url }}" alt="" class="size-10 shrink-0 rounded-[10px] object-cover border border-[#e2e8f0]">
                                        @else
                                            <div class="size-10 shrink-0 rounded-[10px] bg-[#e2e8f0] border border-[#cbd5e1] flex items-center justify-center font-bold text-[10px] text-[#475569]">
                                                IMG
                                            </div>
                                        @endif
                                        <span class="truncate max-w-[160px] font-semibold">{{ $rep->item_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @if($rep->type === 'hilang')
                                        <span class="bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                                    @else
                                        <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-xs font-medium text-[#0f172a] whitespace-nowrap">
                                    {{ $handover->admin->name ?? 'Satpam' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-xs whitespace-nowrap">
                                    <span class="font-medium text-[#0f172a] block">{{ $handover->recipient_name }}</span>
                                    <span class="text-[#64748b] text-[11px]">{{ $handover->recipient_contact }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.verification.show', $handover->handover_id) }}"
                                       class="inline-flex items-center gap-1 bg-[#059669] hover:bg-[#047857] text-white text-[12px] font-semibold px-3.5 py-1.5 rounded-[8px] transition-colors shadow-2xs">
                                        Tinjau &amp; Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-[#94a3b8]">
                                    <div class="flex flex-col items-center justify-center py-2">
                                        <div class="size-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-[#0f172a]">Semua Pengajuan Telah Terverifikasi</span>
                                        <span class="text-xs text-[#64748b] mt-0.5">Tidak ada laporan serah terima yang menunggu persetujuan Admin saat ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- ================= KONTEN KHUSUS SATPAM (Gambar 3) ================= -->
        <!-- Toggle Segments -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard', ['type' => 'hilang']) }}"
               class="flex-1 sm:flex-initial text-center px-4 sm:px-5 py-2.5 rounded-[10px] text-[13px] sm:text-[14px] font-semibold transition-colors
               {{ $type === 'hilang' ? 'bg-[#2563eb] text-white shadow-xs' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:bg-slate-200' }}">
                Barang Hilang
            </a>
            <a href="{{ route('admin.dashboard', ['type' => 'ditemukan']) }}"
               class="flex-1 sm:flex-initial text-center px-4 sm:px-5 py-2.5 rounded-[10px] text-[13px] sm:text-[14px] font-semibold transition-colors
               {{ $type === 'ditemukan' ? 'bg-[#2563eb] text-white shadow-xs' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:bg-slate-200' }}">
                Barang Ditemukan
            </a>
        </div>

        <!-- Data Table Card (Daftar Barang Aktif untuk Operasional Satpam) -->
        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
            <div class="p-4 sm:px-6 sm:py-4 border-b border-[#e2e8f0] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
                <h2 class="text-base font-bold text-[#0f172a]">
                    Daftar Barang {{ $type === 'hilang' ? 'Hilang' : 'Ditemukan' }} Aktif
                </h2>
                <span class="text-xs sm:text-[13px] text-[#64748b]">
                    Menampilkan laporan yang belum selesai
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[14px] min-w-[650px] sm:min-w-0">
                    <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-3.5">Kode</th>
                            <th class="px-4 sm:px-6 py-3.5">Nama Barang</th>
                            <th class="px-4 sm:px-6 py-3.5">Tanggal</th>
                            <th class="px-4 sm:px-6 py-3.5">Lokasi</th>
                            @if($type === 'ditemukan')
                                <th class="px-4 sm:px-6 py-3.5">Klaim Masuk</th>
                            @else
                                <th class="px-4 sm:px-6 py-3.5">Pelapor</th>
                            @endif
                            <th class="px-4 sm:px-6 py-3.5">Status</th>
                            <th class="px-4 sm:px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @forelse($reports as $report)
                            <tr class="hover:bg-white/60 transition-colors">
                                <td class="px-4 sm:px-6 py-4 font-semibold text-[#2563eb] whitespace-nowrap">
                                    {{ $report->code }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 font-medium text-[#0f172a]">
                                    <div class="flex items-center gap-3">
                                        @if($report->photo_url)
                                            <img src="{{ $report->photo_url }}" alt="" class="size-10 sm:size-11 shrink-0 rounded-[10px] object-cover border border-[#e2e8f0]">
                                        @else
                                            <div class="size-10 sm:size-11 shrink-0 rounded-[10px] bg-[#e2e8f0] border border-[#cbd5e1] flex flex-col items-center justify-center font-bold leading-none select-none">
                                                <span class="text-[10px] text-[#475569] tracking-wider">No</span>
                                                <span class="text-[9px] text-[#94a3b8] tracking-widest mt-0.5">IMG</span>
                                            </div>
                                        @endif
                                        <span class="truncate max-w-[150px] sm:max-w-xs">{{ $report->item_name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                    {{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-[#64748b] text-xs sm:text-sm">
                                    {{ $report->location }}
                                </td>
                                @if($type === 'ditemukan')
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        @php
                                            $claimCount = $report->claims->count();
                                            $pendingCount = $report->claims->where('status', 'menunggu')->count();
                                        @endphp
                                        @if($claimCount > 0)
                                            <span class="inline-flex items-center gap-1 bg-[#dbeafe] text-[#1e40af] text-[12px] font-semibold px-2.5 py-0.5 rounded-full">
                                                {{ $claimCount }} klaim
                                                @if($pendingCount > 0)
                                                    <span class="size-1.5 rounded-full bg-blue-600"></span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-[#94a3b8] text-[12px]">Belum ada</span>
                                        @endif
                                    </td>
                                @else
                                    <td class="px-4 sm:px-6 py-4 text-[#0f172a] whitespace-nowrap text-xs sm:text-sm">
                                        {{ $report->reporter_name }}
                                    </td>
                                @endif
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @if($report->status === 'proses_pencarian')
                                        <span class="bg-[#fef3c7] text-[#92400e] text-[11px] sm:text-[12px] font-semibold px-2.5 py-1 rounded-full">
                                            Proses Pencarian
                                        </span>
                                    @elseif($report->status === 'siap_diambil')
                                        <span class="bg-[#dcfce7] text-[#16a34a] text-[11px] sm:text-[12px] font-semibold px-2.5 py-1 rounded-full">
                                            Siap Diambil
                                        </span>
                                    @elseif($report->status === 'menunggu_verifikasi')
                                        <span class="bg-amber-100 text-amber-800 border border-amber-300 text-[11px] sm:text-[12px] font-semibold px-2.5 py-1 rounded-full">
                                            Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="bg-[#f1f5f9] text-[#475569] text-[11px] sm:text-[12px] font-semibold px-2.5 py-1 rounded-full">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.report.show', $report->report_id) }}"
                                       class="inline-flex items-center bg-[#2563eb] hover:bg-blue-700 text-white text-[12px] font-semibold px-3.5 py-1.5 rounded-[8px] transition-colors shadow-2xs">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-[#94a3b8]">
                                    Belum ada data laporan {{ $type === 'hilang' ? 'hilang' : 'ditemukan' }} aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="p-4 sm:px-6 sm:py-4 border-t border-[#e2e8f0]">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
