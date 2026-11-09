@extends('layouts.admin')

@section('title', 'Detail Laporan ' . $report->code . ' - Admin')
@section('header', 'Detail Laporan')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('admin.dashboard', ['type' => 'hilang']) }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline">
        &larr; Kembali ke dashboard
    </a>

    <!-- Page Heading -->
    <div>
        <h1 class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mb-1">Detail Laporan</h1>
        <p class="text-xs sm:text-[15px] text-[#64748b]">
            {{ $report->code }} &middot; Barang Hilang. Ubah status laporan sesuai kondisi.
        </p>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Photo -->
        <div class="lg:col-span-5 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 flex flex-col items-center justify-center shadow-2xs">
            @if($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full max-h-[360px] object-cover rounded-[12px] border border-[#e2e8f0]">
            @else
                <div class="w-full h-64 sm:h-[320px] bg-[#e2e8f0] rounded-[12px] flex items-center justify-center text-[#94a3b8] text-[14px] font-medium">
                    Foto {{ $report->item_name }}
                </div>
            @endif
        </div>

        <!-- Right: Info Card -->
        <div class="lg:col-span-7 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-8 flex flex-col gap-5 shadow-2xs">
            <div class="flex items-center gap-2">
                <span class="bg-[#fee2e2] text-[#991b1b] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                @if($report->status === 'proses_pencarian')
                    <span class="bg-[#fef3c7] text-[#92400e] text-[12px] font-semibold px-2.5 py-1 rounded-full">Proses Pencarian</span>
                @elseif($report->status === 'siap_diambil')
                    <span class="bg-[#dcfce7] text-[#16a34a] text-[12px] font-semibold px-2.5 py-1 rounded-full">Siap Diambil</span>
                @else
                    <span class="bg-[#f1f5f9] text-[#475569] text-[12px] font-semibold px-2.5 py-1 rounded-full">Selesai</span>
                @endif
            </div>

            <h2 class="text-xl sm:text-[22px] font-bold text-[#0f172a]">{{ $report->item_name }}</h2>

            <div class="border-t border-[#e2e8f0] pt-3 flex flex-col gap-2.5 text-[14px]">
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Lokasi terakhir</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->location }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Tanggal kehilangan</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Pelapor</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->reporter_name }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">No. HP</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->reporter_phone }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Deskripsi</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->description }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px] sm:w-36">Ciri-ciri khusus</span>
                    <span class="text-[#0f172a] font-medium sm:text-right flex-1">{{ $report->special_features ?? '-' }}</span>
                </div>
            </div>

            <!-- Status Selector -->
            <div class="border-t border-[#e2e8f0] pt-4">
                <p class="text-[14px] font-semibold text-[#0f172a] mb-2">Status Laporan</p>

                <div class="flex flex-wrap gap-2 items-center mb-2">
                    <!-- Form for switching between 'proses_pencarian' and 'siap_diambil' -->
                    <form action="{{ route('admin.report.status', $report->report_id) }}" method="POST" class="inline-flex flex-wrap gap-2">
                        @csrf
                        @if($report->status === 'proses_pencarian')
                            <button type="button" class="bg-[#2563eb] text-[#f7f8fa] text-[13px] font-medium px-4 py-2 rounded-full cursor-default">
                                Proses Pencarian
                            </button>
                            <button type="submit" name="status" value="siap_diambil"
                                    class="bg-[#f7f8fa] border border-[#e2e8f0] hover:bg-slate-200 text-[#0f172a] text-[13px] font-medium px-4 py-2 rounded-full transition-colors cursor-pointer">
                                Siap Diambil
                            </button>
                        @elseif($report->status === 'siap_diambil')
                            <button type="submit" name="status" value="proses_pencarian"
                                    class="bg-[#f7f8fa] border border-[#e2e8f0] hover:bg-slate-200 text-[#0f172a] text-[13px] font-medium px-4 py-2 rounded-full transition-colors cursor-pointer">
                                Proses Pencarian
                            </button>
                            <button type="button" class="bg-[#2563eb] text-[#f7f8fa] text-[13px] font-medium px-4 py-2 rounded-full cursor-default">
                                Siap Diambil
                            </button>
                        @else
                            <button type="button" class="bg-[#2563eb] text-[#f7f8fa] text-[13px] font-medium px-4 py-2 rounded-full cursor-default">
                                Selesai
                            </button>
                        @endif
                    </form>

                    <!-- Link to Serah Terima (opens Form Serah Terima which sets status to Selesai) -->
                    @if($report->status !== 'selesai')
                        <a href="{{ route('admin.handover.create', $report->report_id) }}"
                           class="bg-[#f7f8fa] border border-[#e2e8f0] hover:bg-[#16a34a] hover:text-white text-[#0f172a] text-[13px] font-medium px-4 py-2 rounded-full transition-colors">
                            Selesai &rarr;
                        </a>
                    @endif
                </div>

                <p class="text-[12px] text-[#64748b]">
                    Memilih Selesai membuka Form Serah Terima. Laporan baru pindah ke History setelah laporan serah terima dibuat.
                </p>
            </div>

            <!-- Delete Button -->
            <div class="border-t border-[#e2e8f0] pt-4">
                <form action="{{ route('admin.report.destroy', $report->report_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-[#dc2626] hover:bg-red-700 text-white font-semibold text-[14px] py-3 rounded-[10px] transition-colors cursor-pointer">
                        Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
