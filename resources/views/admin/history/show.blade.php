@extends('layouts.admin')

@section('title', 'Detail Arsip ' . $report->code . ' - Admin')
@section('header', 'Detail History')

@section('content')
@php
    $handover = $report->handover;
@endphp

<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('admin.history.index') }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline">
        &larr; Kembali ke History
    </a>

    <!-- Page Heading -->
    <div>
        <h1 class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mb-1">Detail Arsip Laporan</h1>
        <p class="text-xs sm:text-[15px] text-[#64748b]">
            {{ $report->code }} &middot; {{ $report->item_name }}. Laporan telah diselesaikan dan diarsipkan.
        </p>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Detail Barang -->
        <div class="lg:col-span-6 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 flex flex-col gap-4 shadow-2xs">
            <div class="flex items-center gap-2">
                @if($report->type === 'hilang')
                    <span class="bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                @else
                    <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                @endif
                <span class="bg-[#f1f5f9] text-[#475569] text-[12px] font-semibold px-2.5 py-1 rounded-full">Selesai</span>
            </div>

            <h2 class="text-lg sm:text-[20px] font-bold text-[#0f172a]">{{ $report->item_name }}</h2>

            @if($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full max-h-[240px] object-cover rounded-[12px] border border-[#e2e8f0]">
            @else
                <div class="w-full h-56 sm:h-[240px] bg-[#e2e8f0] rounded-[12px] flex items-center justify-center text-[#94a3b8] text-[13px] font-medium">
                    Foto Barang
                </div>
            @endif

            <div class="border-t border-[#e2e8f0] pt-3 flex flex-col gap-2.5 text-[14px]">
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Lokasi</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->location }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Tanggal Kejadian</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Pelapor Awal</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->reporter_name }} ({{ $report->reporter_phone }})</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Deskripsi</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->description }}</span>
                </div>
                @if($report->special_features)
                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                        <span class="text-[#64748b] text-[13px] sm:w-36">Ciri Khusus</span>
                        <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->special_features }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Berita Acara Serah Terima -->
        <div class="lg:col-span-6 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-8 flex flex-col gap-5 shadow-2xs">
            <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3">
                <h3 class="text-base sm:text-[18px] font-bold text-[#0f172a]">Berita Acara Serah Terima</h3>
                <span class="bg-[#dcfce7] text-[#166534] text-[12px] font-semibold px-2.5 py-1 rounded-full">
                    Terverifikasi
                </span>
            </div>

            @if($handover)
                <div class="space-y-4 text-[14px]">
                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-[#e2e8f0] gap-0.5 sm:gap-2">
                        <span class="text-[#64748b] text-[13px]">Nama Penerima</span>
                        <span class="text-[#0f172a] font-semibold sm:text-right">{{ $handover->recipient_name }}</span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-[#e2e8f0] gap-0.5 sm:gap-2">
                        <span class="text-[#64748b] text-[13px]">Kontak / Identitas</span>
                        <span class="text-[#0f172a] font-medium sm:text-right">{{ $handover->recipient_contact }}</span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-[#e2e8f0] gap-0.5 sm:gap-2">
                        <span class="text-[#64748b] text-[13px]">Tanggal &amp; Waktu</span>
                        <span class="text-[#0f172a] font-medium sm:text-right">
                            {{ $handover->handover_date ? \Carbon\Carbon::parse($handover->handover_date)->format('d F Y, H:i') : '-' }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-[#e2e8f0] gap-0.5 sm:gap-2">
                        <span class="text-[#64748b] text-[13px]">Petugas Admin/Satpam</span>
                        <span class="text-[#0f172a] font-medium sm:text-right">{{ $handover->admin?->name ?? 'Admin Satpam' }}</span>
                    </div>

                    <div>
                        <span class="text-[#64748b] text-[13px] block mb-1">Catatan Serah Terima</span>
                        <div class="bg-white border border-[#e2e8f0] rounded-[10px] p-3 text-[#0f172a]">
                            {{ $handover->notes ?: 'Tidak ada catatan tambahan.' }}
                        </div>
                    </div>

                    @if($handover->proof_photo_url)
                        <div>
                            <span class="text-[#64748b] text-[13px] block mb-1">Dokumentasi Foto Serah Terima</span>
                            <a href="{{ $handover->proof_photo_url }}" target="_blank">
                                <img src="{{ $handover->proof_photo_url }}" alt="Dokumentasi serah terima"
                                     class="w-full max-h-[220px] object-cover rounded-[12px] border border-[#e2e8f0] hover:opacity-95 transition-opacity">
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-[#64748b] text-[14px]">Data serah terima tidak tersedia.</p>
            @endif
        </div>
    </div>
</div>
@endsection
