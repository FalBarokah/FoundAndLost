@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Back Link -->
    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-[#2563eb] hover:underline">
        &larr; Kembali ke beranda
    </a>

    <!-- Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Gallery / Photo Card -->
        <div class="lg:col-span-6 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-3 shadow-xs">
            <div class="bg-[#e2e8f0] rounded-[12px] h-[340px] sm:h-[420px] overflow-hidden flex items-center justify-center">
                @if ($report->photo_url)
                    <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full h-full object-cover">
                @else
                    <div class="flex flex-col items-center justify-center text-[#94a3b8]">
                        <svg class="w-16 h-16 mb-2 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Foto barang tidak tersedia</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="lg:col-span-6 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 sm:p-7 space-y-5 shadow-xs">
            <!-- Badges -->
            <div class="flex flex-wrap gap-2">
                @if ($report->type === 'hilang')
                    <span class="bg-[#fee2e2] text-[#dc2626] text-xs font-semibold px-2.5 py-1 rounded-full">
                        Hilang
                    </span>
                @else
                    <span class="bg-[#dcfce7] text-[#16a34a] text-xs font-semibold px-2.5 py-1 rounded-full">
                        Ditemukan
                    </span>
                @endif

                @if ($report->status === 'proses_pencarian')
                    <span class="bg-[#fef3c7] text-[#d97706] text-xs font-semibold px-2.5 py-1 rounded-full">
                        Proses Pencarian
                    </span>
                @elseif ($report->status === 'siap_diambil')
                    <span class="bg-[#dbeafe] text-[#2563eb] text-xs font-semibold px-2.5 py-1 rounded-full">
                        Siap Diambil
                    </span>
                @else
                    <span class="bg-[#e2e8f0] text-[#475569] text-xs font-semibold px-2.5 py-1 rounded-full">
                        Selesai
                    </span>
                @endif
            </div>

            <!-- Title & Description -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-[#0f172a] mb-2">{{ $report->item_name }}</h1>
                <p class="text-sm text-[#64748b] leading-relaxed">{{ $report->description }}</p>
            </div>

            <!-- Meta Rows -->
            <div class="divide-y divide-[#e2e8f0] text-sm pt-2">
                <div class="flex py-2.5">
                    <span class="w-36 text-[#64748b]">Lokasi</span>
                    <span class="flex-1 font-medium text-[#0f172a]">{{ $report->location }}</span>
                </div>
                <div class="flex py-2.5">
                    <span class="w-36 text-[#64748b]">Tanggal Kejadian</span>
                    <span class="flex-1 font-medium text-[#0f172a]">{{ $report->incident_date->isoFormat('D MMMM Y') }}</span>
                </div>
                <div class="flex py-2.5">
                    <span class="w-36 text-[#64748b]">Ciri Khusus</span>
                    <span class="flex-1 font-medium text-[#0f172a]">
                        @if ($report->type === 'ditemukan')
                            <span class="italic text-slate-500">Disembunyikan (verifikasi kepemilikan)</span>
                        @else
                            {{ $report->special_features ?: '-' }}
                        @endif
                    </span>
                </div>
                <div class="flex py-2.5">
                    <span class="w-36 text-[#64748b]">
                        {{ $report->type === 'ditemukan' ? 'Ditemukan oleh' : 'Pelapor' }}
                    </span>
                    <span class="flex-1 font-medium text-[#0f172a]">{{ $report->reporter_name }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-4">
                @if ($report->type === 'ditemukan' && $report->status !== 'selesai')
                    <a href="{{ route('claim.create', ['code' => $report->code]) }}" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm py-3 px-4 rounded-[10px] flex items-center justify-center transition-colors shadow-xs">
                        Ajukan Klaim
                    </a>
                @elseif ($report->type === 'hilang' && $report->status === 'siap_diambil')
                    <div class="bg-[#dbeafe] border border-[#93c5fd] rounded-xl p-4 text-xs text-[#1e40af] space-y-1">
                        <p class="font-bold">Barang siap diambil!</p>
                        <p>Silakan datang ke Pos Satpam dengan membawa identitas dan menunjukkan Kode Lacak laporan Anda.</p>
                    </div>
                @elseif ($report->status === 'selesai')
                    <div class="bg-[#e2e8f0] rounded-xl p-4 text-xs text-[#475569] space-y-1 text-center font-medium">
                        Laporan ini telah selesai diserahterimakan kepada pemilik sah.
                    </div>
                @else
                    <div class="text-xs text-[#64748b] bg-slate-100 p-3 rounded-lg text-center">
                        Barang sedang dalam proses pencarian. Pantau perkembangan secara berkala dengan Kode Lacak.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
