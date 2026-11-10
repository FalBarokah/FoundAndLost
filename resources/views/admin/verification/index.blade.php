@extends('layouts.admin')

@section('title', 'Verifikasi Serah Terima - Admin')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-[24px] font-bold text-[#0f172a] mb-1">Verifikasi Serah Terima Barang</h1>
                <p class="text-xs sm:text-[14px] text-[#64748b]">
                    Daftar pengajuan serah terima dari Satpam yang menunggu persetujuan (approval) sebelum dinyatakan resmi selesai.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 px-3.5 py-2 rounded-xl text-amber-800 text-xs sm:text-sm font-semibold shrink-0">
                <span class="size-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span>{{ $pendingHandovers->total() }} Menunggu Persetujuan</span>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[14px] min-w-[760px] sm:min-w-0">
                <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5">Kode</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama Barang</th>
                        <th class="px-4 sm:px-6 py-3.5">Jenis</th>
                        <th class="px-4 sm:px-6 py-3.5">Diajukan Satpam</th>
                        <th class="px-4 sm:px-6 py-3.5">Calon Penerima</th>
                        <th class="px-4 sm:px-6 py-3.5">Waktu Pengajuan</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e8f0]">
                    @forelse($pendingHandovers as $handover)
                        @php
                            $report = $handover->report;
                        @endphp
                        <tr class="hover:bg-white/60 transition-colors">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-[#059669] whitespace-nowrap">
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
                                    <span class="truncate max-w-[150px] sm:max-w-xs font-semibold">{{ $report->item_name }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                @if($report->type === 'hilang')
                                    <span class="bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                                @else
                                    <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-[#0f172a]">
                                <div class="flex items-center gap-1.5">
                                    <svg class="size-4 text-[#64748b]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $handover->admin->name ?? 'Satpam' }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm">
                                <p class="font-medium text-[#0f172a]">{{ $handover->recipient_name }}</p>
                                <p class="text-[11px] text-[#64748b]">{{ $handover->recipient_contact }}</p>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ \Carbon\Carbon::parse($handover->handover_date)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.verification.show', $handover->handover_id) }}"
                                   class="inline-flex items-center gap-1.5 bg-[#059669] hover:bg-[#047857] text-white text-[12px] font-semibold px-3.5 py-2 rounded-[8px] transition-colors shadow-2xs">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Tinjau & Verifikasi</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#94a3b8]">
                                Tidak ada pengajuan serah terima yang menunggu verifikasi saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingHandovers->hasPages())
            <div class="p-4 sm:px-6 sm:py-4 border-t border-[#e2e8f0]">
                {{ $pendingHandovers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
