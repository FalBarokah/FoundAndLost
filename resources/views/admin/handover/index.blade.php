@extends('layouts.admin')

@section('title', 'Serah Terima Barang - Satpam')
@section('header', 'Serah Terima')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
        <h1 class="text-xl sm:text-[24px] font-bold text-[#0f172a] mb-1">Pengelolaan Serah Terima Barang</h1>
        <p class="text-xs sm:text-[14px] text-[#64748b]">
            Daftar barang yang siap diserahkan kepada pemilik yang sah. Laporan yang diproses akan dikirimkan ke <strong>Admin</strong> untuk diverifikasi sebelum resmi selesai.
        </p>
    </div>

    <!-- Alert / Kartu Laporan Ditolak oleh Admin (Jika Ada) -->
    @if(isset($rejectedReports) && $rejectedReports->count() > 0)
        <div class="bg-red-50 border-2 border-red-200 rounded-[16px] p-5 shadow-2xs">
            <div class="flex items-center gap-2.5 mb-3 text-red-800">
                <div class="size-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-red-900">Perhatian: Ada Laporan Serah Terima yang Ditolak oleh Admin</h2>
                    <p class="text-xs text-red-700">Silakan periksa catatan alasan penolakan dan lakukan proses ulang dengan data/bukti yang sesuai.</p>
                </div>
            </div>

            <div class="space-y-3 mt-4">
                @foreach($rejectedReports as $rejected)
                    @php $rejHandover = $rejected->handover; @endphp
                    <div class="bg-white border border-red-200 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-2xs">
                        <div class="flex items-start gap-3">
                            @if($rejected->photo_url)
                                <img src="{{ $rejected->photo_url }}" alt="" class="size-12 shrink-0 rounded-lg object-cover border border-red-100">
                            @else
                                <div class="size-12 shrink-0 rounded-lg bg-red-100 flex items-center justify-center font-bold text-xs text-red-700">
                                    {{ $rejected->type === 'hilang' ? 'HLG' : 'DTM' }}
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-[#0f172a]">{{ $rejected->item_name }}</span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700">{{ $rejected->code }}</span>
                                </div>
                                <div class="mt-1.5 text-xs text-red-800 bg-red-50/80 p-2.5 rounded-lg border border-red-100">
                                    <strong class="font-bold text-red-900 block mb-0.5">Catatan Penolakan Admin:</strong>
                                    {{ $rejHandover?->rejection_reason ?: 'Tidak ada alasan khusus yang dicantumkan.' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 justify-end">
                            <a href="{{ route('admin.handover.create', $rejected->report_id) }}"
                               class="bg-[#dc2626] hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-1.5 shadow-2xs">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Proses Ulang Serah Terima</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Data Table Card: Antrean Siap Diserahterimakan -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
        <div class="p-4 sm:px-6 sm:py-4 border-b border-[#e2e8f0] flex items-center justify-between">
            <h2 class="text-base font-bold text-[#0f172a] flex items-center gap-2">
                <svg class="size-5 text-[#2563eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Antrean Siap Serah Terima
            </h2>
            <span class="text-xs text-[#64748b]">Barang yang siap diserahkan ke pemilik</span>
        </div>

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
                                        Proses Serah Terima
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-[#94a3b8]">
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

    <!-- Data Table Card: Sedang Menunggu Verifikasi Admin -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] overflow-hidden shadow-2xs">
        <div class="p-4 sm:px-6 sm:py-4 border-b border-[#e2e8f0] flex items-center justify-between">
            <h2 class="text-base font-bold text-[#0f172a] flex items-center gap-2">
                <svg class="size-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Laporan Menunggu Verifikasi Admin
            </h2>
            <span class="text-xs text-[#64748b]">Sudah diproses satpam, menunggu approval</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-[14px] min-w-[700px] sm:min-w-0">
                <thead class="bg-[#edf0f4] text-[#64748b] text-[12px] font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5">Kode</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama Barang</th>
                        <th class="px-4 sm:px-6 py-3.5">Penerima</th>
                        <th class="px-4 sm:px-6 py-3.5">Petugas Satpam</th>
                        <th class="px-4 sm:px-6 py-3.5">Waktu Pengajuan</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e8f0]">
                    @forelse($pendingVerificationReports as $pending)
                        @php $ho = $pending->handover; @endphp
                        <tr class="hover:bg-white/60 transition-colors">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-[#0f172a] whitespace-nowrap text-xs">
                                {{ $pending->code }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 font-semibold text-[#0f172a]">
                                {{ $pending->item_name }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-[#0f172a]">
                                <span class="font-medium block">{{ $ho?->recipient_name ?? '-' }}</span>
                                <span class="text-[#64748b] text-[11px]">{{ $ho?->recipient_contact ?? '-' }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-xs text-[#64748b] whitespace-nowrap">
                                {{ $ho?->admin?->name ?? 'Satpam' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-[#64748b] whitespace-nowrap text-xs">
                                {{ $ho?->handover_date ? \Carbon\Carbon::parse($ho->handover_date)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menunggu Verifikasi
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#94a3b8]">
                                Belum ada laporan serah terima yang sedang menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
