<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Found & Lost - Sistem Pelaporan Barang Hilang & Ditemukan' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#edf0f4] text-[#0f172a] font-sans min-h-screen flex flex-col antialiased">
    <!-- Navbar -->
    <header class="bg-[#f7f8fa] border-b border-[#e2e8f0] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[74px] flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-white shadow-xs p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Found & Lost" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-lg text-[#0f172a] tracking-tight group-hover:text-[#2563eb] transition-colors">Found & Lost</span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-7">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#2563eb] font-semibold' : 'text-[#64748b] font-medium hover:text-[#0f172a]' }} text-sm transition-colors">
                    Beranda
                </a>
                <a href="{{ route('report.check') }}" class="{{ request()->routeIs('report.check') ? 'text-[#2563eb] font-semibold' : 'text-[#64748b] font-medium hover:text-[#0f172a]' }} text-sm transition-colors">
                    Cek/Edit Laporan
                </a>
                
                <!-- Dropdown Buat Laporan -->
                <div class="relative group">
                    <button type="button" class="{{ request()->routeIs('report.create.*') ? 'text-[#2563eb] font-semibold' : 'text-[#64748b] font-medium hover:text-[#0f172a]' }} text-sm flex items-center gap-1 transition-colors py-2 cursor-pointer">
                        <span>Buat Laporan</span>
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-1 w-48 bg-[#f7f8fa] rounded-xl border border-[#e2e8f0] shadow-lg py-2 hidden group-hover:block transition-all z-50">
                        <a href="{{ route('report.create.hilang') }}" class="block px-4 py-2 text-sm text-[#0f172a] hover:bg-[#dbeafe] hover:text-[#2563eb] font-medium">
                            Lapor Kehilangan
                        </a>
                        <a href="{{ route('report.create.ditemukan') }}" class="block px-4 py-2 text-sm text-[#0f172a] hover:bg-[#dbeafe] hover:text-[#2563eb] font-medium">
                            Lapor Penemuan
                        </a>
                    </div>
                </div>

                <!-- Admin Button -->
                <a href="{{ route('admin.login') }}" class="bg-[#f7f8fa] border border-[#2563eb] text-[#2563eb] hover:bg-[#2563eb] hover:text-white px-3.5 py-1.5 rounded-[10px] text-xs font-semibold transition-all">
                    Masuk
                </a>
            </nav>

            <!-- Mobile Hamburger Button -->
            <button type="button" id="mobile-menu-btn" class="md:hidden text-[#64748b] hover:text-[#0f172a] p-2 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-[#e2e8f0] bg-[#f7f8fa] px-4 pt-2 pb-4 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 text-sm font-medium {{ request()->routeIs('home') ? 'text-[#2563eb]' : 'text-[#64748b]' }}">
                Beranda
            </a>
            <a href="{{ route('report.check') }}" class="block py-2 text-sm font-medium {{ request()->routeIs('report.check') ? 'text-[#2563eb]' : 'text-[#64748b]' }}">
                Cek/Edit Laporan
            </a>
            <div class="pt-1 pb-1 border-t border-[#e2e8f0]">
                <a href="{{ route('report.create.hilang') }}" class="block py-2 text-sm font-medium text-[#0f172a]">
                    Lapor Kehilangan
                </a>
                <a href="{{ route('report.create.ditemukan') }}" class="block py-2 text-sm font-medium text-[#0f172a]">
                    Lapor Penemuan
                </a>
            </div>
            <a href="{{ route('admin.login') }}" class="block w-full text-center bg-[#2563eb] text-white py-2 rounded-lg text-sm font-semibold">
                Masuk Admin
            </a>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div class="bg-[#dcfce7] border border-[#86efac] text-[#16a34a] px-4 py-3 rounded-xl flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-[#fee2e2] border border-[#fca5a5] text-[#dc2626] px-4 py-3 rounded-xl flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#f7f8fa] border-t border-[#e2e8f0] py-6 mt-16 text-center text-xs text-[#64748b]">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Found & Lost — Sistem Pelaporan dan Penemuan Barang Hilang.</p>
        </div>
    </footer>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
