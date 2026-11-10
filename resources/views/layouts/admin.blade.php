<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $currentUser = auth('admin')->user();
        $isSuper = $currentUser && $currentUser->isSuperAdmin();
        $pendingHandoverCount = $isSuper ? \App\Models\Handover::where('status', \App\Models\Handover::STATUS_MENUNGGU_VERIFIKASI)->count() : 0;
    @endphp
    <title>{{ $title ?? ($isSuper ? 'Admin - Found & Lost' : 'Satpam - Found & Lost') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#edf0f4] text-[#0f172a] font-['Public_Sans',sans-serif] min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Mobile Top Navigation Bar -->
    <header class="md:hidden {{ $isSuper ? 'bg-[#061e16] text-[#e6f4ee] border-[#0e3b2c]' : 'bg-[#0f172a] text-[#f7f8fa] border-slate-800' }} px-4 py-3 sticky top-0 z-40 flex items-center justify-between border-b shadow-sm">
        <div class="flex items-center gap-2.5">
            <div class="size-9 bg-[#f7f8fa] rounded-lg flex items-center justify-center p-1 shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Found & Lost" class="size-full object-contain">
            </div>
            <div>
                <span class="font-bold text-sm tracking-tight block">Found &amp; Lost</span>
                <span class="text-[10px] {{ $isSuper ? 'text-emerald-400' : 'text-blue-400' }} font-semibold uppercase tracking-wider block">
                    {{ $isSuper ? 'Admin' : 'Satpam' }}
                </span>
            </div>
        </div>
        <button type="button" onclick="toggleMobileSidebar()" class="p-2 rounded-lg {{ $isSuper ? 'bg-[#0c3124] text-emerald-200' : 'bg-slate-800 text-slate-200' }} focus:outline-none cursor-pointer" aria-label="Buka Menu">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- Mobile Sidebar Backdrop -->
    <div id="mobile-sidebar-backdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <!-- Sidebar (Desktop Sticky + Mobile Drawer) -->
    <!-- Admin sidebar: Hitam ke-hijauan (#061e16), Satpam sidebar: Biru gelap / Slate (#0f172a) -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 {{ $isSuper ? 'bg-[#061e16] text-[#e6f4ee] border-r border-[#0e3b2c]' : 'bg-[#0f172a] text-[#f7f8fa]' }} flex flex-col shrink-0 min-h-screen p-5 h-screen overflow-y-auto transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static md:w-60 md:sticky md:top-0">
        <!-- Logo & Close Button -->
        <div class="flex items-center justify-between py-2 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="size-11 bg-[#f7f8fa] rounded-xl flex items-center justify-center p-1.5 shrink-0 shadow-xs">
                    <img src="{{ asset('images/logo.png') }}" alt="Found & Lost" class="size-full object-contain">
                </div>
                <div>
                    <span class="font-bold text-base tracking-tight block">Found &amp; Lost</span>
                    <span class="text-[10px] {{ $isSuper ? 'text-emerald-400 font-bold' : 'text-blue-400 font-semibold' }} uppercase tracking-wider block">
                        {{ $isSuper ? 'Portal Admin' : 'Portal Satpam' }}
                    </span>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button type="button" onclick="toggleMobileSidebar()" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-white cursor-pointer" aria-label="Tutup Menu">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="font-semibold text-[11px] {{ $isSuper ? 'text-emerald-300/60' : 'text-[#64748b]' }} tracking-wider mb-2 uppercase">Menu Navigasi</p>

        <!-- Nav Items -->
        <nav class="space-y-1.5 flex-1">
            <!-- Dashboard (Both Admin & Satpam) -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-semibold transition-all
               @if(request()->routeIs('admin.dashboard*') || request()->routeIs('admin.report*'))
                   {{ $isSuper ? 'bg-[#059669] text-white shadow-xs' : 'bg-[#2563eb] text-[#f7f8fa]' }}
               @else
                   {{ $isSuper ? 'text-emerald-100/80 hover:bg-[#0c3124] hover:text-white' : 'text-[#cbd5e1] hover:bg-slate-800 hover:text-white' }}
               @endif">
                <svg class="size-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            @if($isSuper)
                <!-- ADMIN MENU ITEMS -->
                <a href="{{ route('admin.satpam.index') }}"
                   class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-semibold transition-all
                   @if(request()->routeIs('admin.satpam*'))
                       bg-[#059669] text-white shadow-xs
                   @else
                       text-emerald-100/80 hover:bg-[#0c3124] hover:text-white
                   @endif">
                    <svg class="size-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Kelola Satpam</span>
                </a>

                <a href="{{ route('admin.history.index') }}"
                   class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-semibold transition-all
                   @if(request()->routeIs('admin.history*'))
                       bg-[#059669] text-white shadow-xs
                   @else
                       text-emerald-100/80 hover:bg-[#0c3124] hover:text-white
                   @endif">
                    <svg class="size-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>History Selesai</span>
                </a>
            @else
                <!-- SATPAM MENU ITEMS -->
                <a href="{{ route('admin.handover.index') }}"
                   class="flex items-center px-3.5 py-3 rounded-[10px] text-sm font-semibold transition-all
                   @if(request()->routeIs('admin.handover*'))
                       bg-[#2563eb] text-[#f7f8fa]
                   @else
                       text-[#cbd5e1] hover:bg-slate-800 hover:text-white
                   @endif">
                    <svg class="size-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Serah Terima</span>
                </a>
            @endif
        </nav>

        <!-- Bottom Logout & Admin Info -->
        <div class="pt-4 border-t {{ $isSuper ? 'border-[#0e3b2c]' : 'border-slate-800' }}">
            <div class="px-3.5 py-2">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-bold truncate text-white">
                        {{ $currentUser->name ?? 'User' }}
                    </p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isSuper ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                        {{ $isSuper ? 'Admin' : 'Satpam' }}
                    </span>
                </div>
                <p class="text-[11px] {{ $isSuper ? 'text-emerald-300/60' : 'text-[#64748b]' }} truncate">
                    {{ $currentUser->email ?? 'admin@gmail.com' }}
                </p>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center px-3.5 py-2.5 rounded-[10px] text-sm font-medium {{ $isSuper ? 'text-emerald-100/70 hover:bg-rose-950/60 hover:text-rose-300' : 'text-[#cbd5e1] hover:bg-rose-950/60 hover:text-rose-400' }} transition-all cursor-pointer">
                    <svg class="size-4 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
