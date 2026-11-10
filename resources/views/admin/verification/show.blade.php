@extends('layouts.admin')

@section('title', 'Tinjau Verifikasi Serah Terima - Admin')

@section('content')
@php
    $report = $handover->report;
    $claim = $handover->claim;
@endphp

<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Top Bar Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.verification.index') }}"
           class="inline-flex items-center text-xs sm:text-sm font-semibold text-[#64748b] hover:text-[#059669] transition-colors">
            &larr; Kembali ke Daftar Verifikasi
        </a>

        <div class="inline-flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                Menunggu Verifikasi Admin
            </span>
        </div>
    </div>

    <!-- Header Banner -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="text-xs font-bold text-[#059669] tracking-wider uppercase block mb-1">{{ $report->code }}</span>
                <h1 class="text-xl sm:text-2xl font-bold text-[#0f172a]">{{ $report->item_name }}</h1>
                <p class="text-xs sm:text-sm text-[#64748b] mt-1">
                    Diajukan oleh Satpam <strong class="text-[#0f172a]">{{ $handover->admin->name ?? 'Petugas Satpam' }}</strong> pada {{ \Carbon\Carbon::parse($handover->handover_date)->format('d F Y, H:i') }} WIB
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if($report->type === 'hilang')
                    <span class="bg-[#fee2e2] text-[#dc2626] text-xs font-bold px-3 py-1.5 rounded-full">Barang Hilang</span>
                @else
                    <span class="bg-[#dbeafe] text-[#2563eb] text-xs font-bold px-3 py-1.5 rounded-full">Barang Ditemukan</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Grid: Barang & Serah Terima -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kolom Kiri: Informasi Laporan Asli -->
        <div class="space-y-6">
            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
                <h2 class="text-base font-bold text-[#0f172a] mb-4 pb-2 border-b border-[#e2e8f0] flex items-center gap-2">
                    <svg class="size-5 text-[#64748b]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Informasi Barang
                </h2>

                <!-- Foto Barang -->
                <div class="mb-5">
                    <p class="text-xs font-semibold text-[#64748b] mb-2 uppercase">Foto Barang yang Dilaporkan</p>
                    @if($report->photo_url)
                        <a href="{{ $report->photo_url }}" target="_blank" title="Klik untuk memperbesar" class="block group relative rounded-xl overflow-hidden border border-[#e2e8f0] bg-white">
                            <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full h-56 object-contain group-hover:scale-102 transition-transform duration-200">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                Buka Foto Asli
                            </div>
                        </a>
                    @else
                        <div class="h-40 rounded-xl bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-[#64748b]">
                            <svg class="size-8 text-slate-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs">Tidak ada foto barang yang diunggah</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-3.5 text-xs sm:text-sm">
                    <div>
                        <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Deskripsi Barang</span>
                        <p class="text-[#0f172a] font-medium mt-0.5 leading-relaxed">{{ $report->description }}</p>
                    </div>

                    @if($report->special_features)
                        <div>
                            <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Ciri-Ciri Khusus</span>
                            <p class="text-[#0f172a] font-medium mt-0.5 leading-relaxed">{{ $report->special_features }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Lokasi Kejadian</span>
                            <p class="text-[#0f172a] font-semibold mt-0.5">{{ $report->location }}</p>
                        </div>
                        <div>
                            <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Tanggal Kejadian</span>
                            <p class="text-[#0f172a] font-semibold mt-0.5">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#e2e8f0]">
                        <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Pelapor Awal</span>
                        <p class="text-[#0f172a] font-semibold mt-0.5">{{ $report->reporter_name }} ({{ $report->reporter_phone }})</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pengajuan Serah Terima Satpam & Form Keputusan -->
        <div class="space-y-6">
            <!-- Bukti Serah Terima -->
            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 shadow-2xs">
                <h2 class="text-base font-bold text-[#0f172a] mb-4 pb-2 border-b border-[#e2e8f0] flex items-center gap-2">
                    <svg class="size-5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Data Serah Terima oleh Satpam
                </h2>

                <!-- Foto Bukti Serah Terima -->
                <div class="mb-5">
                    <p class="text-xs font-semibold text-[#0f172a] mb-2 uppercase flex items-center justify-between">
                        <span>Foto Bukti Penyerahan Barang</span>
                        @if($handover->proof_photo_url)
                            <span class="text-emerald-600 text-[11px] font-semibold flex items-center gap-1">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Bukti Terlampir
                            </span>
                        @endif
                    </p>

                    @if($handover->proof_photo_url)
                        <a href="{{ $handover->proof_photo_url }}" target="_blank" title="Klik untuk melihat resolusi penuh" class="block group relative rounded-xl overflow-hidden border border-emerald-300 bg-white shadow-2xs">
                            <img src="{{ $handover->proof_photo_url }}" alt="Bukti Serah Terima" class="w-full h-56 object-cover group-hover:scale-102 transition-transform duration-200">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                Buka Foto Resolusi Penuh
                            </div>
                        </a>
                    @else
                        <div class="h-40 rounded-xl bg-amber-50 border border-amber-200 flex flex-col items-center justify-center text-amber-700">
                            <svg class="size-8 text-amber-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-xs font-medium">Petugas tidak melampirkan foto bukti</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-3.5 text-xs sm:text-sm">
                    <div class="grid grid-cols-2 gap-3 bg-white p-3.5 rounded-xl border border-[#e2e8f0]">
                        <div>
                            <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Penerima Barang</span>
                            <p class="text-[#0f172a] font-bold mt-0.5 text-sm">{{ $handover->recipient_name }}</p>
                        </div>
                        <div>
                            <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Kontak / No. HP</span>
                            <p class="text-[#0f172a] font-bold mt-0.5 text-sm">{{ $handover->recipient_contact }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-3.5 rounded-xl border border-[#e2e8f0]">
                        <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Catatan Petugas Satpam</span>
                        <p class="text-[#0f172a] font-medium mt-1 leading-relaxed">
                            {{ $handover->notes ?: 'Tidak ada catatan tambahan dari petugas.' }}
                        </p>
                    </div>

                    <div class="bg-white p-3.5 rounded-xl border border-[#e2e8f0]">
                        <span class="text-[#64748b] block text-[11px] font-semibold uppercase">Petugas Satpam Pelaksana</span>
                        <p class="text-[#0f172a] font-bold mt-0.5">{{ $handover->admin->name ?? 'Satpam' }}</p>
                        <p class="text-[11px] text-[#64748b]">{{ $handover->admin->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Box: Keputusan Admin -->
            <div class="bg-white border-2 border-slate-300 rounded-[16px] p-5 sm:p-6 shadow-sm">
                <h3 class="text-sm font-bold text-[#0f172a] mb-1">Keputusan Verifikasi Admin</h3>
                <p class="text-xs text-[#64748b] mb-4">
                    Pilih untuk menyetujui serah terima ini (laporan selesai & masuk history) atau tolak untuk mengembalikan laporan ke satpam.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Form Approve -->
                    <form action="{{ route('admin.verification.approve', $handover->handover_id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI serah terima barang ini? Status laporan akan resmi SELESAI dan masuk ke History.')">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-[#059669] hover:bg-[#047857] text-white font-bold py-3 px-4 rounded-[12px] transition-all shadow-xs cursor-pointer text-sm">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Approve (Setujui)</span>
                        </button>
                    </form>

                    <!-- Tombol Pemicu Modal Tolak -->
                    <button type="button" onclick="openRejectModal()"
                            class="w-full inline-flex items-center justify-center gap-2 bg-[#dc2626] hover:bg-red-700 text-white font-bold py-3 px-4 rounded-[12px] transition-all shadow-xs cursor-pointer text-sm">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Tolak Laporan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak Laporan -->
<div id="reject-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[20px] max-w-md w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="text-base font-bold text-[#dc2626] flex items-center gap-2">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Tolak Pengajuan Serah Terima
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <p class="text-xs text-[#64748b] leading-relaxed mb-4">
            Laporan ini akan <strong>dikembalikan ke halaman Serah Terima Satpam</strong>. Harap berikan alasan penolakan agar Satpam dapat memperbaiki data atau bukti penyerahan barang.
        </p>

        <form action="{{ route('admin.verification.reject', $handover->handover_id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-[#0f172a] mb-1.5 uppercase">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" rows="4" required placeholder="Contoh: Foto bukti buram, nama penerima tidak sesuai identitas pemilik asli, bukti kepemilikan kurang jelas..."
                          class="w-full bg-[#f8fafc] border border-slate-300 rounded-[10px] p-3 text-sm text-[#0f172a] focus:outline-none focus:border-red-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2.5 rounded-[10px] text-xs font-semibold text-[#64748b] hover:bg-slate-100 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-[10px] text-xs font-bold bg-[#dc2626] hover:bg-red-700 text-white transition-colors cursor-pointer shadow-xs">
                    Konfirmasi Tolak &amp; Kembalikan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal() {
        document.getElementById('reject-modal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').classList.add('hidden');
    }
</script>
@endsection
