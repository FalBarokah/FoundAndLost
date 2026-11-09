@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16">
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-8 sm:p-9 text-center space-y-6 shadow-sm">
        <!-- Check Icon -->
        <div class="w-14 h-14 bg-[#dcfce7] text-[#16a34a] rounded-full mx-auto flex items-center justify-center font-bold text-2xl shadow-xs">
            &#10003;
        </div>

        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] mb-2">Laporan Berhasil Dikirim</h1>
            <p class="text-sm text-[#64748b] max-w-md mx-auto leading-relaxed">
                Simpan kedua kode. Angka belakangnya sama agar mudah diingat. Kode Lacak untuk melihat status, Kode Edit untuk mengubah laporan.
            </p>
        </div>

        <!-- Copy Code Box: Kode Lacak -->
        <div class="bg-[#dbeafe] rounded-[12px] p-5 text-left border border-blue-200 transition-all hover:border-[#2563eb]">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-medium text-[#64748b]">Kode Lacak</span>
                <button type="button" onclick="copyToClipboard('{{ $report->code }}', this)" 
                        class="bg-[#dbeafe] hover:bg-blue-200/60 border border-blue-300 text-[#2563eb] text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1.5 transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    <span>Salin</span>
                </button>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-[#2563eb] tracking-wider mb-1 font-mono">
                {{ $report->code }}
            </p>
            <p class="text-[12px] text-[#64748b]">Gunakan kode ini untuk mengecek status pencarian.</p>
        </div>

        <!-- Copy Code Box: Kode Edit -->
        <div class="bg-[#dbeafe] rounded-[12px] p-5 text-left border border-blue-200 transition-all hover:border-[#2563eb]">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-medium text-[#64748b]">Kode Edit</span>
                <button type="button" onclick="copyToClipboard('{{ $report->edit_code }}', this)" 
                        class="bg-[#dbeafe] hover:bg-blue-200/60 border border-blue-300 text-[#2563eb] text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1.5 transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    <span>Salin</span>
                </button>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-[#2563eb] tracking-wider mb-1 font-mono">
                {{ $report->edit_code }}
            </p>
            <p class="text-[12px] text-[#64748b]">Gunakan kode rahasia ini untuk mengubah atau membatalkan laporan.</p>
        </div>

        <!-- Buttons -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('report.show', ['code' => $report->code]) }}" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm py-3 px-4 rounded-[10px] flex items-center justify-center transition-colors shadow-xs">
                Cek Laporan
            </a>
            <a href="{{ route('home') }}" class="w-full bg-[#e2e8f0] hover:bg-[#cbd5e1] text-[#0f172a] font-semibold text-sm py-3 px-4 rounded-[10px] flex items-center justify-center transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text, btnElement) {
        navigator.clipboard.writeText(text).then(() => {
            const span = btnElement.querySelector('span');
            const originalText = span.innerText;
            span.innerText = 'Tersalin!';
            setTimeout(() => {
                span.innerText = originalText;
            }, 2000);
        });
    }
</script>
@endsection
