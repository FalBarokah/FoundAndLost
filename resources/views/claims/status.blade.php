@extends('layouts.app')

@section('title', 'Status Klaim - ' . $claim->claim_code)

@section('content')
<div class="max-w-[1200px] mx-auto px-4 md:px-0 py-8">
    <!-- Back Link -->
    <a href="{{ route('report.check', ['tab' => 'klaim']) }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline mb-6">
        &larr; Kembali ke Cek Klaim
    </a>

    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-[28px] md:text-[32px] font-bold text-[#0f172a] mb-1">Status Klaim</h1>
        <p class="text-[15px] md:text-[16px] text-[#64748b]">
            Nomor klaim: <span class="font-semibold text-[#0f172a]">{{ $claim->claim_code }}</span>. Data ini hanya untuk dilihat.
        </p>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        <!-- Left: Ringkasan Item -->
        <div class="md:col-span-5 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="bg-[#e0f2fe] text-[#075985] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                <span class="bg-[#dbeafe] text-[#1e40af] text-[12px] font-semibold px-2.5 py-1 rounded-full">{{ $claim->report->status_label }}</span>
            </div>

            <h2 class="text-[20px] font-bold text-[#0f172a] leading-tight">{{ $claim->report->item_name }}</h2>

            @if($claim->report->photo_url)
                <img src="{{ $claim->report->photo_url }}" alt="{{ $claim->report->item_name }}" class="w-full h-[200px] object-cover rounded-[12px] border border-[#e2e8f0]">
            @else
                <div class="w-full h-[200px] bg-[#e2e8f0] rounded-[12px] flex items-center justify-center text-[#94a3b8] text-[13px] font-medium">
                    Foto Barang
                </div>
            @endif

            <div class="border-t border-[#e2e8f0] pt-4 flex flex-col gap-2.5 text-[14px]">
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Lokasi ditemukan</span>
                    <span class="text-[#0f172a] font-medium text-right">{{ $claim->report->location }}</span>
                </div>
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Tanggal ditemukan</span>
                    <span class="text-[#0f172a] font-medium text-right">{{ \Carbon\Carbon::parse($claim->report->incident_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Kode Laporan</span>
                    <a href="{{ route('report.show', $claim->report->code) }}" class="text-[#2563eb] hover:underline font-semibold text-right">{{ $claim->report->code }}</a>
                </div>
            </div>
        </div>

        <!-- Right: Status Klaim Card -->
        <div class="md:col-span-7 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 md:p-8 flex flex-col gap-5">
            <h3 class="text-[18px] font-bold text-[#0f172a]">Status Klaim</h3>

            <div>
                @if($claim->status === 'ditindaklanjuti')
                    <div class="inline-flex bg-[#dcfce7] text-[#166534] text-[12px] font-semibold px-3 py-1.5 rounded-full mb-3">
                        Ditindaklanjuti
                    </div>
                    <p class="text-[14px] text-[#0f172a] leading-relaxed bg-[#f0fdf4] border border-[#bbf7d0] rounded-[10px] p-4">
                        Klaimmu disetujui. Datangi Pos Satpam dengan membawa kartu identitas dan bukti kepemilikan untuk mengambil barang.
                    </p>
                @elseif($claim->status === 'dibatalkan')
                    <div class="inline-flex bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-3 py-1.5 rounded-full mb-3">
                        Dibatalkan
                    </div>
                    <p class="text-[14px] text-[#0f172a] leading-relaxed bg-[#fef2f2] border border-[#fecaca] rounded-[10px] p-4">
                        Klaim ini dibatalkan karena data kepemilikan tidak sesuai atau barang telah diserahkan kepada pemilik yang sah.
                    </p>
                @else
                    <div class="inline-flex bg-[#fef3c7] text-[#92400e] text-[12px] font-semibold px-3 py-1.5 rounded-full mb-3">
                        Menunggu Verifikasi
                    </div>
                    <p class="text-[14px] text-[#0f172a] leading-relaxed bg-[#fffbeb] border border-[#fde68a] rounded-[10px] p-4">
                        Klaimmu sedang ditinjau oleh petugas satpam. Kami akan menghubungi nomor kamu jika data bukti sudah cocok.
                    </p>
                @endif
            </div>

            <div class="border-t border-[#e2e8f0] pt-4 flex flex-col gap-3 text-[14px]">
                <div class="flex justify-between items-center py-1">
                    <span class="text-[#64748b] text-[13px]">Nomor klaim</span>
                    <span class="text-[#0f172a] font-semibold">{{ $claim->claim_code }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-[#64748b] text-[13px]">Diajukan</span>
                    <span class="text-[#0f172a] font-medium">{{ $claim->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-[#64748b] text-[13px]">Nama Pengklaim</span>
                    <span class="text-[#0f172a] font-medium">{{ $claim->claimant_name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
