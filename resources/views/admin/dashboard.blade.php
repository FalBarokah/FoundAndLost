@extends('layouts.admin')

@section('title', 'Dashboard Admin - Found & Lost')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs">
            <p class="text-[13px] font-medium text-[#64748b]">Total Laporan</p>
            <p class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mt-1">{{ $totalReports }}</p>
        </div>

        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs">
            <p class="text-[13px] font-medium text-[#64748b]">Siap Diserahkan</p>
            <p class="text-2xl sm:text-[28px] font-bold text-[#2563eb] mt-1">{{ $readyToHandover }}</p>
        </div>

        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-4 sm:p-5 shadow-2xs">
            <p class="text-[13px] font-medium text-[#64748b]">Selesai (History)</p>
            <p class="text-2xl sm:text-[28px] font-bold text-[#16a34a] mt-1">{{ $completedReports }}</p>
        </div>
    </div>

    <!-- Toggle Segments -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.dashboard', ['type' => 'hilang']) }}"
           class="flex-1 sm:flex-initial text-center px-4 sm:px-5 py-2.5 rounded-[10px] text-[13px] sm:text-[14px] font-semibold transition-colors {{ $type === 'hilang' ? 'bg-[#2563eb] text-white shadow-xs' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:bg-slate-200' }}">
            Barang Hilang
        </a>
        <a href="{{ route('admin.dashboard', ['type' => 'ditemukan']) }}"
           class="flex-1 sm:flex-initial text-center px-4 sm:px-5 py-2.5 rounded-[10px] text-[13px] sm:text-[14px] font-semibold transition-colors {{ $type === 'ditemukan' ? 'bg-[#2563eb] text-white shadow-xs' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:bg-slate-200' }}">
            Barang Ditemukan
        </a>
    </div>

    <!-- Data Table Card -->
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
</div>
@endsection
