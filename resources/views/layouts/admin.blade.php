<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard - Found & Lost' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#edf0f4] text-[#0f172a] font-sans min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Mobile Top Navigation Bar -->
    <header class="md:hidden bg-[#0f172a] text-[#f7f8fa] px-4 py-3 sticky top-0 z-40 flex items-center justify-between border-b border-slate-800 shadow-sm">
        <div class="flex items-center gap-2.5">
            <div class="size-9 bg-[#f7f8fa] rounded-lg flex items-center justify-center p-1 shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Found & Lost" class="size-full object-contain">
            </div>
            <span class="font-bold text-sm text-[#f7f8fa] tracking-tight">Found &amp; Lost</span>
        </div>
        <button type="button" onclick="toggleMobileSidebar()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 focus:outline-none cursor-pointer" aria-label="Buka Menu">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- Mobile Sidebar Backdrop -->
    <div id="mobile-sidebar-backdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <!-- Sidebar (Desktop Sticky + Mobile Drawer) -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] text-[#f7f8fa] flex flex-col shrink-0 min-h-screen p-5 h-screen overflow-y-auto transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static md:w-60 md:sticky md:top-0">
        <!-- Logo & Close Button -->
        <div class="flex items-center justify-between py-2 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="size-11 bg-[#f7f8fa] rounded-xl flex items-center justify-center p-1.5 shrink-0 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}" alt="Found & Lost" class="size-full object-contain">
                </div>
                <div>
                    <span class="font-bold text-base text-[#f7f8fa] tracking-tight block">Found &amp; Lost</span>
                    <span class="text-[10px] text-[#94a3b8] uppercase tracking-wider block font-semibold">Portal Admin</span>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button type="button" onclick="toggleMobileSidebar()" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer" aria-label="Tutup Menu">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="font-semibold text-[11px] text-[#64748b] tracking-wider mb-2 uppercase">Menu Navigasi</p>

        <!-- Nav Items -->
        <nav class="space-y-1.5 flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard*') || request()->routeIs('admin.report*') ? 'bg-[#2563eb] text-[#f7f8fa]' : 'text-[#cbd5e1] hover:bg-slate-800 hover:text-white' }}">
                <svg class="size-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.handover.index') }}" class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-medium transition-all {{ request()->routeIs('admin.handover*') ? 'bg-[#2563eb] text-[#f7f8fa]' : 'text-[#cbd5e1] hover:bg-slate-800 hover:text-white' }}">
                <svg class="size-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Serah Terima
            </a>

            <a href="{{ route('admin.history.index') }}" class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-medium transition-all {{ request()->routeIs('admin.history*') ? 'bg-[#2563eb] text-[#f7f8fa]' : 'text-[#cbd5e1] hover:bg-slate-800 hover:text-white' }}">
                <svg class="size-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                History
            </a>
        </nav>

        <!-- Bottom Logout & Admin Info -->
        <div class="pt-4 border-t border-slate-800">
            <div class="px-3.5 py-2">
                <p class="text-xs font-semibold text-white truncate">{{ auth('admin')->user()->name ?? 'Satpam Pos 1' }}</p>
                <p class="text-[11px] text-[#64748b] truncate">{{ auth('admin')->user()->email ?? 'admin@gmail.com' }}</p>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center px-3.5 py-2.5 rounded-[10px] text-sm font-medium text-[#cbd5e1] hover:bg-rose-950/60 hover:text-rose-400 transition-all cursor-pointer">
                    <svg class="size-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen min-w-0 overflow-x-hidden">
        <!-- Flash Notification -->
        <div class="px-4 sm:px-6 lg:px-10 pt-4 sm:pt-6">
            @if (session('success'))
                <div class="bg-[#dcfce7] border border-[#86efac] text-[#16a34a] px-4 py-3 rounded-xl flex items-center gap-2 mb-4 shadow-2xs">
                    <svg class="size-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-[#fee2e2] border border-[#fca5a5] text-[#dc2626] px-4 py-3 rounded-xl flex items-center gap-2 mb-4 shadow-2xs">
                    <svg class="size-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <main class="flex-1 p-4 sm:p-6 lg:p-10 pt-2 sm:pt-4">
            @yield('content')
        </main>
    </div>

    <!-- Toggle Script for Mobile Sidebar -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    </script>
</body>
</html>
