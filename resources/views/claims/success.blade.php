@extends('layouts.app')

@section('title', 'Klaim Berhasil Dikirim - Found & Lost')

@section('content')
<div class="max-w-[540px] mx-auto px-4 py-12">
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-8 md:p-10 text-center flex flex-col items-center">
        <!-- Success Icon -->
        <div class="size-[56px] bg-[#dcfce7] rounded-full flex items-center justify-center text-[#16a34a] text-[28px] font-bold mb-4">
            ✓
        </div>

        <h1 class="text-[24px] font-bold text-[#0f172a] mb-2">Klaim Berhasil Dikirim</h1>
        <p class="text-[14px] text-[#64748b] leading-relaxed mb-6">
            Simpan Nomor Klaim di bawah ini untuk melihat status klaimmu. Admin akan menghubungi lewat nomor yang kamu isi.
        </p>

        <!-- Copyable Code Box -->
        <div onclick="copyToClipboard('{{ $claim->claim_code }}')"
             class="w-full bg-[#dbeafe] hover:bg-blue-100 transition-colors cursor-pointer rounded-[12px] p-4 mb-6 border border-blue-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[12px] font-medium text-[#64748b]">Nomor Klaim</span>
                <span class="inline-flex items-center gap-1.5 bg-[#dbeafe] text-[#2563eb] text-[12px] font-semibold px-2.5 py-0.5 rounded-full border border-blue-300">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Salin
                </span>
            </div>
            <p class="text-[28px] font-bold text-[#2563eb] tracking-wider my-1">{{ $claim->claim_code }}</p>
            <p class="text-[12px] text-[#64748b]" id="copy-hint">Ketuk kode untuk menyalin.</p>
        </div>

        <!-- Action Buttons -->
        <div class="w-full space-y-3">
            <a href="{{ route('report.check', ['tab' => 'klaim', 'code' => $claim->claim_code]) }}"
               class="block w-full bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[14px] py-3 rounded-[10px] transition-colors">
                Cek Klaim
            </a>
            <a href="{{ route('home') }}"
               class="block w-full bg-[#e2e8f0] hover:bg-slate-300 text-[#0f172a] font-semibold text-[14px] py-3 rounded-[10px] transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const hint = document.getElementById('copy-hint');
            hint.textContent = 'Kode berhasil disalin ke papan klip!';
            hint.classList.add('text-[#16a34a]', 'font-medium');
            setTimeout(() => {
                hint.textContent = 'Ketuk kode untuk menyalin.';
                hint.classList.remove('text-[#16a34a]', 'font-medium');
            }, 2500);
        });
    }
</script>
@endsection
