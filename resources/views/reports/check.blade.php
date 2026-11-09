@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto px-4 py-12">
    <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-6 sm:p-9 shadow-sm">
        <!-- Mode Tabs -->
        <div class="bg-[#e3e8ef] p-1 rounded-[10px] grid grid-cols-3 gap-1 mb-6">
            <button type="button" onclick="switchTab('lacak')" id="tab-btn-lacak"
                    class="tab-btn py-2.5 px-2 rounded-[8px] text-xs sm:text-sm font-semibold transition-all cursor-pointer bg-white text-[#2563eb] shadow-xs">
                Cek Laporan
            </button>
            <button type="button" onclick="switchTab('edit')" id="tab-btn-edit"
                    class="tab-btn py-2.5 px-2 rounded-[8px] text-xs sm:text-sm font-medium transition-all cursor-pointer text-[#64748b] hover:text-[#0f172a]">
                Edit Laporan
            </button>
            <button type="button" onclick="switchTab('klaim')" id="tab-btn-klaim"
                    class="tab-btn py-2.5 px-2 rounded-[8px] text-xs sm:text-sm font-medium transition-all cursor-pointer text-[#64748b] hover:text-[#0f172a]">
                Cek Klaim
            </button>
        </div>

        <!-- Tab 1: Cek Laporan (Kode Lacak FL) -->
        <div id="tab-content-lacak" class="tab-content space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Cek Laporan</h1>
                <p class="text-sm text-[#64748b]">Masukkan Kode Lacak (awalan FL) untuk melihat status laporanmu.</p>
            </div>

            @if (session('track_error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-lg">
                    {{ session('track_error') }}
                </div>
            @endif

            <form action="{{ route('report.track') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="code" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Kode Lacak</label>
                    <input type="text" name="code" id="code" required value="{{ old('code') }}" placeholder="Contoh: FL-2026-0042"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm font-mono text-[#0f172a] uppercase placeholder-slate-400 focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                </div>

                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm py-3 px-4 rounded-[10px] transition-colors cursor-pointer shadow-xs">
                        Cek Laporan
                    </button>
                    <a href="{{ route('home') }}" class="w-full bg-[#e2e8f0] hover:bg-[#cbd5e1] text-[#0f172a] font-semibold text-sm py-2.5 px-4 rounded-[10px] flex items-center justify-center transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

        <!-- Tab 2: Edit Laporan (Kode Edit ED) -->
        <div id="tab-content-edit" class="tab-content hidden space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Edit Laporan</h1>
                <p class="text-sm text-[#64748b]">Masukkan Kode Edit rahasia (awalan ED) untuk memperbarui atau membatalkan laporanmu.</p>
            </div>

            @if (session('edit_error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-lg">
                    {{ session('edit_error') }}
                </div>
            @endif

            <form action="{{ route('report.editForm') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="edit_code" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Kode Edit</label>
                    <input type="text" name="edit_code" id="edit_code" required value="{{ old('edit_code') }}" placeholder="Contoh: ED-2026-0042"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm font-mono text-[#0f172a] uppercase placeholder-slate-400 focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                </div>

                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm py-3 px-4 rounded-[10px] transition-colors cursor-pointer shadow-xs">
                        Buka Form Edit
                    </button>
                    <a href="{{ route('home') }}" class="w-full bg-[#e2e8f0] hover:bg-[#cbd5e1] text-[#0f172a] font-semibold text-sm py-2.5 px-4 rounded-[10px] flex items-center justify-center transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

        <!-- Tab 3: Cek Klaim (Nomor Klaim KLM) -->
        <div id="tab-content-klaim" class="tab-content hidden space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Cek Status Klaim</h1>
                <p class="text-sm text-[#64748b]">Masukkan Nomor Klaim (awalan KLM) yang kamu terima saat mengajukan klaim.</p>
            </div>

            @if (session('claim_error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-lg">
                    {{ session('claim_error') }}
                </div>
            @endif

            <form action="{{ route('claim.checkStatus') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="claim_code" class="block text-xs font-semibold text-[#0f172a] mb-1.5">Nomor Klaim</label>
                    <input type="text" name="claim_code" id="claim_code" required value="{{ old('claim_code') }}" placeholder="Contoh: KLM-2026-0001"
                           class="w-full bg-[#f7f8fa] border border-[#e2e8f0] rounded-[10px] px-3.5 py-2.5 text-sm font-mono text-[#0f172a] uppercase placeholder-slate-400 focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]">
                </div>

                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-[#f7f8fa] font-semibold text-sm py-3 px-4 rounded-[10px] transition-colors cursor-pointer shadow-xs">
                        Cek Status Klaim
                    </button>
                    <a href="{{ route('home') }}" class="w-full bg-[#e2e8f0] hover:bg-[#cbd5e1] text-[#0f172a] font-semibold text-sm py-2.5 px-4 rounded-[10px] flex items-center justify-center transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-[#2563eb]', 'shadow-xs', 'font-semibold');
            btn.classList.add('text-[#64748b]', 'font-medium');
        });

        const activeContent = document.getElementById('tab-content-' + tab);
        const activeBtn = document.getElementById('tab-btn-' + tab);

        if (activeContent && activeBtn) {
            activeContent.classList.remove('hidden');
            activeBtn.classList.remove('text-[#64748b]', 'font-medium');
            activeBtn.classList.add('bg-white', 'text-[#2563eb]', 'shadow-xs', 'font-semibold');
        }
    }

    // Auto switch tab if error on specific tab
    @if (session('edit_error'))
        switchTab('edit');
    @elseif (session('claim_error'))
        switchTab('klaim');
    @endif
</script>
@endsection
