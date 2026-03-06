<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Website Builder - Webnewbiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        [x-cloak] { display: none !important; }

        /* Split-screen layout for steps 1-3 */
        .builder-wrapper { display: flex; min-height: 100vh; background: #ffffff; }
        .builder-left { width: 50%; background: linear-gradient(135deg, #0a0a0a 0%, #171717 50%, #1c1c1c 100%); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 3rem; }
        .builder-right { width: 50%; display: flex; flex-direction: column; position: relative; background: #fafafa; }
        .builder-right-content { flex: 1; overflow-y: auto; padding: 3rem; display: flex; flex-direction: column; justify-content: center; }
        .builder-bottom-bar { border-top: 1px solid #e5e7eb; padding: 1.25rem 3rem; display: flex; align-items: center; justify-content: space-between; background: #fff; flex-shrink: 0; }

        /* Animations */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        @keyframes pulse-glow { 0%, 100% { opacity: 0.4; } 50% { opacity: 1; } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes drawLine { from { stroke-dashoffset: 100; } to { stroke-dashoffset: 0; } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
        @keyframes slideRight { from { width: 0; } to { width: 100%; } }

        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-shimmer { background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.08) 50%, transparent 100%); background-size: 200% 100%; animation: shimmer 2s linear infinite; }
        .animate-scale-in { animation: scaleIn 0.5s ease-out both; }
        .step-panel { animation: fadeUp 0.5s ease-out; }

        /* Radio card */
        .radio-card { border: 2px solid #e5e7eb; border-radius: 14px; padding: 1.5rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); display: flex; align-items: center; gap: 1rem; background: #fff; }
        .radio-card:hover { border-color: #a3a3a3; background: #fafafa; transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
        .radio-card.selected { border-color: #171717; background: linear-gradient(135deg, #fafafa, #f5f5f5); box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .radio-card .radio-dot { width: 22px; height: 22px; border-radius: 50%; border: 2px solid #d4d4d8; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.3s ease; }
        .radio-card.selected .radio-dot { border-color: #171717; background: #e5e5e5; }
        .radio-card.selected .radio-dot::after { content: ''; width: 12px; height: 12px; border-radius: 50%; background: #171717; }

        /* Business type list */
        .type-list { max-height: 320px; overflow-y: auto; }
        .type-item { padding: 0.75rem 1rem; cursor: pointer; border-radius: 8px; transition: all 0.2s ease; }
        .type-item:hover { background: #f5f5f5; }
        .type-item.selected { background: linear-gradient(135deg, #e5e5e5, #f0f0f0); color: #171717; font-weight: 600; }

        /* Left panel */
        .left-grid-pattern { position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 30px 30px; }
        .left-gradient-orb { position: absolute; border-radius: 50%; filter: blur(80px); }

        /* Step dots */
        .step-dots { display: flex; align-items: center; gap: 0.5rem; }
        .step-dot-group .dot { width: 8px; height: 8px; border-radius: 50%; background: #d4d4d8; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .step-dot-group .dot.active { background: linear-gradient(135deg, #171717, #404040); width: 28px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        .step-dot-group .dot.done { background: #171717; }

        /* Fullscreen grid pattern */
        .fullscreen-grid { position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 30px 30px; pointer-events: none; }

        /* === FULL SCREEN PHASES (dark themed) === */
        .fullscreen-phase { position: fixed; inset: 0; z-index: 100; background: #080808; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; overflow: hidden; }

        /* Animated gradient orbs */
        @keyframes orbDrift1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(80px, -60px) scale(1.1); }
            50% { transform: translate(-40px, -100px) scale(0.95); }
            75% { transform: translate(-80px, 40px) scale(1.05); }
        }
        @keyframes orbDrift2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-100px, 50px) scale(1.08); }
            50% { transform: translate(60px, 80px) scale(0.9); }
            75% { transform: translate(100px, -40px) scale(1.12); }
        }
        @keyframes orbDrift3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(50px, 80px) scale(0.95); }
            50% { transform: translate(-80px, -50px) scale(1.1); }
            75% { transform: translate(40px, -80px) scale(1); }
        }
        .gradient-orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
        .gradient-orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%); bottom: -150px; left: 20%; animation: orbDrift1 8s ease-in-out infinite; }
        .gradient-orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%); top: -100px; right: 10%; animation: orbDrift2 10s ease-in-out infinite; }
        .gradient-orb-3 { width: 450px; height: 450px; background: radial-gradient(circle, rgba(255,255,255,0.025) 0%, transparent 70%); bottom: -120px; right: 15%; animation: orbDrift3 12s ease-in-out infinite; }

        /* Wireframe sitemap */
        .wireframe-page { background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 8px; overflow: hidden; backdrop-filter: blur(10px); }
        .wireframe-header { background: rgba(255,255,255,0.12); color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .wireframe-row { height: 10px; background: rgba(255,255,255,0.08); border-radius: 3px; margin: 6px 10px; }
        .wireframe-row:first-child { margin-top: 10px; }
        .wireframe-row:last-child { margin-bottom: 10px; }

        /* === STRUCTURE EDITOR === */
        .structure-editor { position: fixed; inset: 0; z-index: 100; background: #f4f4f8; display: flex; flex-direction: column; }
        .structure-top-bar { background: linear-gradient(135deg, #0a0a0a, #1c1c1c); padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .structure-main { flex: 1; display: flex; overflow: hidden; }
        .structure-content { flex: 1; overflow-y: auto; padding: 32px 24px; }
        .structure-sidebar { width: 320px; background: #fff; border-left: 1px solid #e5e7eb; display: flex; flex-direction: column; flex-shrink: 0; box-shadow: -4px 0 20px rgba(0,0,0,0.04); }
        .structure-sidebar-content { flex: 1; overflow-y: auto; padding: 24px; }
        .structure-sidebar-footer { border-top: 1px solid #e5e7eb; padding: 16px 24px; }
        .structure-bottom-bar { background: #fff; border-top: 1px solid #e5e7eb; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }

        /* Homepage card (expanded) */
        .homepage-card { background: #fff; border: 1.5px solid #e2e4ea; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .homepage-card-header { background: linear-gradient(135deg, #171717, #262626); color: #fff; padding: 12px 18px; display: flex; align-items: center; justify-content: space-between; font-size: 13px; font-weight: 600; }
        .homepage-section-row { padding: 12px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; cursor: default; transition: all 0.15s; }
        .homepage-section-row:hover { background: #f5f5f5; }
        .homepage-section-row:last-child { border-bottom: none; }

        /* Child page cards */
        .child-page-card { background: #fff; border: 1.5px solid #e2e4ea; border-radius: 10px; overflow: hidden; min-width: 200px; flex: 1; max-width: 260px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .child-page-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .child-page-header { padding: 10px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f0f0f0; }
        .child-page-section { padding: 8px 14px; font-size: 11px; color: #666; border-bottom: 1px solid #f5f5f5; display: flex; align-items: center; justify-content: space-between; }
        .child-page-section:last-child { border-bottom: none; }

        /* Connector lines */
        .connector-line { width: 1.5px; height: 30px; background: #d4d4d8; margin: 0 auto; }
        .fullscreen-phase .connector-line { background: rgba(255,255,255,0.2); }

        /* Add page modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 200; display: flex; align-items: center; justify-content: center; }
        .modal-box { background: #fff; border-radius: 16px; padding: 28px; width: 400px; max-width: 90vw; box-shadow: 0 25px 60px rgba(0,0,0,0.2); }

        /* Gen dots */
        .gen-dots span { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.5); margin: 0 4px; animation: pulse-glow 1.4s ease-in-out infinite; }
        .gen-dots span:nth-child(2) { animation-delay: 0.2s; }
        .gen-dots span:nth-child(3) { animation-delay: 0.4s; }

        /* Responsive */
        @media (max-width: 768px) {
            .builder-wrapper { flex-direction: column; }
            .builder-left { width: 100%; min-height: 200px; padding: 2rem; }
            .builder-right { width: 100%; }
            .builder-right-content { padding: 1.5rem; }
            .builder-bottom-bar { padding: 1rem 1.5rem; }
            .structure-sidebar { width: 100%; border-left: none; border-top: 1px solid #e5e5e5; }
            .structure-main { flex-direction: column; }
        }
    </style>
</head>
<body class="bg-white">

@if($websiteCount >= $maxWebsites && $maxWebsites > 0)
    <div class="fixed inset-0 z-[200] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
            <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Plan Limit Reached</h2>
            <p class="text-slate-500 text-sm mb-2">You've used <strong>{{ $websiteCount }}</strong> of <strong>{{ $maxWebsites }}</strong> websites on your current plan.</p>
            <p class="text-slate-400 text-sm mb-6">Upgrade your plan to create more websites.</p>
            <div class="flex items-center gap-3 justify-center">
                <a href="{{ url('/pricing') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-black/10">
                    Upgrade Plan
                </a>
                <a href="{{ route('dashboard') }}" class="border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-6 py-2.5 rounded-xl text-sm transition-all">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
@endif

<div x-data="aiBuilder()" x-cloak>

    {{-- ==================== STEPS 1-3: SPLIT SCREEN WIZARD ==================== --}}
    <div class="builder-wrapper" x-show="step <= 3" x-transition>

        {{-- LEFT PANEL --}}
        <div class="builder-left">
            <div class="left-grid-pattern"></div>
            <div class="left-gradient-orb" style="width:300px;height:300px;background:rgba(255,255,255,0.05);top:-50px;left:-50px;"></div>
            <div class="left-gradient-orb" style="width:200px;height:200px;background:rgba(255,255,255,0.04);bottom:50px;right:-30px;"></div>
            <div class="relative z-10 w-full max-w-md">
                <template x-if="step === 1">
                    <div class="step-panel text-center">
                        <div class="animate-float mb-8">
                            <svg class="w-20 h-20 mx-auto text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-4 animate-fade-up">Let's start creating<br>your website with AI</h2>
                        <p class="text-white/50 text-lg animate-fade-up" style="animation-delay:0.2s">Choose the type of website you'd like to build</p>
                    </div>
                </template>
                <template x-if="step === 2">
                    <div class="step-panel text-center">
                        <div class="animate-float mb-8">
                            <svg class="w-20 h-20 mx-auto text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-4 animate-fade-up">Tell us about<br>your business</h2>
                        <p class="text-white/50 text-lg animate-fade-up" style="animation-delay:0.2s">Select your industry so we can tailor the experience</p>
                    </div>
                </template>
                <template x-if="step === 3">
                    <div class="step-panel">
                        <h2 class="text-2xl font-bold text-white mb-6 animate-fade-up">Write a great description</h2>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/10 animate-fade-up" style="animation-delay:0.2s">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-white/70 font-semibold text-sm">Good example</span>
                            </div>
                            <p class="text-white/60 text-sm leading-relaxed">"We are a cozy Italian restaurant in downtown Chicago, known for our handmade pasta and wood-fired pizzas. We want a website that showcases our menu, allows online reservations, and tells our family story."</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="builder-right">
            <div class="builder-right-content">
                <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-400 tracking-wider uppercase">Webnewbiz AI</span>
                    <a href="{{ route('dashboard') }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:text-gray-900 hover:bg-gray-100 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></a>
                </div>

                {{-- STEP 1 --}}
                <div x-show="step === 1" x-transition class="step-panel">
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">What kind of website do you want to create?</h1>
                    <p class="text-slate-400 mb-8">Choose the type that best fits your needs.</p>
                    <div class="space-y-4">
                        <div class="radio-card" :class="websiteType === 'informational' ? 'selected' : ''" @click="websiteType = 'informational'">
                            <div class="radio-dot"></div>
                            <div><div class="flex items-center gap-3"><svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg><div><p class="font-semibold text-slate-900">I want to create an informational website</p><p class="text-sm text-slate-400 mt-0.5">Business sites, portfolios, blogs, landing pages</p></div></div></div>
                        </div>
                        <div class="radio-card" :class="websiteType === 'ecommerce' ? 'selected' : ''" @click="websiteType = 'ecommerce'">
                            <div class="radio-dot"></div>
                            <div><div class="flex items-center gap-3"><svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg><div><p class="font-semibold text-slate-900">I want to create a website with an online store</p><p class="text-sm text-slate-400 mt-0.5">E-commerce, product catalog, shopping cart</p></div></div></div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div x-show="step === 2" x-transition class="step-panel">
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Choose your design style</h1>
                    <p class="text-slate-400 mb-6">Pick a premium layout or let us auto-match based on your business type.</p>

                    {{-- Layout Cards --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <template x-for="layout in premiumLayouts" :key="layout.slug">
                            <div @click="selectLayout(layout)" :class="selectedLayout === layout.slug ? 'ring-2 ring-gray-900 shadow-lg' : 'hover:shadow-md hover:-translate-y-0.5'" class="border border-slate-200 rounded-xl p-4 cursor-pointer transition-all duration-200">
                                {{-- Color preview strip --}}
                                <div class="h-2 rounded-full mb-3 overflow-hidden flex">
                                    <div class="flex-1" :style="'background:' + layout.primary"></div>
                                    <div class="w-1/3" :style="'background:' + layout.accent"></div>
                                </div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-bold text-gray-900" x-text="layout.name"></span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold" :class="layout.is_dark ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'" x-text="layout.style"></span>
                                </div>
                                <p class="text-[11px] text-gray-400 leading-snug mb-2" x-text="layout.description"></p>
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="tag in layout.best_for.slice(0, 3)" :key="tag">
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-gray-50 text-gray-400 border border-gray-100" x-text="tag"></span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Auto-match option --}}
                    <div @click="selectedLayout = 'auto'; businessType = businessType || 'Other'" :class="selectedLayout === 'auto' ? 'ring-2 ring-gray-900 bg-gray-50' : 'hover:bg-gray-50'" class="border border-slate-200 rounded-xl p-4 cursor-pointer transition-all mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-900 to-gray-600 flex items-center justify-center text-white text-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Auto-match</p>
                                <p class="text-xs text-gray-400">AI picks the best layout for your business type</p>
                            </div>
                        </div>
                    </div>

                    {{-- Business type selector --}}
                    <div class="relative mb-3">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Business Type</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="searchQuery" placeholder="Search business types..." class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                    </div>
                    <div class="type-list border border-slate-200 rounded-xl overflow-hidden" style="max-height:180px;">
                        <template x-for="bType in filteredBusinessTypes" :key="bType">
                            <div class="type-item" :class="businessType === bType ? 'selected' : ''" @click="businessType = bType">
                                <div class="flex items-center justify-between"><span x-text="bType" class="text-sm"></span><svg x-show="businessType === bType" class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div x-show="step === 3" x-transition class="step-panel">
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">What's your site about?</h1>
                    <p class="text-slate-400 mb-8">Help our AI understand your business to create the perfect website.</p>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Business / Site name</label>
                        <input type="text" x-model="businessName" maxlength="200" placeholder="e.g., Marco's Italian Kitchen" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-slate-700">Tell us about your website</label>
                            <button type="button" @click="enhanceWithAI()" :disabled="isEnhancing || businessDescription.trim().length < 10"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all disabled:opacity-30 disabled:cursor-not-allowed bg-gray-100 text-gray-900 hover:bg-gray-200 border border-slate-200">
                                <svg x-show="!isEnhancing" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <svg x-show="isEnhancing" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span x-text="isEnhancing ? 'Enhancing...' : 'Enhance with AI'"></span>
                            </button>
                        </div>
                        <textarea x-model="businessDescription" rows="5" maxlength="1000" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none" placeholder="Describe your business, target audience, and what you want on your website."></textarea>
                    </div>
                </div>
            </div>

            {{-- BOTTOM BAR --}}
            <div class="builder-bottom-bar">
                <button type="button" @click="prevStep()" x-show="step > 1" class="flex items-center gap-2 text-slate-400 hover:text-gray-900 font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
                </button>
                <div x-show="step === 1"></div>
                <div class="step-dots">
                    <template x-for="i in 3" :key="i"><div class="step-dot-group"><div class="dot" :class="{ 'active': step === i, 'done': step > i }"></div></div></template>
                </div>
                <button type="button" @click="nextStep()" :disabled="!canProceed" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 disabled:bg-slate-200 disabled:from-slate-200 disabled:to-slate-200 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-lg text-sm">
                    Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== STEP 4: FULL SCREEN AI ANALYZING PHASES ==================== --}}
    <div x-show="step === 4" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fullscreen-phase">
        <div class="fullscreen-grid"></div>
        <div class="gradient-orb gradient-orb-1"></div>
        <div class="gradient-orb gradient-orb-2"></div>
        <div class="gradient-orb gradient-orb-3"></div>

        {{-- Diamond icon top-left --}}
        <div class="absolute top-6 left-6">
            <svg class="w-6 h-6 text-white/30" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 12l10 10 10-10L12 2zm0 3.41L18.59 12 12 18.59 5.41 12 12 5.41z"/></svg>
        </div>

        <div class="relative z-10 max-w-2xl w-full text-center">
            {{-- Phase 1: Analyzing --}}
            <div x-show="aiPhase === 1" x-transition class="animate-fade-in">
                <div class="w-16 h-16 bg-white/[0.06] backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 border border-white/[0.08]">
                    <svg class="w-8 h-8 text-white/60 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h1 class="text-sm font-bold tracking-[0.2em] uppercase text-white/90 mb-6">AI IS ANALYZING YOUR INPUT TO CRAFT THE PERFECT WEBSITE</h1>
                <p class="text-white/40 text-sm italic" x-text="businessDescription.substring(0, 120) + (businessDescription.length > 120 ? '...' : '')"></p>
            </div>

            {{-- Phase 2: Optimizing content --}}
            <div x-show="aiPhase === 2" x-transition class="animate-fade-in">
                <div class="w-16 h-16 bg-white/[0.06] backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 border border-white/[0.08]">
                    <svg class="w-8 h-8 text-white/60 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h1 class="text-sm font-bold tracking-[0.2em] uppercase text-white/90 mb-6">OPTIMIZING YOUR CONTENT FOR CLARITY AND IMPACT</h1>
                <p class="text-white/50 text-sm leading-relaxed max-w-lg mx-auto" x-text="enhancedDescription || businessDescription"></p>
            </div>

            {{-- Phase 3: Building & generating --}}
            <div x-show="aiPhase === 3" x-transition class="animate-fade-in">
                <div class="w-16 h-16 bg-white/[0.06] backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 border border-white/[0.08]">
                    <svg class="w-8 h-8 text-white/60 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </div>
                <h1 class="text-sm font-bold tracking-[0.2em] uppercase text-white/90 mb-6" x-text="generationMessage || 'BUILDING YOUR WEBSITE...'"></h1>
                <p class="text-white/40 text-sm">AI is selecting the perfect theme and generating your content</p>
            </div>
        </div>
    </div>

    {{-- ==================== STEP 5: INTERACTIVE STRUCTURE EDITOR ==================== --}}
    <div x-show="step === 5" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="structure-editor">

        {{-- Top bar --}}
        <div class="structure-top-bar">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 12l10 10 10-10L12 2zm0 3.41L18.59 12 12 18.59 5.41 12 12 5.41z"/></svg>
                <span class="text-sm font-semibold text-white">AI Website Builder</span>
                <span class="text-xs text-gray-400/60 hidden sm:inline">&middot; Customize your site structure</span>
            </div>
            <a href="{{ route('dashboard') }}" class="text-gray-400/70 hover:text-white text-sm transition-colors">Cancel</a>
        </div>

        <div class="structure-main">
            {{-- Main content area --}}
            <div class="structure-content">
                <div class="max-w-3xl mx-auto">

                    {{-- Error message --}}
                    <div x-show="errorMessage" x-transition class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    {{-- Homepage card (expanded with all sections) --}}
                    <div class="homepage-card mb-4">
                        <div class="homepage-card-header">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                                <span x-text="pages[0]?.title || 'Homepage'"></span>
                            </div>
                            <span class="text-white/50 text-xs">Click + to add a new section</span>
                        </div>
                        <div>
                            <template x-for="(sec, si) in pages[0]?.sections || []" :key="sec.type + '-' + si">
                                <div class="homepage-section-row group"
                                     draggable="true"
                                     @dragstart="dragStart(0, si, $event)"
                                     @dragover.prevent="dragOver(0, si, $event)"
                                     @dragleave="dragLeave($event)"
                                     @drop.prevent="drop(0, si, $event)"
                                     @dragend="dragEnd($event)"
                                     :class="{'bg-blue-50 border-blue-200': dragOverIndex === si && dragOverPage === 0}">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-300 cursor-grab active:cursor-grabbing opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                        <span class="text-sm text-slate-700" x-text="sec.label"></span>
                                    </div>
                                    <button type="button" @click="removeSection(0, si)" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4 text-slate-300 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            {{-- Add section to homepage --}}
                            <div class="px-5 py-3">
                                <div x-show="!showSectionPicker || sectionPickerPage !== 0">
                                    <button type="button" @click="showSectionPicker = true; sectionPickerPage = 0" class="w-full flex items-center justify-center gap-1.5 py-2.5 text-xs font-medium text-slate-400 hover:text-gray-900 border border-dashed border-slate-200 hover:border-slate-400 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add Section
                                    </button>
                                </div>
                                <div x-show="showSectionPicker && sectionPickerPage === 0" x-transition>
                                    <div class="grid grid-cols-3 gap-2 mb-2">
                                        <template x-for="st in availableSectionTypes" :key="st.type">
                                            <button type="button" @click="addSection(0, st.type, st.label); showSectionPicker = false" class="flex flex-col items-center gap-1 px-2 py-2.5 text-center rounded-lg border border-slate-200 hover:border-gray-400 hover:bg-gray-50 hover:text-gray-900 transition-all group" :title="st.desc">
                                                <svg class="w-4 h-4 text-slate-400 group-hover:text-gray-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="st.icon"/></svg>
                                                <span class="text-[10px] leading-tight text-slate-500 group-hover:text-gray-900" x-text="st.label"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <button type="button" @click="showSectionPicker = false" class="text-xs text-slate-400 hover:text-slate-600">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- + Add Page button --}}
                    <div class="text-center mb-6">
                        <button type="button" @click="showAddPageModal = true; $nextTick(() => $refs.newPageInput.focus())" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-gray-50 text-slate-700 hover:text-gray-900 font-semibold text-sm rounded-lg transition-all border border-slate-200 hover:border-gray-400 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Page
                        </button>
                    </div>

                    {{-- Connector line --}}
                    <div class="connector-line" x-show="pages.length > 1"></div>

                    {{-- Child page cards in a row --}}
                    <div class="flex gap-4 justify-center flex-wrap" x-show="pages.length > 1">
                        <template x-for="(page, pi) in pages.slice(1)" :key="pi + 1">
                            <div class="child-page-card" :style="'border-left: 3px solid ' + childPageColor(pi)">
                                <div class="child-page-header">
                                    <span x-text="page.title" class="truncate"></span>
                                    <button type="button" @click="removePage(pi + 1)" class="flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-slate-300 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <template x-for="(sec, si) in page.sections" :key="sec.type + '-' + si">
                                    <div class="child-page-section group"
                                         draggable="true"
                                         @dragstart="dragStart(pi + 1, si, $event)"
                                         @dragover.prevent="dragOver(pi + 1, si, $event)"
                                         @dragleave="dragLeave($event)"
                                         @drop.prevent="drop(pi + 1, si, $event)"
                                         @dragend="dragEnd($event)"
                                         :class="{'bg-blue-50': dragOverIndex === si && dragOverPage === (pi + 1)}">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 text-slate-300 cursor-grab active:cursor-grabbing opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                            <span class="truncate" x-text="sec.label"></span>
                                        </div>
                                        <button type="button" @click="removeSection(pi + 1, si)" class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                            <svg class="w-3 h-3 text-slate-300 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                                {{-- Add section to child page --}}
                                <div class="px-3 py-2">
                                    <button type="button" @click="showSectionPicker = true; sectionPickerPage = pi + 1" x-show="!showSectionPicker || sectionPickerPage !== (pi + 1)" class="w-full text-center py-2 text-[11px] font-medium text-slate-400 hover:text-gray-900 border border-dashed border-slate-200 hover:border-slate-400 rounded-lg transition-colors">+ Add Section</button>
                                    <div x-show="showSectionPicker && sectionPickerPage === (pi + 1)" x-transition>
                                        <div class="grid grid-cols-2 gap-1.5 mb-2 max-h-52 overflow-y-auto py-1">
                                            <template x-for="st in availableSectionTypes" :key="st.type">
                                                <button type="button" @click="addSection(pi + 1, st.type, st.label); showSectionPicker = false" class="flex items-center gap-1.5 px-2 py-2 text-left rounded-lg border border-slate-200 hover:border-gray-400 hover:bg-gray-50 transition-all group" :title="st.desc">
                                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-gray-700 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="st.icon"/></svg>
                                                    <span class="text-[11px] text-slate-600 group-hover:text-gray-900 truncate" x-text="st.label"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <button type="button" @click="showSectionPicker = false" class="text-[10px] text-slate-400 hover:text-slate-600">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="structure-sidebar">
                <div class="structure-sidebar-content">
                    <h2 class="text-lg font-bold text-slate-900 mb-1">Edit your site's structure</h2>
                    <p class="text-xs text-slate-400 mb-6">The structure is based on your brief</p>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 block">Site Info</label>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-slate-500 mb-1 block">Site title</label>
                                    <input type="text" x-model="businessName" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-900">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 mb-1 block">Type</label>
                                    <div class="text-sm text-slate-800 font-medium capitalize" x-text="businessType || websiteType"></div>
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 mb-1 block">Pages</label>
                                    <div class="text-sm text-slate-800 font-medium"><span x-text="pages.length"></span> pages</div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        {{-- Design Layout in sidebar --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">Design Layout</label>
                            <div class="space-y-1.5 max-h-64 overflow-y-auto">
                                <template x-for="layout in premiumLayouts" :key="layout.slug">
                                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg cursor-pointer border transition-all"
                                         :class="selectedLayout === layout.slug ? 'border-gray-900 bg-slate-50' : 'border-transparent hover:bg-slate-50'"
                                         @click="selectLayout(layout)">
                                        <div class="w-4 h-4 rounded-full flex-shrink-0" :style="'background:' + layout.primary"></div>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm font-medium block truncate" x-text="layout.name"></span>
                                            <span class="text-[10px] text-slate-400" x-text="layout.style"></span>
                                        </div>
                                        <div x-show="selectedLayout === layout.slug" class="w-4 h-4 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </template>
                                <div class="flex items-center gap-2 px-3 py-2 rounded-lg cursor-pointer border transition-all"
                                     :class="selectedLayout === 'auto' ? 'border-gray-900 bg-slate-50' : 'border-transparent hover:bg-slate-50'"
                                     @click="selectedLayout = 'auto'">
                                    <div class="w-4 h-4 rounded-full flex-shrink-0 bg-gradient-to-br from-gray-900 to-gray-500"></div>
                                    <span class="text-sm font-medium">Auto-match</span>
                                    <div x-show="selectedLayout === 'auto'" class="ml-auto w-4 h-4 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="structure-sidebar-footer">
                    <button type="button" @click="startGeneration()" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg text-sm transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Generate
                    </button>
                </div>
            </div>
        </div>

        {{-- Bottom bar with tabs --}}
        <div class="structure-bottom-bar">
            <div class="flex items-center gap-6">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider cursor-default">SITE INFO</span>
                <span class="text-xs font-semibold text-gray-900 uppercase tracking-wider border-b-2 border-gray-900 pb-1 cursor-default">PAGES</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-full"><span x-text="pages.length"></span> pages</span>
                <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-full"><span x-text="pages.reduce((t,p) => t + p.sections.length, 0)"></span> sections</span>
            </div>
        </div>
    </div>

    {{-- ==================== STEP 6: GENERATING ANIMATION ==================== --}}
    <div x-show="step === 6" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fullscreen-phase">
        <div class="fullscreen-grid"></div>
        <div class="gradient-orb gradient-orb-1"></div>
        <div class="gradient-orb gradient-orb-2"></div>
        <div class="gradient-orb gradient-orb-3"></div>
        <div class="relative z-10 text-center">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 border border-white/10">
                <svg class="w-10 h-10 text-gray-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h1 class="text-sm font-bold tracking-[0.2em] uppercase text-white/90 mb-3">GENERATING YOUR PERSONALIZED AI WEBSITE</h1>
            <p class="text-white/40 mb-8">Please wait while we craft something amazing...</p>
            <div class="gen-dots"><span></span><span></span><span></span></div>
            <p class="text-sm text-white/30 mt-6" x-text="generationMessage"></p>
        </div>
    </div>

    {{-- ==================== ADD PAGE MODAL ==================== --}}
    <div x-show="showAddPageModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="modal-overlay" @click.self="showAddPageModal = false" @keydown.escape.window="showAddPageModal = false">
        <div class="modal-box animate-scale-in" style="width:480px;">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Add a new page</h3>
            <p class="text-xs text-slate-400 mb-4">Name your page, then pick which sections to include</p>
            <input type="text" x-model="newPageTitle" @keydown.enter="addCustomPage()" placeholder="e.g., Gallery, Blog, FAQ..." class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm mb-4" x-ref="newPageInput">

            {{-- Section picker in modal --}}
            <div x-show="newPageTitle.trim().length > 0" x-transition class="mb-4">
                <p class="text-xs font-semibold text-slate-500 mb-2">Select sections for this page:</p>
                <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto">
                    <template x-for="st in availableSectionTypes" :key="st.type">
                        <button type="button" @click="toggleNewPageSection(st.type, st.label)"
                            class="flex flex-col items-center gap-1 px-2 py-2.5 text-center rounded-lg border transition-all group"
                            :class="newPageSections.some(s => s.type === st.type) ? 'border-gray-900 bg-gray-50 text-gray-900' : 'border-slate-200 hover:border-gray-400 hover:bg-gray-50'"
                            :title="st.desc">
                            <svg class="w-4 h-4 transition-colors" :class="newPageSections.some(s => s.type === st.type) ? 'text-gray-900' : 'text-slate-400 group-hover:text-gray-700'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="st.icon"/></svg>
                            <span class="text-[10px] leading-tight" x-text="st.label"></span>
                        </button>
                    </template>
                </div>
                <p class="text-[10px] text-slate-400 mt-2" x-show="newPageSections.length > 0"><span x-text="newPageSections.length"></span> section(s) selected</p>
            </div>

            <button type="button" @click="addCustomPage()" :disabled="!newPageTitle.trim()" class="w-full bg-gray-900 hover:bg-gray-800 disabled:bg-slate-200 disabled:cursor-not-allowed text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors">
                Add Page
            </button>
        </div>
    </div>

    {{-- Hidden form --}}
    <form id="builderForm" x-ref="form" action="{{ route('builder.generate') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="name" :value="businessName">
        <input type="hidden" name="business_type" :value="finalBusinessType">
        <input type="hidden" name="prompt" :value="enhancedDescription || businessDescription">
        <input type="hidden" name="style" :value="finalStyle">
        <input type="hidden" name="theme" :value="selectedLayout || 'auto'">
        <input type="hidden" name="pages_structure" :value="JSON.stringify(pages)">
    </form>
</div>

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-[300] bg-red-50 border border-red-200 text-red-700 px-6 py-3 rounded-xl shadow-lg">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
</div>
@endif

<script>
function aiBuilder() {
    return {
        step: 1, websiteType: '', businessType: '', businessName: '', businessDescription: '',
        searchQuery: '', isGenerating: false, isEnhancing: false,
        selectedLayout: '', premiumLayouts: @json($layouts ?? []),
        generationMessage: '', errorMessage: '', enhancedDescription: '',
        aiPhase: 1, showSectionPicker: false, sectionPickerPage: -1,
        showAddPageModal: false, newPageTitle: '', newPageSections: [],
        dragSourcePage: -1, dragSourceIndex: -1, dragOverPage: -1, dragOverIndex: -1,
        pages: [],

        availableSectionTypes: [
            { type: 'hero', label: 'Hero Banner', desc: 'Page header with title and subtitle', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4' },
            { type: 'features', label: 'Features', desc: 'Highlight key features or services', icon: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z' },
            { type: 'about_preview', label: 'About Preview', desc: 'Brief company introduction', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            { type: 'testimonials', label: 'Testimonials', desc: 'Customer reviews and quotes', icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' },
            { type: 'cta', label: 'Call to Action', desc: 'Encourage visitors to take action', icon: 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z' },
            { type: 'gallery', label: 'Gallery', desc: 'Image carousel or grid', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
            { type: 'pricing', label: 'Pricing', desc: 'Pricing plans and packages', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            { type: 'team', label: 'Team', desc: 'Team member profiles', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
            { type: 'faq', label: 'FAQ', desc: 'Frequently asked questions', icon: 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            { type: 'contact_form', label: 'Contact Form', desc: 'Contact form with fields', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
            { type: 'content', label: 'Text Content', desc: 'Rich text content block', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
            { type: 'stats', label: 'Statistics', desc: 'Key numbers and metrics', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
            { type: 'process', label: 'How It Works', desc: 'Step-by-step process', icon: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' },
        ],

        pagePresets: {
            '_default': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'features', label: 'Features' }, { type: 'about_preview', label: 'About Preview' }, { type: 'stats', label: 'Statistics' }, { type: 'process', label: 'How It Works' }, { type: 'testimonials', label: 'Testimonials' }, { type: 'cta', label: 'Call to Action' }] },
                { title: 'About Us', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Story' }, { type: 'team', label: 'Our Team' }] },
                { title: 'Services', slug: 'services', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'Service List' }, { type: 'cta', label: 'Call to Action' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Contact Form' }, { type: 'content', label: 'Contact Details' }] },
            ],
            'Restaurant': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'about_preview', label: 'Our Story' }, { type: 'features', label: 'Specialties' }, { type: 'process', label: 'How to Order' }, { type: 'gallery', label: 'Photo Gallery' }, { type: 'testimonials', label: 'Reviews' }, { type: 'cta', label: 'Reserve a Table' }] },
                { title: 'Menu', slug: 'menu', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'Menu Items' }] },
                { title: 'About Us', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Story' }, { type: 'team', label: 'Our Chefs' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Reservation Form' }, { type: 'content', label: 'Location & Hours' }] },
            ],
            'E-commerce': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'features', label: 'Featured Products' }, { type: 'about_preview', label: 'Why Choose Us' }, { type: 'stats', label: 'Store Stats' }, { type: 'process', label: 'How to Shop' }, { type: 'testimonials', label: 'Customer Reviews' }, { type: 'cta', label: 'Shop Now' }] },
                { title: 'Shop', slug: 'shop', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'Product Catalog' }] },
                { title: 'About Us', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Brand Story' }, { type: 'stats', label: 'Brand Statistics' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Contact Form' }, { type: 'faq', label: 'Shipping FAQ' }] },
            ],
            'Portfolio': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'gallery', label: 'Featured Work' }, { type: 'about_preview', label: 'About Me' }, { type: 'process', label: 'My Process' }, { type: 'stats', label: 'Stats' }, { type: 'testimonials', label: 'Client Reviews' }, { type: 'cta', label: 'Let\'s Work Together' }] },
                { title: 'Portfolio', slug: 'portfolio', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'gallery', label: 'Project Gallery' }] },
                { title: 'About', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'My Background' }, { type: 'features', label: 'Skills' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Get in Touch' }] },
            ],
            'Agency': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'features', label: 'Our Services' }, { type: 'stats', label: 'Results & Stats' }, { type: 'process', label: 'Our Process' }, { type: 'testimonials', label: 'Client Testimonials' }, { type: 'cta', label: 'Get a Quote' }] },
                { title: 'Services', slug: 'services', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'Service Details' }, { type: 'pricing', label: 'Pricing Plans' }] },
                { title: 'Case Studies', slug: 'portfolio', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'gallery', label: 'Case Studies' }] },
                { title: 'About Us', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Story' }, { type: 'team', label: 'Meet the Team' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Contact Form' }] },
            ],
            'SaaS': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'features', label: 'Key Features' }, { type: 'stats', label: 'Platform Stats' }, { type: 'pricing', label: 'Pricing Plans' }, { type: 'testimonials', label: 'Customer Reviews' }, { type: 'cta', label: 'Start Free Trial' }] },
                { title: 'Features', slug: 'services', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'All Features' }] },
                { title: 'Pricing', slug: 'pricing', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'pricing', label: 'Plans & Pricing' }, { type: 'faq', label: 'Pricing FAQ' }] },
                { title: 'About', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Mission' }, { type: 'team', label: 'The Team' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Contact Sales' }] },
            ],
            'Fitness': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'features', label: 'Programs' }, { type: 'about_preview', label: 'About Our Gym' }, { type: 'gallery', label: 'Facility Photos' }, { type: 'testimonials', label: 'Member Reviews' }, { type: 'pricing', label: 'Membership Plans' }, { type: 'cta', label: 'Join Now' }] },
                { title: 'Programs', slug: 'services', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'Training Programs' }] },
                { title: 'About', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Story' }, { type: 'team', label: 'Our Trainers' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Contact Form' }, { type: 'content', label: 'Location & Hours' }] },
            ],
            'Healthcare': [
                { title: 'Home', slug: 'home', sections: [{ type: 'hero', label: 'Hero Banner' }, { type: 'features', label: 'Our Services' }, { type: 'about_preview', label: 'Why Choose Us' }, { type: 'process', label: 'How It Works' }, { type: 'stats', label: 'Our Numbers' }, { type: 'team', label: 'Our Doctors' }, { type: 'testimonials', label: 'Patient Reviews' }, { type: 'cta', label: 'Book Appointment' }] },
                { title: 'Services', slug: 'services', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'features', label: 'Medical Services' }] },
                { title: 'About Us', slug: 'about', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Our Practice' }, { type: 'team', label: 'Doctors & Staff' }] },
                { title: 'Contact', slug: 'contact', sections: [{ type: 'hero', label: 'Page Header' }, { type: 'contact_form', label: 'Appointment Request' }, { type: 'content', label: 'Location & Hours' }] },
            ],
        },

        businessTypes: @json($businessTypes ?? []),

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const prompt = urlParams.get('prompt');
            if (prompt && prompt.trim()) {
                this.businessDescription = prompt.trim();
                const lower = prompt.toLowerCase();
                this.websiteType = (lower.includes('store') || lower.includes('shop') || lower.includes('ecommerce') || lower.includes('e-commerce') || lower.includes('sell') || lower.includes('product')) ? 'ecommerce' : 'informational';
                const typeMap = { 'restaurant': 'Restaurant', 'cafe': 'Restaurant', 'food': 'Food & Beverage', 'kitchen': 'Restaurant', 'shop': 'E-commerce', 'store': 'E-commerce', 'clothing': 'Fashion', 'fashion': 'Fashion', 'agency': 'Agency', 'marketing': 'Marketing', 'portfolio': 'Portfolio', 'freelance': 'Portfolio', 'blog': 'Blog', 'fitness': 'Fitness', 'gym': 'Fitness', 'photo': 'Photography', 'lawyer': 'Legal', 'tech': 'Technology', 'saas': 'SaaS', 'software': 'SaaS', 'consult': 'Consulting', 'health': 'Healthcare', 'medical': 'Healthcare', 'education': 'Education', 'travel': 'Travel', 'beauty': 'Beauty & Spa', 'salon': 'Beauty & Spa', 'construction': 'Construction', 'nonprofit': 'Nonprofit' };
                for (const [keyword, type] of Object.entries(typeMap)) { if (lower.includes(keyword)) { this.businessType = type; break; } }
                if (!this.businessType) this.businessType = 'Other';
                const nameMatch = prompt.match(/^(?:a|an|the|my)?\s*(.+?)(?:\s+(?:with|that|for|in|offering|helping|selling|showcasing)\b)/i);
                if (nameMatch) this.businessName = nameMatch[1].trim().substring(0, 80);
                this.selectedLayout = 'auto';
                this.step = 3;
                window.history.replaceState({}, '', window.location.pathname);
            }
            @if(old('name')) this.businessName = '{{ old("name") }}'; @endif
        },

        get filteredBusinessTypes() { if (!this.searchQuery) return this.businessTypes; const q = this.searchQuery.toLowerCase(); return this.businessTypes.filter(t => t.toLowerCase().includes(q)); },
        selectLayout(layout) {
            this.selectedLayout = layout.slug;
            if (!this.businessType) this.businessType = layout.best_for[0] || 'Other';
        },
        get canProceed() { if (this.step === 1) return this.websiteType !== ''; if (this.step === 2) return (this.selectedLayout !== '' || this.businessType !== ''); if (this.step === 3) return this.businessName.trim() !== ''; return true; },
        get finalBusinessType() { if (this.websiteType === 'ecommerce') return 'ecommerce-' + this.businessType.toLowerCase().replace(/[\s\/]+/g, '-'); return this.businessType.toLowerCase().replace(/[\s\/]+/g, '-'); },
        get finalStyle() { return this.selectedLayout || 'auto'; },

        childPageColor(index) {
            const colors = ['#171717', '#404040', '#525252', '#737373', '#262626', '#3f3f46'];
            return colors[index % colors.length];
        },

        initPages() {
            const bt = this.businessType;
            const presets = this.pagePresets[bt] || this.pagePresets['_default'];
            this.pages = JSON.parse(JSON.stringify(presets));
        },

        addSection(pageIndex, type, label) { this.pages[pageIndex].sections.push({ type, label }); },
        removeSection(pageIndex, sectionIndex) { this.pages[pageIndex].sections.splice(sectionIndex, 1); },
        removePage(index) { this.pages.splice(index, 1); },

        toggleNewPageSection(type, label) {
            const idx = this.newPageSections.findIndex(s => s.type === type);
            if (idx >= 0) { this.newPageSections.splice(idx, 1); }
            else { this.newPageSections.push({ type, label }); }
        },

        addCustomPage() {
            if (!this.newPageTitle.trim()) return;
            const title = this.newPageTitle.trim();
            const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            let sections = this.newPageSections.length > 0
                ? [{ type: 'hero', label: 'Page Header' }, ...JSON.parse(JSON.stringify(this.newPageSections))]
                : [{ type: 'hero', label: 'Page Header' }, { type: 'content', label: 'Text Content' }];
            this.pages.push({ title, slug, sections });
            this.newPageTitle = '';
            this.newPageSections = [];
            this.showAddPageModal = false;
            // Auto-open section picker for the new page
            this.$nextTick(() => {
                this.showSectionPicker = true;
                this.sectionPickerPage = this.pages.length - 1;
            });
        },

        // Drag-to-reorder sections within a page
        dragStart(pageIndex, sectionIndex, e) {
            this.dragSourcePage = pageIndex;
            this.dragSourceIndex = sectionIndex;
            e.dataTransfer.effectAllowed = 'move';
            e.target.style.opacity = '0.5';
        },
        dragOver(pageIndex, sectionIndex, e) {
            if (this.dragSourcePage !== pageIndex) return;
            this.dragOverPage = pageIndex;
            this.dragOverIndex = sectionIndex;
        },
        dragLeave(e) {
            this.dragOverPage = -1;
            this.dragOverIndex = -1;
        },
        drop(pageIndex, sectionIndex, e) {
            if (this.dragSourcePage !== pageIndex) return;
            const sections = this.pages[pageIndex].sections;
            const moved = sections.splice(this.dragSourceIndex, 1)[0];
            sections.splice(sectionIndex, 0, moved);
            this.dragSourcePage = -1;
            this.dragSourceIndex = -1;
            this.dragOverPage = -1;
            this.dragOverIndex = -1;
        },
        dragEnd(e) {
            e.target.style.opacity = '1';
            this.dragSourcePage = -1;
            this.dragSourceIndex = -1;
            this.dragOverPage = -1;
            this.dragOverIndex = -1;
        },

        nextStep() {
            if (!this.canProceed) return;
            if (this.step === 3) { this.startAIPhases(); return; }
            if (this.step < 3) this.step++;
        },
        prevStep() { if (this.step > 1) this.step--; },

        async startAIPhases() {
            this.aiPhase = 1;
            this.step = 4;

            // Phase 1: Analyzing (2s)
            await this.wait(2000);

            // Phase 2: Optimizing content - call AI enhance
            this.aiPhase = 2;
            if (this.businessDescription.trim().length >= 10) {
                try {
                    const response = await fetch('{{ route("builder.enhance") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ description: this.businessDescription, business_name: this.businessName, business_type: this.businessType }),
                    });
                    const data = await response.json();
                    if (data.success && data.enhanced) { this.enhancedDescription = data.enhanced; }
                } catch (e) { console.error('Enhance failed:', e); }
            }
            await this.wait(2000);

            // Phase 3: Building & submitting
            this.aiPhase = 3;
            await this.wait(1500);

            // Auto-submit the form — AI picks the theme automatically
            this.startGeneration();
        },

        async fetchSitePlan() {
            if (this.businessDescription.trim().length < 10) return null;
            try {
                const response = await fetch('{{ route("builder.plan-site") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ business_type: this.businessType, business_name: this.businessName, description: this.businessDescription }),
                });
                const data = await response.json();
                if (data.success && data.pages && data.pages.length >= 2) {
                    return data.pages;
                }
            } catch (e) { console.error('Site plan fetch failed:', e); }
            return null;
        },

        wait(ms) { return new Promise(resolve => setTimeout(resolve, ms)); },

        async startGeneration() {
            this.isGenerating = true; this.errorMessage = '';
            const messages = ['Selecting the perfect theme...', 'Generating content with AI...', 'Cloning & customizing site...', 'Setting up WordPress...', 'Preparing your website...'];
            let msgIdx = 0; this.generationMessage = messages[0];
            const msgTimer = setInterval(() => { msgIdx++; if (msgIdx < messages.length) this.generationMessage = messages[msgIdx]; }, 4000);
            try {
                const formData = new FormData();
                formData.append('name', this.businessName);
                formData.append('business_type', this.websiteType === 'ecommerce' ? 'ecommerce-' + this.businessType : this.businessType);
                formData.append('prompt', this.enhancedDescription || this.businessDescription);
                formData.append('style', this.selectedLayout || 'auto');
                formData.append('theme', this.selectedLayout || 'auto');

                const response = await fetch('{{ route("builder.generate") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: formData });
                clearInterval(msgTimer); const data = await response.json();
                if (data.success && data.redirect) window.location.href = data.redirect;
                else { this.step = 3; this.isGenerating = false; this.errorMessage = data.message || 'Something went wrong.'; }
            } catch (error) { clearInterval(msgTimer); this.step = 3; this.isGenerating = false; this.errorMessage = 'Network error. Please try again.'; }
        },

        async enhanceWithAI() {
            if (this.isEnhancing || this.businessDescription.trim().length < 10) return;
            this.isEnhancing = true;
            try {
                const response = await fetch('{{ route("builder.enhance") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify({ description: this.businessDescription, business_name: this.businessName, business_type: this.businessType }) });
                const data = await response.json();
                if (data.success && data.enhanced) { this.businessDescription = data.enhanced; }
            } catch (e) { console.error('Enhance failed:', e); }
            finally { this.isEnhancing = false; }
        },
    };
}
</script>
</body>
</html>
