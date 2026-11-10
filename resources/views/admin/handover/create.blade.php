@extends('layouts.admin')

@section('title', 'Form Serah Terima - ' . $report->code)
@section('header', 'Form Serah Terima')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('admin.handover.index') }}" class="inline-flex items-center text-[#2563eb] text-[14px] font-medium hover:underline">
        &larr; Kembali ke Daftar Serah Terima
    </a>

    <!-- Page Heading -->
    <div>
        <h1 class="text-2xl sm:text-[28px] font-bold text-[#0f172a] mb-1">Form Serah Terima Barang</h1>
        <p class="text-xs sm:text-[15px] text-[#64748b]">
            {{ $report->code }} &middot; {{ $report->item_name }}. Catat data penerima dan unggah foto dokumentasi serah terima untuk diverifikasi oleh Admin.
        </p>
    </div>

    <!-- Alert Jika Sebelumnya Ditolak Admin -->
    @if(isset($existingHandover) && $existingHandover->status === 'ditolak')
        <div class="bg-red-50 border-2 border-red-300 rounded-[16px] p-5 shadow-2xs">
            <div class="flex items-start gap-3">
                <div class="size-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0 mt-0.5">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-red-900 uppercase tracking-wide">Pengajuan Sebelumnya Ditolak oleh Admin</h2>
                    <p class="text-xs sm:text-sm text-red-800 mt-1 font-medium leading-relaxed">
                        <strong>Alasan Penolakan:</strong> {{ $existingHandover->rejection_reason }}
                    </p>
                    <p class="text-xs text-red-700 mt-1">
                        Silakan perbaiki data atau perbarui foto bukti penyerahan barang di bawah ini lalu ajukan kembali.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left: Ringkasan Barang -->
        <div class="lg:col-span-5 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-6 flex flex-col gap-4 shadow-2xs">
            <div class="flex items-center gap-2">
                @if($report->type === 'hilang')
                    <span class="bg-[#fee2e2] text-[#dc2626] text-[12px] font-semibold px-2.5 py-1 rounded-full">Hilang</span>
                @else
                    <span class="bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-1 rounded-full">Ditemukan</span>
                @endif
                <span class="bg-[#dcfce7] text-[#16a34a] text-[12px] font-semibold px-2.5 py-1 rounded-full">Siap Diserahkan</span>
            </div>

            <h2 class="text-lg sm:text-[20px] font-bold text-[#0f172a]">{{ $report->item_name }}</h2>

            @if($report->photo_url)
                <img src="{{ $report->photo_url }}" alt="{{ $report->item_name }}" class="w-full max-h-[220px] object-cover rounded-[12px] border border-[#e2e8f0]">
            @else
                <div class="w-full h-48 sm:h-[200px] bg-[#e2e8f0] rounded-[12px] flex items-center justify-center text-[#94a3b8] text-[13px] font-medium">
                    Foto Barang
                </div>
            @endif

            <div class="border-t border-[#e2e8f0] pt-3 flex flex-col gap-2 text-[14px]">
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px]">Lokasi</span>
                    <span class="text-[#0f172a] font-medium sm:text-right">{{ $report->location }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 gap-0.5 sm:gap-2">
                    <span class="text-[#64748b] text-[13px]">Tanggal</span>
                    <span class="text-[#0f172a] font-medium sm:text-right">{{ \Carbon\Carbon::parse($report->incident_date)->format('d M Y') }}</span>
                </div>
                @if($activeClaim)
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-[10px]">
                        <p class="text-[12px] font-bold text-[#1e40af] uppercase mb-1">Data Klaim Pemilik ({{ $activeClaim->claim_code }})</p>
                        <p class="text-[13px] text-[#0f172a]"><strong>Pengklaim:</strong> {{ $activeClaim->claimant_name }}</p>
                        <p class="text-[13px] text-[#0f172a]"><strong>No. HP:</strong> {{ $activeClaim->claimant_phone }}</p>
                        <p class="text-[13px] text-[#0f172a] mt-1"><strong>Bukti:</strong> {{ $activeClaim->proof_description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Form Serah Terima -->
        <div class="lg:col-span-7 bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-5 sm:p-8 shadow-2xs">
            <h3 class="text-base sm:text-[18px] font-semibold text-[#0f172a] mb-2">Pencatatan Berita Acara Serah Terima</h3>
            <p class="text-xs text-[#64748b] mb-5">
                Pastikan identitas penerima telah diperiksa secara langsung. Laporan ini akan diverifikasi oleh Admin.
            </p>

            @php
                $defaultRecipient = $existingHandover?->recipient_name
                    ?: ($activeClaim ? $activeClaim->claimant_name : $report->reporter_name);
                $defaultContact = $existingHandover?->recipient_contact
                    ?: ($activeClaim ? $activeClaim->claimant_phone : $report->reporter_phone);
                $defaultNotes = $existingHandover?->notes ?: '';
            @endphp

            <form action="{{ route('admin.handover.store', $report->report_id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Nama Lengkap Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name', $defaultRecipient) }}" required
                           class="w-full bg-[#f7f8fa] border @error('recipient_name') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    @error('recipient_name')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">No. Kontak / No. Identitas Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_contact" value="{{ old('recipient_contact', $defaultContact) }}" placeholder="08xx / NIK / Kartu Pelajar" required
                           class="w-full bg-[#f7f8fa] border @error('recipient_contact') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                    @error('recipient_contact')
                        <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">
                        Dokumentasi Foto Serah Terima <span class="text-red-500">*</span>
                    </label>

                    @if(isset($existingHandover) && $existingHandover->proof_photo_url)
                        <div class="mb-3 p-3 bg-white rounded-xl border border-slate-200 flex items-center gap-3">
                            <img src="{{ $existingHandover->proof_photo_url }}" alt="Foto Tersimpan" class="size-16 rounded-lg object-cover border border-slate-200">
                            <div class="text-xs">
                                <span class="font-bold text-slate-800 block">Foto Bukti Sebelumnya Telah Diunggah</span>
                                <span class="text-[#64748b] block mt-0.5">Unggah foto baru di bawah jika ingin mengganti foto sebelumnya.</span>
                            </div>
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-[#2563eb] bg-[#edf0f4] rounded-[12px] p-5 sm:p-6 text-center transition-colors relative">
                        <input type="file" name="proof_photo" id="proof_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewUpload(this)">
                        <div id="upload-placeholder" class="space-y-1 pointer-events-none">
                            <span class="text-[#2563eb] text-[14px] font-semibold block" id="file-label">Unggah foto bukti serah terima</span>
                            <span class="text-[#64748b] text-[12px] block">Foto penerima bersama barang atau kartu identitas (JPG atau PNG, maks. 2 MB)</span>
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

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Catatan Serah Terima (opsional)</label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Barang diserahkan dalam kondisi baik dan lengkap, disaksikan oleh petugas jaga."
                              class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">{{ old('notes', $defaultNotes) }}</textarea>
                </div>

                <div class="pt-4 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 border-t border-[#e2e8f0]">
                    <a href="{{ route('admin.handover.index') }}"
                       class="text-center bg-[#e2e8f0] hover:bg-slate-300 text-[#0f172a] font-semibold text-[14px] px-5 py-3 rounded-[10px] transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="text-center bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[14px] px-6 py-3 rounded-[10px] transition-colors shadow-2xs cursor-pointer">
                        Ajukan Serah Terima ke Admin &rarr;
                    </button>
                </div>
            </form>
        </div>
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
