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

            <form id="update-form" action="{{ route('report.update', $report->code) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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

                <!-- Ganti / Upload Foto Baru -->
                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">
                        Ganti Foto Barang (opsional)
                    </label>
                    @if($report->photo_url)
                        <p class="text-[12px] text-[#64748b] mb-2">Saat ini laporan sudah memiliki foto. Unggah di bawah jika ingin menggantinya dengan foto baru.</p>
                    @endif
                    <div class="border border-[#2563eb] border-dashed bg-[#edf0f4] rounded-[12px] p-5 text-center transition-colors relative">
                        <input type="file" name="photo" id="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)">
                        <div id="preview-container" class="space-y-1 pointer-events-none">
                            <svg class="w-7 h-7 text-[#2563eb] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm font-semibold text-[#2563eb]">Pilih foto baru untuk mengganti</p>
                            <p class="text-xs text-[#64748b]">JPG atau PNG, maks. 2 MB</p>
                        </div>
                        <div id="preview-wrapper" class="hidden relative z-10 flex flex-col items-center">
                            <img id="image-preview" class="max-h-44 rounded-lg object-contain border border-[#e2e8f0] bg-white shadow-xs" alt="Preview Foto Baru">
                            <p id="file-name" class="text-xs text-[#0f172a] font-medium mt-1.5"></p>
                            <button type="button" onclick="cancelImage()" class="mt-2.5 bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Batalkan Pilihan Foto
                            </button>
                        </div>
                    </div>
                    @error('photo')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="pt-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button type="button" onclick="confirmDelete()"
                            class="w-full sm:w-auto bg-[#dc2626] hover:bg-red-700 text-white font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors cursor-pointer">
                        Hapus Laporan
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors cursor-pointer">
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
    function previewImage(input) {
        const file = input.files[0];
        const container = document.getElementById('preview-container');
        const wrapper = document.getElementById('preview-wrapper');
        const preview = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');

        if (file) {
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.add('hidden');
                wrapper.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function cancelImage() {
        const input = document.getElementById('photo');
        const container = document.getElementById('preview-container');
        const wrapper = document.getElementById('preview-wrapper');
        const preview = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');

        input.value = '';
        preview.src = '';
        fileName.textContent = '';
        wrapper.classList.add('hidden');
        container.classList.remove('hidden');
    }

    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endsection
