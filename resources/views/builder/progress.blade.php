<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Building Your Website - Webnewbiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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

        @keyframes fadeUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes checkDraw { from { stroke-dashoffset: 24; } to { stroke-dashoffset: 0; } }
        @keyframes shimmer-slide { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
        @keyframes pulse-ring { 0% { transform: scale(0.95); opacity: 0.4; } 50% { transform: scale(1.1); opacity: 0; } 100% { transform: scale(0.95); opacity: 0; } }
        @keyframes tipFade {
            0%, 18% { opacity: 0; transform: translateY(8px); }
            22%, 48% { opacity: 1; transform: translateY(0); }
            52%, 100% { opacity: 0; transform: translateY(-8px); }
        }

        .animate-fade-up { animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }

        .progress-bar-fill {
            position: relative; overflow: hidden;
            background: linear-gradient(90deg, #fff, #d1d5db, #fff);
        }
        .progress-bar-fill::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer-slide 2s ease-in-out infinite;
        }

        .step-line { position: absolute; left: 19px; top: 40px; bottom: -2px; width: 2px; background: rgba(255,255,255,0.06); overflow: hidden; }
        .step-line-fill { width: 100%; height: 0%; background: linear-gradient(to bottom, #fff, #9ca3af); transition: height 0.8s cubic-bezier(0.16, 1, 0.3, 1); border-radius: 2px; }
        .step-line.done .step-line-fill { height: 100%; }

        .step-icon-active::before {
            content: ''; position: absolute; inset: -4px; border-radius: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            animation: pulse-ring 2s ease-out infinite;
        }

        .tip-item { animation: tipFade 15s ease-in-out infinite; }
        .tip-item:nth-child(2) { animation-delay: 5s; }
        .tip-item:nth-child(3) { animation-delay: 10s; }

        .progress-ring-track { transition: stroke-dashoffset 1.2s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-[#080808]">

{{-- Background --}}
<div class="fixed inset-0 pointer-events-none overflow-hidden">
    <div class="fixed inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-1/3 -left-32 w-[500px] h-[500px] bg-white/[0.02] rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-20 right-0 w-[400px] h-[400px] bg-white/[0.015] rounded-full blur-[120px]"></div>
</div>

<div x-data="buildProgress()" x-cloak class="relative z-10 w-full max-w-lg mx-auto px-5 py-10">

    {{-- Main Card --}}
    <div class="bg-white/[0.04] backdrop-blur-2xl rounded-3xl border border-white/[0.08] shadow-2xl shadow-black/40 overflow-hidden animate-fade-up">

        {{-- Top Hero with Ring --}}
        <div class="relative px-8 pt-12 pb-10 text-center overflow-hidden">
            <div class="relative z-10">
                {{-- Progress Ring --}}
                <div class="relative w-32 h-32 mx-auto mb-7">
                    <div class="absolute inset-0 rounded-full"
                         :style="'box-shadow: 0 0 40px 8px rgba(255, 255, 255, ' + (progressPercent / 600) + ')'"></div>

                    <svg class="w-32 h-32 -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="52" stroke="rgba(255,255,255,0.06)" stroke-width="5" fill="none"/>
                        <circle cx="60" cy="60" r="52"
                            stroke="url(#progressGrad)" stroke-width="5" fill="none"
                            stroke-linecap="round"
                            stroke-dasharray="326.7"
                            :stroke-dashoffset="326.7 - (326.7 * progressPercent / 100)"
                            class="progress-ring-track"/>
                        <defs>
                            <linearGradient id="progressGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#ffffff"/>
                                <stop offset="100%" stop-color="#6b7280"/>
                            </linearGradient>
                        </defs>
                    </svg>

                    <div class="absolute inset-0 flex items-center justify-center">
                        <template x-if="currentStatus !== 'error' && currentStatus !== 'active'">
                            <div class="text-center">
                                <span class="text-3xl font-extrabold text-white tabular-nums" x-text="Math.round(progressPercent)"></span>
                                <span class="text-lg font-bold text-white/30">%</span>
                            </div>
                        </template>
                        <template x-if="currentStatus === 'active'">
                            <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"
                                          style="stroke-dasharray: 24; animation: checkDraw 0.6s ease-out forwards;"/>
                                </svg>
                            </div>
                        </template>
                        <template x-if="currentStatus === 'error'">
                            <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-7 h-7 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl font-extrabold text-white mb-2 tracking-tight">
                    <span x-show="currentStatus !== 'error' && currentStatus !== 'active'">Building Your Website</span>
                    <span x-show="currentStatus === 'active'">Your Website is Live!</span>
                    <span x-show="currentStatus === 'error'" class="text-white/60">Build Failed</span>
                </h1>

                <p class="text-sm font-medium text-white/30 mb-4" x-text="statusMessage"></p>

                {{-- Website name chip --}}
                <div class="inline-flex items-center gap-2 bg-white/[0.06] rounded-full px-4 py-1.5 border border-white/[0.08]">
                    <div class="w-2 h-2 rounded-full animate-pulse"
                         :class="currentStatus === 'error' ? 'bg-white/40' : currentStatus === 'active' ? 'bg-white' : 'bg-white/60'"></div>
                    <span class="text-white/50 text-xs font-semibold tracking-wide">{{ $website->name }}</span>
                </div>
            </div>
        </div>

        {{-- Steps Timeline --}}
        <div class="px-8 py-6 border-t border-white/[0.05]">
            <div class="space-y-0">
                <template x-for="(bStep, index) in steps" :key="index">
                    <div class="relative flex items-start gap-4 pb-5 last:pb-0">
                        <div x-show="index < steps.length - 1" class="step-line" :class="bStep.status === 'done' ? 'done' : ''">
                            <div class="step-line-fill"></div>
                        </div>

                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-500 relative z-10"
                            :class="{
                                'bg-white text-gray-900 shadow-lg shadow-white/10': bStep.status === 'done',
                                'bg-white/10 text-white border border-white/20 step-icon-active': bStep.status === 'active',
                                'bg-white/[0.03] text-white/15 border border-white/[0.06]': bStep.status === 'pending',
                                'bg-white/[0.06] text-white/40 border border-white/10': bStep.status === 'error'
                            }">
                            <template x-if="bStep.status === 'done'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="bStep.status === 'active'">
                                <svg class="w-4.5 h-4.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </template>
                            <template x-if="bStep.status === 'pending'">
                                <div class="w-1.5 h-1.5 rounded-full bg-white/15"></div>
                            </template>
                            <template x-if="bStep.status === 'error'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </template>
                        </div>

                        <div class="pt-2.5 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold transition-colors duration-500 truncate"
                                    :class="{
                                        'text-white/90': bStep.status === 'done',
                                        'text-white': bStep.status === 'active',
                                        'text-white/20': bStep.status === 'pending',
                                        'text-white/40': bStep.status === 'error'
                                    }"
                                    x-text="bStep.label"></span>
                                <span x-show="bStep.status === 'active'" class="flex-shrink-0 text-[10px] font-bold uppercase tracking-widest text-white/30 animate-pulse">in progress</span>
                            </div>
                            <p x-show="bStep.status === 'error'" class="text-xs text-white/30 mt-0.5">Something went wrong</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="px-8 pb-5">
            <div class="h-1.5 rounded-full bg-white/[0.04] overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000 ease-out"
                     :class="currentStatus === 'error' ? 'bg-white/20' : 'progress-bar-fill'"
                     :style="'width: ' + progressPercent + '%'"></div>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-[10px] font-semibold text-white/15 uppercase tracking-widest">Progress</span>
                <span class="text-[10px] font-bold tabular-nums text-white/30"
                      x-text="Math.round(progressPercent) + '%'"></span>
            </div>
        </div>

        {{-- Rotating Tips --}}
        <div x-show="currentStatus !== 'error' && currentStatus !== 'active'" class="px-8 pb-6">
            <div class="bg-white/[0.03] rounded-xl border border-white/[0.05] px-5 py-3.5 relative overflow-hidden h-12">
                <div class="absolute inset-0 flex items-center px-5">
                    <template x-for="(tip, i) in tips" :key="i">
                        <p class="absolute inset-0 flex items-center px-5 text-xs text-white/25 font-medium tip-item"
                           :style="'animation-delay: ' + (i * 5) + 's'">
                            <span class="mr-2.5 w-4 h-4 flex items-center justify-center bg-white/[0.06] rounded" x-html="tip.icon"></span>
                            <span x-text="tip.text"></span>
                        </p>
                    </template>
                </div>
            </div>
        </div>

        {{-- Error Action --}}
        <div x-show="currentStatus === 'error'" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="px-8 pb-6">
            <div class="bg-white/[0.04] rounded-2xl border border-white/[0.08] p-6 text-center">
                <p class="text-sm text-white/40 mb-4">Something went wrong while building your website.</p>
                <div class="flex items-center justify-center gap-3">
                    <form method="POST" action="{{ route('websites.retry', $website) }}">
                        @csrf
                        <button type="submit"
                           class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-900 font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-300 shadow-lg shadow-white/5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Retry This Build
                        </button>
                    </form>
                    <a href="{{ route('builder.index') }}"
                       class="inline-flex items-center gap-2 bg-white/[0.08] hover:bg-white/[0.12] text-white/60 hover:text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-300 border border-white/10">
                        Start New Build
                    </a>
                </div>
            </div>
        </div>

        {{-- Success Action --}}
        <div x-show="currentStatus === 'active'" x-cloak
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="px-8 pb-6">
            <div class="bg-white/[0.06] rounded-2xl border border-white/10 p-6 text-center">
                <div class="inline-flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 rounded-full bg-white animate-pulse"></div>
                    <span class="text-sm font-bold text-white">Website is live</span>
                </div>
                <p class="text-xs text-white/40">Redirecting you now...</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="mt-6 text-center animate-fade-up" style="animation-delay: 0.3s">
        <a href="{{ route('dashboard') }}" class="text-xs text-white/15 hover:text-white/50 font-semibold transition-all duration-300 tracking-wide">
            &larr; Back to Dashboard
        </a>
    </div>
</div>

<script>
function buildProgress() {
    return {
        currentStatus: '{{ $website->status }}',
        currentStep: 0,
        statusMessage: 'Preparing your workspace...',
        steps: [
            { label: 'Initializing project', status: 'done' },
            { label: 'Generating AI content', status: 'active' },
            { label: 'Creating custom images', status: 'pending' },
            { label: 'Setting up WordPress', status: 'pending' },
            { label: 'Building pages & layout', status: 'pending' },
            { label: 'Applying final polish', status: 'pending' },
            { label: 'Publishing your site', status: 'pending' },
        ],
        tips: [
            { icon: '<svg class="w-2.5 h-2.5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>', text: 'AI is writing unique content for your business' },
            { icon: '<svg class="w-2.5 h-2.5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>', text: 'Custom images are being generated just for you' },
            { icon: '<svg class="w-2.5 h-2.5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>', text: 'Your site will be fully editable after launch' },
        ],
        pollInterval: null,

        get progressPercent() {
            const doneCount = this.steps.filter(s => s.status === 'done').length;
            const activeBonus = this.steps.some(s => s.status === 'active') ? 0.4 : 0;
            return Math.min(100, Math.round(((doneCount + activeBonus) / this.steps.length) * 100));
        },

        init() {
            this.simulateProgress();
            this.pollInterval = setInterval(() => this.checkStatus(), 3000);
        },

        simulateProgress() {
            const messages = [
                'Generating AI content...',
                'Crafting your brand identity...',
                'Creating custom images...',
                'Installing WordPress core...',
                'Building your pages...',
                'Adding finishing touches...',
                'Going live...',
            ];

            const advance = () => {
                if (this.currentStatus === 'active' || this.currentStatus === 'error') return;
                if (this.currentStep < this.steps.length - 1) {
                    this.steps[this.currentStep].status = 'done';
                    this.currentStep++;
                    this.steps[this.currentStep].status = 'active';
                    this.statusMessage = messages[this.currentStep] || this.steps[this.currentStep].label + '...';
                    const delay = this.currentStep <= 2 ? 8000 + Math.random() * 6000 : 4000 + Math.random() * 3000;
                    setTimeout(advance, delay);
                }
            };
            setTimeout(advance, 3000);
        },

        async checkStatus() {
            try {
                const response = await fetch('{{ route("builder.status", $website) }}', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                });
                const data = await response.json();
                this.currentStatus = data.status;

                if (data.status === 'active') {
                    clearInterval(this.pollInterval);
                    this.steps.forEach(s => s.status = 'done');
                    this.statusMessage = 'Your website is live!';
                    setTimeout(() => {
                        window.location.href = '{{ route("builder.complete", $website) }}';
                    }, 2000);
                } else if (data.status === 'error') {
                    clearInterval(this.pollInterval);
                    this.steps.forEach(s => { if (s.status === 'active') s.status = 'error'; });
                    this.statusMessage = 'Build failed. Please try again.';
                }
            } catch (e) {}
        }
    };
}
</script>
</body>
</html>
