@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-3 text-[13px] text-[#64748b]">
        {{-- Info showing count --}}
        <div>
            @if ($paginator->firstItem())
                <span>Menampilkan <strong class="text-[#0f172a] font-semibold">{{ $paginator->firstItem() }}</strong> sampai <strong class="text-[#0f172a] font-semibold">{{ $paginator->lastItem() }}</strong> dari <strong class="text-[#0f172a] font-semibold">{{ $paginator->total() }}</strong> data</span>
            @else
                <span>Total <strong class="text-[#0f172a] font-semibold">{{ $paginator->total() }}</strong> data</span>
            @endif
        </div>

        {{-- Page Buttons --}}
        <div class="inline-flex items-center -space-x-px rounded-[10px] shadow-2xs bg-white border border-[#e2e8f0] overflow-hidden">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3 py-2 text-slate-300 bg-slate-50 cursor-not-allowed">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-2 text-[#0f172a] hover:bg-slate-100 transition-colors">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center px-3 py-2 text-[#94a3b8] bg-slate-50 cursor-default select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center px-3.5 py-2 font-bold text-white bg-[#2563eb] cursor-default select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center px-3.5 py-2 font-medium text-[#0f172a] hover:bg-slate-100 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-2 text-[#0f172a] hover:bg-slate-100 transition-colors">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-3 py-2 text-slate-300 bg-slate-50 cursor-not-allowed">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
