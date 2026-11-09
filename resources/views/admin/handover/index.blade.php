@extends('layouts.admin')

@section('title', 'Serah Terima - Admin')
@section('header', 'Serah Terima')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
        <h1 class="text-xl sm:text-[24px] font-bold text-[#0f172a] mb-1">Antrean Serah Terima</h1>
        <p class="text-xs sm:text-[14px] text-[#64748b]">
            Daftar barang yang siap diserahkan kepada pemilik setelah verifikasi atau klaim disetujui. Setelah serah terima diproses, barang akan dipindahkan ke History.
        </p>
    </div>

    <!-- Data Table Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[14px] min-w-[700px] sm:min-w-0">
                <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5">Kode</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama Barang</th>
                        <th class="px-4 sm:px-6 py-3.5">Jenis</th>
                        <th class="px-4 sm:px-6 py-3.5">Calon Penerima</th>
                        <th class="px-4 sm:px-6 py-3.5">Kontak</th>
                        <th class="px-4 sm:px-6 py-3.5">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e8f0]">
                    @forelse($queueReports as $report)
                        @php
                            $activeClaim = $report->claims->first();
                            $recipientName = $activeClaim ? $activeClaim->claimant_name : $report->reporter_name;
                            $recipientContact = $activeClaim ? $activeClaim->claimant_phone : $report->reporter_phone;
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
                                {{ $recipientName }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ $recipientContact }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs sm:text-sm">
                                {{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <form action="{{ route('admin.handover.cancelQueue', $report->report_id) }}" method="POST" onsubmit="return confirm('Batalkan tindak lanjut antrean ini?')">
                                        @csrf
                                        <button type="submit" class="bg-[#f1f5f9] hover:bg-slate-200 text-[#475569] text-[12px] font-semibold px-3 py-2 rounded-[8px] transition-colors cursor-pointer">
                                            Batal
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.handover.create', $report->report_id) }}"
                                       class="bg-[#16a34a] hover:bg-green-700 text-white text-[12px] font-semibold px-4 py-2 rounded-[8px] transition-colors whitespace-nowrap inline-flex items-center justify-center shadow-2xs">
                                        Proses
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#94a3b8]">
                                Tidak ada laporan dalam antrean serah terima saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($queueReports->hasPages())
            <div class="p-4 sm:px-6 sm:py-4 border-t border-[#e2e8f0]">
                {{ $queueReports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
