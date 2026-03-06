<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Website Ready! - Webnewbiz</title>
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

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
        @keyframes checkDraw { from { stroke-dashoffset: 100; } to { stroke-dashoffset: 0; } }
        @keyframes ringPulse { 0% { transform: scale(1); opacity: 0.3; } 100% { transform: scale(1.5); opacity: 0; } }

        .confetti-piece {
            position: fixed; top: -10px; border-radius: 2px;
            animation: confetti-fall linear forwards; z-index: 100; pointer-events: none;
        }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
        .success-ring {
            position: absolute; inset: 0; border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.2);
            animation: ringPulse 1.5s ease-out infinite;
        }
        .check-path {
            stroke-dasharray: 100;
            animation: checkDraw 0.8s ease-out 0.3s forwards;
            stroke-dashoffset: 100;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-[#080808]">

<div x-data="{ ...completePage(), ...chatbot({{ $website->id }}) }" x-init="createConfetti(); loadHistory()" class="w-full max-w-2xl mx-auto px-6 py-12">
    {{-- Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="fixed inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 32px 32px;"></div>
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-white/[0.02] rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 -right-20 w-80 h-80 bg-white/[0.015] rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10">
        {{-- Success icon --}}
        <div class="relative w-24 h-24 mx-auto mb-8 animate-scale-in">
            <div class="success-ring"></div>
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-2xl shadow-white/10">
                <svg class="w-12 h-12 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="check-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <div class="text-center mb-10 animate-fade-up" style="animation-delay: 0.2s">
            <h1 class="text-3xl font-bold text-white mb-3">Your Website is Live!</h1>
            <p class="text-white/35 text-lg">"{{ $website->name }}" has been built and deployed successfully.</p>
        </div>

        {{-- Website preview card --}}
        <div class="bg-white/[0.05] backdrop-blur-xl rounded-2xl border border-white/[0.08] overflow-hidden mb-8 animate-fade-up shadow-2xl shadow-black/20" style="animation-delay: 0.4s">
            <div class="h-52 bg-white/[0.03] flex items-center justify-center relative overflow-hidden">
                @if($website->screenshot_path)
                    <img src="{{ $website->screenshot_path }}" alt="{{ $website->name }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center">
                        <svg class="w-16 h-16 text-white/10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <p class="text-white/20 text-sm">Preview loading...</p>
                    </div>
                @endif
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                    <a href="{{ $website->url }}" target="_blank" class="text-white text-sm font-medium hover:underline flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        {{ $website->url }}
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-white/25 uppercase tracking-wider mb-1">Name</p>
                        <p class="text-sm font-medium text-white">{{ $website->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-white/25 uppercase tracking-wider mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Active
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-white/25 uppercase tracking-wider mb-1">Style</p>
                        <p class="text-sm font-medium text-white capitalize">{{ $website->ai_style }}</p>
                    </div>
                    @if($website->domains->count())
                    <div>
                        <p class="text-xs text-white/25 uppercase tracking-wider mb-1">Domain</p>
                        <p class="text-sm font-medium text-white">{{ $website->domains->first()->domain }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8 animate-fade-up" style="animation-delay: 0.6s">
            <a href="{{ $website->url }}" target="_blank"
                class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-100 text-gray-900 font-semibold px-8 py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-white/5 hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Visit Your Website
            </a>
            <a href="{{ route('websites.wp-admin', $website) }}" target="_blank"
                class="inline-flex items-center justify-center gap-2 bg-white/[0.07] hover:bg-white/[0.12] text-white font-semibold px-8 py-3.5 rounded-xl border border-white/10 transition-all duration-300 backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                WP Admin Panel
            </a>
        </div>

        {{-- Dashboard link --}}
        <div class="text-center animate-fade-up" style="animation-delay: 0.8s">
            <a href="{{ route('dashboard') }}" class="text-white/25 hover:text-white/60 font-medium text-sm transition-colors">
                Go to Dashboard &rarr;
            </a>
        </div>
    </div>
</div>

    {{-- Floating chat button --}}
    <button @click="chatOpen = !chatOpen" class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-white hover:bg-gray-100 text-gray-900 rounded-full shadow-lg shadow-white/10 flex items-center justify-center transition-all duration-300 hover:scale-105">
        <svg x-show="!chatOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <svg x-show="chatOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    {{-- Chat panel --}}
    <div x-show="chatOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 z-40 w-[400px] h-full bg-white shadow-2xl flex flex-col" style="max-width: 90vw;">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gray-900 rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">AI Website Editor</h3>
                    <p class="text-xs text-gray-400">Ask me to edit your website</p>
                </div>
            </div>
            <button @click="chatOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="chatMessages">
            <template x-if="chatMessages.length === 0 && !chatLoading">
                <div class="flex gap-3">
                    <div class="w-7 h-7 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-md px-4 py-3 max-w-[85%]">
                        <p class="text-sm text-gray-700 leading-relaxed">Hi! I can help you edit your website. Try things like:</p>
                        <ul class="text-sm text-gray-500 mt-2 space-y-1">
                            <li>"Change the hero title to Welcome"</li>
                            <li>"Update the site tagline"</li>
                            <li>"Change the primary color to blue"</li>
                            <li>"Add a new FAQ page"</li>
                        </ul>
                    </div>
                </div>
            </template>

            <template x-for="(msg, i) in chatMessages" :key="i">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex gap-3'">
                    <template x-if="msg.role === 'assistant'">
                        <div class="w-7 h-7 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </template>
                    <div :class="msg.role === 'user' ? 'bg-gray-900 text-white rounded-2xl rounded-tr-md px-4 py-3 max-w-[85%] shadow-md' : 'bg-gray-100 text-gray-700 rounded-2xl rounded-tl-md px-4 py-3 max-w-[85%]'">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.content"></p>
                        <template x-if="msg.actions_taken && msg.actions_taken.length > 0">
                            <div class="mt-2 pt-2 border-t" :class="msg.role === 'user' ? 'border-gray-700' : 'border-gray-200'">
                                <template x-for="(act, ai) in msg.actions_taken" :key="ai">
                                    <div class="flex items-center gap-1.5 text-xs mt-1" :class="msg.role === 'user' ? 'text-gray-300' : 'text-gray-500'">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span x-text="act.detail || act.action"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="chatLoading" class="flex gap-3">
                <div class="w-7 h-7 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-md px-4 py-3">
                    <div class="flex gap-1.5">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay:0.2s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay:0.4s"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 p-4 flex-shrink-0">
            <form @submit.prevent="sendChat()" class="flex gap-2">
                <input type="text" x-model="chatInput" placeholder="Ask me to edit your website..." :disabled="chatLoading"
                    class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/20 focus:border-gray-400 disabled:opacity-50 transition-all" />
                <button type="submit" :disabled="!chatInput.trim() || chatLoading"
                    class="bg-gray-900 hover:bg-black disabled:bg-gray-200 disabled:cursor-not-allowed text-white px-4 py-2.5 rounded-xl transition-all flex-shrink-0 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>

<script>
function completePage() { return {}; }

function createConfetti() {
    const shades = ['#ffffff', '#e5e7eb', '#d1d5db', '#9ca3af', '#6b7280', '#4b5563', '#374151', '#1f2937', '#111827'];
    for (let i = 0; i < 60; i++) {
        const piece = document.createElement('div');
        piece.className = 'confetti-piece';
        piece.style.left = Math.random() * 100 + 'vw';
        piece.style.backgroundColor = shades[Math.floor(Math.random() * shades.length)];
        piece.style.animationDuration = (2 + Math.random() * 3) + 's';
        piece.style.animationDelay = Math.random() * 2 + 's';
        piece.style.width = (6 + Math.random() * 8) + 'px';
        piece.style.height = (6 + Math.random() * 8) + 'px';
        piece.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
        document.body.appendChild(piece);
        setTimeout(() => piece.remove(), 7000);
    }
}

function chatbot(websiteId) {
    return {
        chatOpen: false,
        chatMessages: [],
        chatInput: '',
        chatLoading: false,

        async loadHistory() {
            try {
                const res = await fetch(`/websites/${websiteId}/chat/history`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                if (data.messages) this.chatMessages = data.messages;
            } catch (e) {}
        },

        async sendChat() {
            if (!this.chatInput.trim() || this.chatLoading) return;
            const msg = this.chatInput.trim();
            this.chatInput = '';
            this.chatMessages.push({ role: 'user', content: msg });
            this.chatLoading = true;
            this.scrollChat();

            try {
                const res = await fetch(`/websites/${websiteId}/chat`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                this.chatMessages.push({
                    role: 'assistant',
                    content: data.reply || 'Sorry, something went wrong.',
                    actions_taken: data.actions_taken || []
                });
            } catch (e) {
                this.chatMessages.push({ role: 'assistant', content: 'Network error. Please try again.' });
            }
            this.chatLoading = false;
            this.scrollChat();
        },

        scrollChat() {
            this.$nextTick(() => {
                const el = this.$refs.chatMessages;
                if (el) el.scrollTop = el.scrollHeight;
            });
        }
    };
}
</script>
</body>
</html>
