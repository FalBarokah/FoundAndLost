@extends('layouts.admin')

@section('title', 'History Serah Terima - Admin')
@section('header', 'History')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
        <h1 class="text-xl sm:text-[24px] font-bold text-[#0f172a] mb-1">Riwayat Laporan Selesai</h1>
        <p class="text-xs sm:text-[14px] text-[#64748b]">
            Arsip seluruh barang yang telah berhasil diserahterimakan kepada pemilik yang sah beserta bukti dokumentasinya.
        </p>
    </div>

    <!-- Data Table Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[14px] min-w-[750px] sm:min-w-0">
                <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5">Kode</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama Barang</th>
                        <th class="px-4 sm:px-6 py-3.5">Jenis</th>
                        <th class="px-4 sm:px-6 py-3.5">Penerima</th>
                        <th class="px-4 sm:px-6 py-3.5">Tanggal Serah Terima</th>
                        <th class="px-4 sm:px-6 py-3.5">Petugas</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e8f0]">
                    @forelse($histories as $report)
                        @php
                            $handover = $report->handover;
                        @endphp
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
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                @if($report->type === 'hilang')
                                    <span class="bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                                @else
                                    <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#0f172a] font-medium whitespace-nowrap text-xs sm:text-sm">
                                {{ $handover?->recipient_name ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ $handover?->handover_date ? \Carbon\Carbon::parse($handover->handover_date)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ $handover?->admin?->name ?? 'Admin Satpam' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.history.show', $report->report_id) }}"
                                   class="inline-flex items-center bg-[#2563eb] hover:bg-blue-700 text-white text-[12px] font-semibold px-3.5 py-1.5 rounded-[8px] transition-colors shadow-2xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#94a3b8]">
                                Belum ada riwayat laporan yang selesai diserahterimakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($histories->hasPages())
            <div class="p-4 sm:px-6 sm:py-4 border-t border-[#e2e8f0]">
                {{ $histories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
