@extends('layouts.app')

@section('title', 'Ajukan Klaim Barang - ' . $report->item_name)

@section('content')
<div class="max-w-[800px] mx-auto px-4 md:px-0 py-8">
    <!-- Back Link -->
    <a href="{{ route('report.show', $report->code) }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline mb-6">
        &larr; Kembali ke Detail Laporan
    </a>

    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-[28px] md:text-[32px] font-bold text-[#0f172a] mb-1">Ajukan Klaim Barang</h1>
        <p class="text-[15px] md:text-[16px] text-[#64748b]">
            Isi data dan bukti kepemilikan. Satpam akan memverifikasi sebelum barang diserahkan.
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 md:p-8">
        <!-- Item Summary Banner -->
        <div class="bg-[#edf0f4] rounded-[12px] p-3 flex items-center gap-3.5 mb-6">
            @if($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="size-[64px] object-cover rounded-[10px] border border-[#e2e8f0] shrink-0">
            @else
                <div class="size-[64px] bg-[#e2e8f0] rounded-[10px] flex items-center justify-center text-[#94a3b8] text-[12px] font-medium shrink-0">
                    Foto
                </div>
            @endif
            <div>
                <h3 class="text-[16px] font-semibold text-[#0f172a]">{{ $report->item_name }}</h3>
                <p class="text-[13px] text-[#64748b]">
                    {{ $report->location }} &middot; {{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}
                </p>
            </div>
        </div>

        <form action="{{ route('claim.store', $report->code) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Grid 2 Col -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Nama Lengkap</label>
                    <input type="text" name="claimant_name" value="{{ old('claimant_name') }}" placeholder="Nama sesuai identitas" required
                           class="w-full bg-[#f7f8fa] border @error('claimant_name') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    @error('claimant_name')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">No. HP</label>
                    <input type="tel" inputmode="numeric" pattern="[0-9]{10,13}" maxlength="13" name="claimant_phone" value="{{ old('claimant_phone') }}" placeholder="08xxxxxxxxxx (10 - 13 angka)" required
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                           class="w-full bg-[#f7f8fa] border @error('claimant_phone') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    <p class="text-[11px] text-[#64748b] mt-1">Hanya angka (10 sampai 13 digit).</p>
                    @error('claimant_phone')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ciri Khusus -->
            <div>
                <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Ciri khusus barang</label>
                <textarea name="proof_description" rows="3" placeholder="Sebutkan ciri yang hanya diketahui pemilik..." required
                          class="w-full bg-[#f7f8fa] border @error('proof_description') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">{{ old('proof_description') }}</textarea>
                <p class="text-[12px] text-[#64748b] mt-1">
                    Informasi ini akan dicocokkan dengan ciri rahasia dari pelapor untuk memvalidasi kepemilikan.
                </p>
                @error('proof_description')
                    <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Bukti -->
            <div>
                <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Unggah bukti kepemilikan (opsional)</label>
                <div class="border-2 border-dashed border-[#2563eb] bg-[#edf0f4] rounded-[12px] p-6 text-center transition-colors relative">
                    <input type="file" name="proof_photo" id="proof_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewUpload(this)">
                    <div id="upload-placeholder" class="space-y-1 pointer-events-none">
                        <span class="text-[#2563eb] text-[14px] font-semibold block" id="file-label">Unggah bukti kepemilikan</span>
                        <span class="text-[#64748b] text-[12px] block">Foto barang sebelum hilang, nota pembelian, atau bukti lain (JPG atau PNG, maks. 2 MB)</span>
                    </div>
                    <div id="preview-box" class="mt-3 hidden relative z-10 flex flex-col items-center">
                        <img id="preview-img" src="" alt="Preview" class="w-36 h-36 object-contain rounded-[10px] border border-[#e2e8f0] bg-white shadow-xs">
                        <p id="file-name" class="text-xs text-[#0f172a] font-medium mt-1.5"></p>
                        <button type="button" onclick="cancelUpload()" class="mt-2.5 bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Batalkan Pilihan Foto
                        </button>
                    </div>
                </div>
                @error('proof_photo')
                    <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('report.show', $report->code) }}"
                   class="bg-[#e2e8f0] hover:bg-slate-300 text-[#0f172a] font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors">
                    Kirim Klaim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewUpload(input) {
        const file = input.files[0];
        const placeholder = document.getElementById('upload-placeholder');
        const previewBox = document.getElementById('preview-box');
        const previewImg = document.getElementById('preview-img');
        const fileName = document.getElementById('file-name');
        if (file) {
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function cancelUpload() {
        const input = document.getElementById('proof_photo');
        const placeholder = document.getElementById('upload-placeholder');
        const previewBox = document.getElementById('preview-box');
        const previewImg = document.getElementById('preview-img');
        input.value = '';
        previewImg.src = '';
        previewBox.classList.add('hidden');
        placeholder.classList.remove('hidden');
    }
</script>
@endsection
