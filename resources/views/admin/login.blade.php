<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#edf0f4]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin - Found & Lost</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts: Public Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-['Public_Sans',sans-serif] text-[#0f172a] bg-[#edf0f4] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-[420px]">
        <!-- Card -->
        <div class="bg-[#f7f8fa] border border-[#e2e8f0] rounded-[16px] p-8 md:p-10 shadow-sm">
            <!-- Brand Header -->
            <div class="flex flex-col items-center mb-6 text-center">
                <div class="size-[48px] mb-3 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Found & Lost" class="size-[44px] object-contain">
                </div>
                <h1 class="text-[24px] font-bold text-[#0f172a]">Masuk Admin</h1>
                <p class="text-[13px] text-[#64748b] mt-1">Portal internal untuk verifikasi dan serah terima</p>
            </div>

            @if($errors->any())
                <!-- Pop-up Modal Jika Salah Email / Password -->
                <div id="error-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-opacity">
                    <div class="bg-white rounded-[20px] max-w-sm w-full p-6 text-center shadow-2xl border border-red-100 flex flex-col items-center animate-in fade-in zoom-in duration-200">
                        <div class="size-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-4">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#0f172a] mb-1.5">Gagal Masuk</h3>
                        <p class="text-sm text-[#64748b] leading-relaxed mb-6">
                            {{ $errors->first() }}
                        </p>
                        <button type="button" onclick="document.getElementById('error-modal').remove()"
                                class="w-full bg-[#dc2626] hover:bg-red-700 text-white font-semibold text-sm py-3 px-4 rounded-[12px] transition-colors cursor-pointer shadow-sm">
                            Coba Lagi
                        </button>
                    </div>
                </div>

                <div class="mb-5 p-3 rounded-[10px] bg-red-50 border border-red-200 text-red-600 text-[13px]">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.authenticate') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus
                           class="w-full bg-[#f7f8fa] border @error('email') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] px-3.5 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#0f172a] mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password-input" name="password" placeholder="Password saya" required
                               class="w-full bg-[#f7f8fa] border @error('password') border-red-500 @else border-[#e2e8f0] @enderror rounded-[10px] pl-3.5 pr-10 py-2.5 text-[14px] text-[#0f172a] focus:outline-none focus:border-[#2563eb]">
                        <button type="button" onclick="togglePassword()" id="eye-btn" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#64748b] hover:text-[#0f172a] focus:outline-none cursor-pointer" title="Lihat / sembunyikan password">
                            <!-- Eye icon (open) -->
                            <svg id="eye-open" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye icon (closed / slashed) -->
                            <svg id="eye-closed" class="size-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-[#2563eb] hover:bg-blue-700 text-white font-semibold text-[14px] py-3 rounded-[10px] transition-colors">
                        Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-[13px] text-[#64748b] hover:text-[#2563eb] font-medium transition-colors">
                    &larr; Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
