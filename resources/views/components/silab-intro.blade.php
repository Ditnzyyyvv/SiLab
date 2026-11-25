{{-- Smooth Multi-Layer Logo Intro Overlay untuk Halaman Login & Register --}}
<div
    id="silab-intro-overlay"
    class="silab-intro-overlay fixed inset-0 z-50 flex flex-col items-center justify-center bg-gradient-to-br from-white via-slate-50 to-sky-50/95 transition-all duration-700 ease-out cursor-pointer select-none"
    role="presentation"
    title="Klik di mana saja untuk melewati intro"
>
    <div id="silab-intro-stage" class="silab-intro-stage relative flex flex-col items-center">
        {{-- Ambient Blue Glow Behind Logo --}}
        <div class="silab-layer-glow pointer-events-none absolute -inset-8 rounded-full bg-gradient-to-tr from-blue-600/20 via-sky-400/25 to-cyan-300/20 blur-3xl opacity-0"></div>

        {{-- 1024x1024 Synchronized Transparent Layer Stack --}}
        <div class="relative w-52 h-52 sm:w-64 sm:h-64 overflow-hidden rounded-3xl">
            {{-- Layer 1: Blueprint Outline Wireframe --}}
            <img src="{{ asset('images/intro/outline.png') }}" alt="" decoding="async"
                 class="silab-layer-outline absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Layer 2: Solid 3D Monogram Fill --}}
            <img src="{{ asset('images/intro/monogram.png') }}" alt="" decoding="async"
                 class="silab-layer-monogram absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Layer 3-7: Staggered Floating Data Pixels --}}
            <img src="{{ asset('images/intro/pixel-2.png') }}" alt="" decoding="async"
                 class="silab-layer-pixel-2 absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />
            <img src="{{ asset('images/intro/pixel-1.png') }}" alt="" decoding="async"
                 class="silab-layer-pixel-1 absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />
            <img src="{{ asset('images/intro/pixel-3.png') }}" alt="" decoding="async"
                 class="silab-layer-pixel-3 absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />
            <img src="{{ asset('images/intro/pixel-4.png') }}" alt="" decoding="async"
                 class="silab-layer-pixel-4 absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />
            <img src="{{ asset('images/intro/pixel-5.png') }}" alt="" decoding="async"
                 class="silab-layer-pixel-5 absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Layer 8: Wordmark "SiLab" --}}
            <img src="{{ asset('images/intro/wordmark.png') }}" alt="SiLab" decoding="async"
                 class="silab-layer-wordmark absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Layer 9: Circuit Underline --}}
            <img src="{{ asset('images/intro/line.png') }}" alt="" decoding="async"
                 class="silab-layer-line absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Layer 10: Circuit Node Ring --}}
            <img src="{{ asset('images/intro/dot.png') }}" alt="" decoding="async"
                 class="silab-layer-dot absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Layer 11: Crisp Unified Composite Finish --}}
            <img src="{{ asset('images/intro/full.png') }}" alt="Logo SiLab" decoding="async"
                 class="silab-layer-full absolute inset-0 w-full h-full object-contain opacity-0 will-change-transform" />

            {{-- Glass Sheen Sweep --}}
            <div class="silab-layer-sheen pointer-events-none absolute inset-y-0 left-0 w-1/3 bg-gradient-to-r from-transparent via-white/75 to-transparent opacity-0"></div>
        </div>

        {{-- Subtitle Tagline --}}
        <p class="silab-layer-tagline mt-3 text-[11px] sm:text-xs font-bold uppercase text-slate-500 tracking-[0.14em] opacity-0">
            Sistem Informasi Laboratorium Sekolah
        </p>

        {{-- Subtle Skip Hint --}}
        <span class="mt-6 text-[11px] font-medium text-slate-400 hover:text-indigo-600 transition-colors">
            Klik di mana saja untuk lewati
        </span>
    </div>
</div>
