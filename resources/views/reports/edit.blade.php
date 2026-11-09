@extends('layouts.app')

@section('title', 'Edit Laporan - ' . $report->code)

@section('content')
<div class="max-w-[1200px] mx-auto px-4 md:px-0 py-8">
    <!-- Back Link -->
    <a href="{{ route('report.check', ['tab' => 'edit']) }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline mb-6">
        &larr; Kembali ke Edit Laporan
    </a>

    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-[28px] md:text-[32px] font-bold text-[#0f172a] mb-1">Edit Laporan</h1>
        <p class="text-[15px] md:text-[16px] text-[#64748b]">
            Kode Edit: <span class="font-semibold text-[#0f172a]">{{ $report->edit_code }}</span>. Ubah data atau hapus laporanmu.
        </p>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        <!-- Left: Ringkasan -->
        <div class="md:col-span-5 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 flex flex-col gap-4">
            <div class="flex items-center gap-2">
                @if($report->type === 'hilang')
                    <span class="bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                @else
                    <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                @endif

                @if($report->status === 'proses_pencarian')
                    <span class="bg-[#fef3c7] text-[#92400e] text-[12px] font-semibold px-2.5 py-1 rounded-full">Proses Pencarian</span>
                @elseif($report->status === 'siap_diambil')
                    <span class="bg-[#dcfce7] text-[#16a34a] text-[12px] font-semibold px-2.5 py-1 rounded-full">Siap Diambil</span>
                @else
                    <span class="bg-[#f1f5f9] text-[#475569] text-[12px] font-semibold px-2.5 py-1 rounded-full">Selesai</span>
                @endif
            </div>

            <h2 class="text-[20px] font-bold text-[#0f172a] leading-tight">{{ $report->item_name }}</h2>

            @if($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full h-[200px] object-cover rounded-[12px] border border-[#e2e8f0]">
            @else
                <div class="w-full h-[200px] bg-[#e2e8f0] rounded-[12px] flex items-center justify-center text-[#94a3b8] text-[13px] font-medium">
                    Foto Barang
                </div>
            @endif

            <div class="border-t border-[#e2e8f0] pt-4 flex flex-col gap-2.5 text-[14px]">
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Tanggal</span>
                    <span class="text-[#0f172a] font-medium text-right">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Lokasi</span>
                    <span class="text-[#0f172a] font-medium text-right">{{ $report->location }}</span>
                </div>
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Status (diubah admin)</span>
                    <span class="text-[#0f172a] font-medium text-right">{{ $report->status_label }}</span>
                </div>
                <div class="flex justify-between items-start py-1">
                    <span class="text-[#64748b] text-[13px]">Kode Lacak</span>
                    <span class="text-[#2563eb] font-semibold text-right">{{ $report->code }}</span>
                </div>
            </div>
        </div>

        <!-- Right: Form Card -->
        <div class="md:col-span-7 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 md:p-8">
            <h3 class="text-[18px] font-semibold text-[#0f172a] mb-6">Edit Data Laporan</h3>

            <form id="update-form" action="{{ route('report.update', $report->code) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_code" value="{{ $report->edit_code }}">

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Nama Barang</label>
                    <input type="text" name="item_name" value="{{ old('item_name', $report->item_name) }}" required
                           class="w-full bg-[#f7f8fa] border @error('item_name') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    @error('item_name')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $report->location) }}" required
                           class="w-full bg-[#f7f8fa] border @error('location') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    @error('location')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">No. HP</label>
                    <input type="tel" inputmode="numeric" pattern="[0-9]{10,13}" maxlength="13" name="reporter_phone" value="{{ old('reporter_phone', $report->reporter_phone) }}" required
                           placeholder="Contoh: 081234567890 (10 - 13 angka)"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                           class="w-full bg-[#f7f8fa] border @error('reporter_phone') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    <p class="text-[11px] text-[#64748b] mt-1">Hanya angka (10 sampai 13 digit).</p>
                    @error('reporter_phone')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" required
                              class="w-full bg-[#f7f8fa] border @error('description') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">{{ old('description', $report->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Ciri-ciri Khusus (opsional)</label>
                    <textarea name="special_features" rows="2"
                              class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">{{ old('special_features', $report->special_features) }}</textarea>
                </div>

                <!-- Actions -->
                <div class="pt-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button type="button" onclick="confirmDelete()"
                            class="w-full sm:w-auto bg-[#dc2626] hover:bg-red-700 text-white font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors">
                        Hapus Laporan
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <!-- Separate Hidden Delete Form -->
            <form id="delete-form" action="{{ route('report.destroy', $report->code) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <input type="hidden" name="edit_code" value="{{ $report->edit_code }}">
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endsection
