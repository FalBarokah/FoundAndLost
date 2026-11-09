@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#0f172a] mb-2">
            {{ $type === 'hilang' ? 'Lapor Barang Hilang' : 'Lapor Barang Ditemukan' }}
        </h1>
        <p class="text-base text-[#64748b]">
            {{ $type === 'hilang' 
                ? 'Isi formulir dengan detail agar mempermudah proses pencarian dan verifikasi barang.' 
                : 'Laporkan barang yang kamu temukan, lalu serahkan fisik barang ke Pos Satpam.' }}
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 sm:p-8 shadow-xs">
        <!-- Report Type Toggle -->
        <div class="mb-6">
            <label class="block text-xs font-semibold text-[#0f172a] mb-2">Jenis Laporan</label>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('report.create.hilang') }}" 
                   class="py-3 px-4 rounded-[10px] text-center font-semibold text-sm transition-all flex items-center justify-center gap-2 {{ $type === 'hilang' ? 'bg-[#dbeafe] border border-[#2563eb] text-[#2563eb]' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:bg-slate-100' }}">
                    <span class="w-2.5 h-2.5 rounded-full {{ $type === 'hilang' ? 'bg-[#2563eb]' : 'bg-slate-300' }}"></span>
                    Barang Hilang
                </a>
                <a href="{{ route('report.create.ditemukan') }}" 
                   class="py-3 px-4 rounded-[10px] text-center font-semibold text-sm transition-all flex items-center justify-center gap-2 {{ $type === 'ditemukan' ? 'bg-[#dbeafe] border border-[#2563eb] text-[#2563eb]' : 'bg-[#f7f8fa] border border-[#e2e8f0] text-[#0f172a] hover:bg-slate-100' }}">
                    <span class="w-2.5 h-2.5 rounded-full {{ $type === 'ditemukan' ? 'bg-[#2563eb]' : 'bg-slate-300' }}"></span>
                    Barang Ditemukan
                </a>
            </div>
        </div>

        <!-- Auto Status Information -->
        <div class="mb-6 bg-blue-50/70 border border-blue-200/80 rounded-xl px-4 py-2.5 text-xs text-blue-800 flex items-center justify-between">
            <span>Status Awal Laporan:</span>
            <span class="font-bold uppercase tracking-wider {{ $type === 'hilang' ? 'text-amber-700' : 'text-blue-700' }}">
                {{ $type === 'hilang' ? 'Proses Pencarian' : 'Siap Diambil' }}
            </span>
        </div>

        <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Barang -->
                <div>
                    <label for="item_name" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Nama Barang <span class="text-rose-500">*</span></label>
                    <input type="text" name="item_name" id="item_name" required value="{{ old('item_name') }}"
                           placeholder="Contoh: Kunci Motor Honda, Dompet Hitam"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                    @error('item_name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" id="location" required value="{{ old('location') }}"
                           placeholder="Contoh: Parkiran Barat, Lab Komputer 2"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                    @error('location')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Kejadian -->
                <div>
                    <label for="incident_date" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Tanggal Kejadian / Ditemukan <span class="text-rose-500">*</span></label>
                    <input type="date" name="incident_date" id="incident_date" required value="{{ old('incident_date', date('Y-m-d')) }}"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                    @error('incident_date')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Pelapor -->
                <div>
                    <label for="reporter_name" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Nama Pelapor <span class="text-rose-500">*</span></label>
                    <input type="text" name="reporter_name" id="reporter_name" required value="{{ old('reporter_name') }}"
                           placeholder="Nama lengkap kamu"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                    @error('reporter_name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No HP / Kontak -->
                <div class="sm:col-span-2">
                    <label for="reporter_phone" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Nomor Handphone / WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="tel" inputmode="numeric" pattern="[0-9]{10,13}" maxlength="13" name="reporter_phone" id="reporter_phone" required value="{{ old('reporter_phone') }}"
                           placeholder="Contoh: 081234567890 (10 - 13 angka)"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                    <p class="text-[11px] text-[#64748b] mt-1">Hanya angka (10 sampai 13 digit).</p>
                    @error('reporter_phone')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi Barang -->
            <div>
                <label for="description" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Deskripsi Barang <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="3" required placeholder="Jelaskan bentuk, warna umum, kondisi barang..."
                          class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ciri Khusus -->
            <div>
                <label for="special_features" class="block text-xs font-semibold text-[#0f172a] mb-1.5">
                    Ciri-Ciri Khusus 
                    @if ($type === 'ditemukan')
                        <span class="text-[#64748b] font-normal">(Akan disembunyikan di katalog publik untuk keperluan verifikasi pengklaim)</span>
                    @endif
                </label>
                <textarea name="special_features" id="special_features" rows="3" placeholder="Sebutkan detail tersembunyi seperti stiker, goresan, nomor seri, isi dompet/tas..."
                          class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm text-[#0f172a] focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">{{ old('special_features') }}</textarea>
                @error('special_features')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Foto -->
            <div>
                <label class="block text-xs font-semibold text-[#0f172a] mb-1.5">Foto Barang (Opsional)</label>
                <div class="border border-[#2563eb] border-dashed bg-[#edf0f4] rounded-[12px] p-6 text-center transition-colors relative">
                    <input type="file" name="photo" id="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)">
                    <div id="preview-container" class="space-y-1 pointer-events-none">
                        <svg class="w-8 h-8 text-[#2563eb] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-semibold text-[#2563eb]">Unggah foto barang</p>
                        <p class="text-xs text-[#64748b]">JPG atau PNG, maks. 2 MB</p>
                    </div>
                    <div id="preview-wrapper" class="hidden relative z-10 flex flex-col items-center">
                        <img id="image-preview" class="max-h-48 rounded-lg object-contain border border-[#e2e8f0] bg-white shadow-xs" alt="Preview Foto">
                        <button type="button" onclick="cancelImage()" class="mt-3 bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Batalkan Pilihan Foto
                        </button>
                    </div>
                </div>
                @error('photo')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#e2e8f0]">
                <a href="{{ route('home') }}" class="bg-[#e2e8f0] hover:bg-[#cbd5e1] text-[#0f172a] font-semibold text-sm px-5 py-2.5 rounded-[10px] transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm px-6 py-2.5 rounded-[10px] transition-colors shadow-xs cursor-pointer">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        const wrapper = document.getElementById('preview-wrapper');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                wrapper.classList.remove('hidden');
                container.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function cancelImage() {
        const input = document.getElementById('photo');
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        const wrapper = document.getElementById('preview-wrapper');
        input.value = '';
        preview.src = '';
        wrapper.classList.add('hidden');
        container.classList.remove('hidden');
    }
</script>
@endsection
