@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Hero Banner -->
    <section class="bg-[#dbeafe] px-4 sm:px-6 lg:px-24 py-16 lg:py-20">
        <div class="max-w-5xl">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#0f172a] leading-tight mb-4">
                Barang hilang? Temukan kembali bersama Found &amp; Lost.
            </h1>
            <p class="text-base sm:text-lg text-[#64748b] max-w-2xl leading-relaxed mb-8">
                Laporkan barang hilang atau temuan tanpa perlu login. Satpam membantu memverifikasi klaim dan mengembalikan barang ke pemilik.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('report.create.hilang') }}" class="bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm px-5 py-3 rounded-[10px] transition-all shadow-xs">
                    Lapor Kehilangan
                </a>
                <a href="{{ route('report.create.ditemukan') }}" class="bg-[#f7f8fa] border border-[#2563eb] text-[#2563eb] hover:bg-blue-50 font-semibold text-sm px-5 py-3 rounded-[10px] transition-all">
                    Lapor Penemuan
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content / Body -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        <!-- Procedure Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Prosedur Kehilangan -->
            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 flex flex-col justify-between shadow-xs">
                <div>
                    <span class="text-[13px] text-[#64748b] block mb-1">Prosedur Operasional</span>
                    <h2 class="text-xl font-bold text-[#0f172a] mb-3">KEHILANGAN BARANG</h2>
                    <div class="text-sm text-[#64748b] space-y-1.5 leading-relaxed mb-6">
                        <p>Jika kamu kehilangan barang di lingkungan sekolah, lakukan langkah-langkah berikut:</p>
                        <ol class="list-decimal list-inside space-y-1 pl-1">
                            <li>Ingat kapan dan di mana terakhir kali kamu bersama barang tersebut.</li>
                            <li>Laporkan dengan mengisi formulir Lapor Barang Hilang.</li>
                            <li>Isi formulir dengan rinci, termasuk deskripsi barang, lokasi, dan waktu terakhir dilihat, serta kontak kamu agar Satpam dapat menghubungi lebih lanjut.</li>
                            <li>Simpan nomor pelacakan laporan, lalu tunggu informasi lanjutan apabila barang ditemukan.</li>
                        </ol>
                    </div>
                </div>
                <div>
                    <a href="{{ route('report.create.hilang') }}" class="inline-block bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] text-xs font-semibold px-4 py-2 rounded-[10px] transition-colors">
                        Lapor Kehilangan
                    </a>
                </div>
            </div>

            <!-- Prosedur Penemuan -->
            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 flex flex-col justify-between shadow-xs">
                <div>
                    <span class="text-[13px] text-[#64748b] block mb-1">Prosedur Operasional</span>
                    <h2 class="text-xl font-bold text-[#0f172a] mb-3">PENEMUAN BARANG</h2>
                    <div class="text-sm text-[#64748b] space-y-1.5 leading-relaxed mb-6">
                        <p>Jika kamu menemukan barang hilang di lingkungan sekolah, lakukan langkah-langkah berikut:</p>
                        <ol class="list-decimal list-inside space-y-1 pl-1">
                            <li>Amankan barang tersebut.</li>
                            <li>Laporkan dengan mengisi formulir Lapor Barang Ditemukan.</li>
                            <li>Isi formulir dengan rinci, termasuk deskripsi barang, lokasi, dan waktu penemuan, serta kontak kamu agar Satpam dapat menghubungi lebih lanjut.</li>
                            <li>Setelah dilaporkan, mohon kesediaannya untuk menyerahkan barang kepada Satpam.</li>
                        </ol>
                    </div>
                </div>
                <div>
                    <a href="{{ route('report.create.ditemukan') }}" class="inline-block bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] text-xs font-semibold px-4 py-2 rounded-[10px] transition-colors">
                        Lapor Penemuan
                    </a>
                </div>
            </div>
        </div>

        <!-- Catalog Header & Filter Bar -->
        <div class="space-y-4">
            <h2 class="text-xl font-bold text-[#0f172a]">Laporan Barang</h2>
            
            <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama barang atau lokasi..." 
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-4 py-2.5 text-sm text-[#0f172a] placeholder-[#64748b] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                </div>

                <!-- Hidden type input to preserve type when searching -->
                <input type="hidden" name="type" value="{{ $type }}">

                <!-- Type Filter Chips -->
                <div class="flex gap-2">
                    <a href="{{ route('home', array_merge(request()->except('type'), ['type' => 'semua'])) }}" 
                       class="px-4 py-2 rounded-full text-xs font-medium transition-all {{ $type === 'semua' ? 'bg-[#2563eb] text-[#f7f8fa]' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:border-slate-300' }}">
                        Semua
                    </a>
                    <a href="{{ route('home', array_merge(request()->except('type'), ['type' => 'hilang'])) }}" 
                       class="px-4 py-2 rounded-full text-xs font-medium transition-all {{ $type === 'hilang' ? 'bg-[#2563eb] text-[#f7f8fa]' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:border-slate-300' }}">
                        Hilang
                    </a>
                    <a href="{{ route('home', array_merge(request()->except('type'), ['type' => 'ditemukan'])) }}" 
                       class="px-4 py-2 rounded-full text-xs font-medium transition-all {{ $type === 'ditemukan' ? 'bg-[#2563eb] text-[#f7f8fa]' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:border-slate-300' }}">
                        Ditemukan
                    </a>
                </div>
            </form>
        </div>

        <!-- Reports Grid -->
        @if ($reports->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($reports as $report)
                    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[14px] p-3 flex flex-col justify-between hover:shadow-md transition-shadow">
                        <!-- Top Info -->
                        <div>
                            <!-- Photo / Placeholder -->
                            <div class="bg-[#e2e8f0] rounded-[12px] h-[160px] overflow-hidden flex items-center justify-center relative mb-3">
                                @if ($report->photo_url)
                                    <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex flex-col items-center justify-center text-[#94a3b8] text-xs font-medium">
                                        <svg class="w-8 h-8 mb-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Foto Barang
                                    </div>
                                @endif
                            </div>

                            <!-- Badges -->
                            <div class="flex flex-wrap gap-1.5 mb-2.5">
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

                            <!-- Item Title -->
                            <h3 class="font-semibold text-base text-[#0f172a] truncate mb-1">
                                {{ $report->item_name }}
                            </h3>

                            <!-- Location & Date -->
                            <p class="text-[13px] text-[#64748b] mb-3">
                                {{ $report->location }} &middot; {{ $report->incident_date->isoFormat('D MMM Y') }}
                            </p>
                        </div>

                        <!-- Card Action Button -->
                        <div class="pt-2">
                            <a href="{{ route('report.show', ['code' => $report->code]) }}" class="w-full bg-[#f7f8fa] border border-[#2563eb] text-[#2563eb] hover:bg-blue-50 text-xs font-semibold py-2 px-3.5 rounded-[10px] flex items-center justify-center transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $reports->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] py-16 px-4 text-center">
                <svg class="w-12 h-12 text-[#94a3b8] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-lg font-bold text-[#0f172a] mb-1">Belum Ada Laporan</h3>
                <p class="text-sm text-[#64748b] max-w-sm mx-auto mb-6">Tidak ditemukan laporan sesuai filter pencarian yang kamu pilih.</p>
                <a href="{{ route('home') }}" class="inline-block bg-[#2563eb] text-white text-xs font-semibold px-4 py-2.5 rounded-[10px]">
                    Reset Filter
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
