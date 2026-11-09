@extends('layouts.admin')

@section('title', 'Detail Laporan Temuan ' . $report->code . ' - Admin')
@section('header', 'Detail Laporan Temuan')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('admin.dashboard', ['type' => 'ditemukan']) }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline">
        &larr; Kembali ke dashboard
    </a>

    <!-- Page Heading -->
    <div>
        <h1 class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mb-1">Detail Laporan Temuan</h1>
        <p class="text-xs sm:text-[15px] text-[#64748b]">
            {{ $report->code }} &middot; Barang Ditemukan. Tinjau klaim kepemilikan dan verifikasi barang.
        </p>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Item Info & Photo -->
        <div class="lg:col-span-5 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 flex flex-col gap-4 shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                @if($report->status === 'proses_pencarian')
                    <span class="bg-[#fef3c7] text-[#92400e] text-[12px] font-semibold px-2.5 py-1 rounded-full">Proses Pencarian</span>
                @elseif($report->status === 'siap_diambil')
                    <span class="bg-[#dcfce7] text-[#16a34a] text-[12px] font-semibold px-2.5 py-1 rounded-full">Siap Diambil</span>
                @else
                    <span class="bg-[#f1f5f9] text-[#475569] text-[12px] font-semibold px-2.5 py-1 rounded-full">Selesai</span>
                @endif
            </div>

            <h2 class="text-xl sm:text-[22px] font-bold text-[#0f172a]">{{ $report->item_name }}</h2>

            @if($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full max-h-[260px] object-cover rounded-[12px] border border-[#e2e8f0]">
            @else
                <div class="w-full h-56 sm:h-[240px] bg-[#e2e8f0] rounded-[12px] flex items-center justify-center text-[#94a3b8] text-[14px] font-medium">
                    Foto Barang
                </div>
            @endif

            <div class="border-t border-[#e2e8f0] pt-3 flex flex-col gap-2.5 text-[14px]">
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Lokasi ditemukan</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->location }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Tanggal ditemukan</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Nama Penemu</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->reporter_name }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Kontak Penemu</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->reporter_phone }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Deskripsi</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->description }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2 bg-amber-50 p-2.5 rounded-[8px] border border-amber-200">
                    <span class="text-[#92400e] text-[13px] font-semibold sm:w-36">Ciri Rahasia (Penemu)</span>
                    <span class="text-[#78350f] font-medium sm:text-right flex-1">{{ $report->special_features ?? 'Tidak ada' }}</span>
                </div>
            </div>

            <!-- Delete Button -->
            <div class="border-t border-[#e2e8f0] pt-4">
                <form action="{{ route('admin.report.destroy', $report->report_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-[#dc2626] hover:bg-red-700 text-white font-semibold text-[14px] py-2.5 rounded-[10px] transition-colors cursor-pointer">
                        Hapus Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Daftar Klaim Masuk -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-base sm:text-[18px] font-bold text-[#0f172a]">
                        Daftar Klaim Masuk ({{ $report->claims->count() }})
                    </h3>
                </div>
                <p class="text-xs sm:text-[13px] text-[#64748b]">
                    Tinjau klaim dan cocokkan bukti atau ciri khusus pengklaim dengan ciri rahasia barang. Jika sesuai, klik <strong>Tindak Lanjuti</strong> untuk memasukkan ke antrean Serah Terima.
                </p>
            </div>

            @forelse($report->claims as $claim)
                <div class="bg-[#f7f8fa] border {{ $claim->status === 'ditindaklanjuti' ? 'border-[#2563eb] ring-1 ring-[#2563eb]' : 'border-[#e2e8f0]' }} rounded-[16px] p-5 sm:p-6 flex flex-col gap-4 shadow-2xs">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-[#e2e8f0] pb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-[#0f172a] text-[15px] sm:text-[16px]">{{ $claim->claim_code }}</span>
                            @if($claim->status === 'ditindaklanjuti')
                                <span class="bg-[#dcfce7] text-[#166534] text-[11px] sm:text-[12px] font-semibold px-2.5 py-0.5 rounded-full">Ditindaklanjuti</span>
                            @elseif($claim->status === 'dibatalkan')
                                <span class="bg-[#fee2e2] text-[#dc2626] text-[11px] sm:text-[12px] font-semibold px-2.5 py-0.5 rounded-full">Dibatalkan</span>
                            @else
                                <span class="bg-[#fef3c7] text-[#92400e] text-[11px] sm:text-[12px] font-semibold px-2.5 py-0.5 rounded-full">Menunggu Verifikasi</span>
                            @endif
                        </div>
                        <span class="text-[12px] text-[#64748b]">{{ $claim->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-[14px]">
                        <div>
                            <span class="text-[12px] text-[#64748b] block">Nama Pengklaim</span>
                            <span class="font-semibold text-[#0f172a]">{{ $claim->claimant_name }}</span>
                        </div>
                        <div>
                            <span class="text-[12px] text-[#64748b] block">No. HP Pengklaim</span>
                            <span class="font-semibold text-[#0f172a]">{{ $claim->claimant_phone }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-[12px] text-[#64748b] block mb-1">Ciri Khusus / Bukti Kepemilikan yang Diajukan:</span>
                        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-3 text-[13px] sm:text-[14px] text-[#0f172a]">
                            {{ $claim->proof_description }}
                        </div>
                    </div>

                    @if($claim->proof_photo_url)
                        <div>
                            <span class="text-[12px] text-[#64748b] block mb-1">Lampiran Foto Bukti:</span>
                            <a href="{{ $claim->proof_photo_url }}" target="_blank">
                                <img src="{{ $claim->proof_photo_url }}" alt="Bukti klaim" class="w-32 h-32 object-cover rounded-[10px] border border-[#e2e8f0] hover:opacity-90">
                            </a>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="pt-2 flex flex-wrap items-center justify-end gap-2 sm:gap-3 border-t border-[#e2e8f0]">
                        @if($claim->status === 'menunggu')
                            <form action="{{ route('admin.claim.cancel', $claim->claim_id) }}" method="POST" onsubmit="return confirm('Batalkan klaim ini?')">
                                @csrf
                                <button type="submit" class="bg-[#fee2e2] hover:bg-red-200 text-[#dc2626] font-semibold text-[13px] px-4 py-2 rounded-[8px] transition-colors cursor-pointer">
                                    Batalkan
                                </button>
                            </form>
                            <form action="{{ route('admin.claim.approve', $claim->claim_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[13px] px-5 py-2 rounded-[8px] transition-colors cursor-pointer shadow-2xs">
                                    Tindak Lanjuti &rarr;
                                </button>
                            </form>
                        @elseif($claim->status === 'ditindaklanjuti')
                            <a href="{{ route('admin.handover.create', $report->report_id) }}"
                               class="bg-[#16a34a] hover:bg-green-700 text-white font-semibold text-[13px] px-4 py-2 rounded-[8px] transition-colors shadow-2xs">
                                Form Serah Terima &rarr;
                            </a>
                            <form action="{{ route('admin.claim.cancel', $claim->claim_id) }}" method="POST" onsubmit="return confirm('Batalkan persetujuan klaim ini?')">
                                @csrf
                                <button type="submit" class="bg-[#fee2e2] hover:bg-red-200 text-[#dc2626] font-semibold text-[13px] px-4 py-2 rounded-[8px] transition-colors cursor-pointer">
                                    Batalkan Persetujuan
                                </button>
                            </form>
                        @else
                            <span class="text-[13px] text-[#94a3b8] italic">Klaim telah dibatalkan.</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-8 sm:p-12 text-center text-[#94a3b8] shadow-2xs">
                    Belum ada pengajuan klaim untuk barang ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
