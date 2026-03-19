@extends('site.layouts.app')

@section('title', 'WebNewBiz — Build Your AI-Powered Website for Just $1')

@section('content')

{{-- Hero Section --}}
<section class="relative min-h-screen flex items-center justify-center pt-24 pb-20 overflow-hidden">
    {{-- Background Elements --}}
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-white/[0.02] rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-white/[0.015] rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/[0.03] mb-8" data-animate>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs text-brand-300 tracking-wide">Limited Offer — Start for just $1</span>
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-display font-bold leading-[0.9] tracking-tight mb-8" data-animate>
                <span class="gradient-text">Build Your</span><br>
                <span class="text-white">AI-Powered</span><br>
                <span class="gradient-text">Website</span>
            </h1>

            <p class="text-lg md:text-xl text-brand-400 max-w-2xl mx-auto leading-relaxed mb-10" data-animate>
                Launch your business online in minutes. AI designs, builds, and automates everything for you. No coding, no manual work.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16" data-animate>
                <a href="#" class="btn-primary text-base px-10 py-4">
                    Get Started for $1
                    <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#how-it-works" class="btn-outline">How It Works</a>
            </div>

            {{-- Hero Image --}}
            <div class="relative max-w-5xl mx-auto" data-animate>
                <div class="glass rounded-2xl p-2 glow">
                    <img src="{{ asset('assets/images/homepage/vibe-coding-frontend_1xyitityugjhj.png') }}" alt="WebNewBiz Dashboard" class="rounded-xl w-full">
                </div>
                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-3/4 h-20 bg-white/[0.02] blur-2xl rounded-full"></div>
            </div>
        </div>
    </div>
</section>

{{-- Trusted Marquee --}}
<section class="relative z-10 border-y border-white/5 py-6 overflow-hidden">
    <div class="flex whitespace-nowrap marquee">
        @for($i = 0; $i < 3; $i++)
            <span class="mx-12 text-sm text-brand-500 tracking-widest uppercase">AI Website Builder</span>
            <span class="mx-4 text-brand-700">●</span>
            <span class="mx-12 text-sm text-brand-500 tracking-widest uppercase">Smart Dashboard</span>
            <span class="mx-4 text-brand-700">●</span>
            <span class="mx-12 text-sm text-brand-500 tracking-widest uppercase">E-Commerce Ready</span>
            <span class="mx-4 text-brand-700">●</span>
            <span class="mx-12 text-sm text-brand-500 tracking-widest uppercase">AI Chatbots</span>
            <span class="mx-4 text-brand-700">●</span>
            <span class="mx-12 text-sm text-brand-500 tracking-widest uppercase">Social Automation</span>
            <span class="mx-4 text-brand-700">●</span>
            <span class="mx-12 text-sm text-brand-500 tracking-widest uppercase">Smart Bookings</span>
            <span class="mx-4 text-brand-700">●</span>
        @endfor
    </div>
</section>

{{-- Features Grid --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Why WebNewBiz</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">AI websites that work<br>as fast as you do.</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $features = [
                ['icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>', 'title' => '1-Click Website Builder', 'desc' => 'Generate a personalized site with built-in, AI-powered functionality. No code required.'],
                ['icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>', 'title' => 'Smart Dashboard', 'desc' => 'Full WordPress control with drag-and-drop editor. Design visually without code.'],
                ['icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>', 'title' => 'AI Business Tools', 'desc' => 'AI handles content, chatbots, orders, and growth while you focus on what matters.'],
                ['icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4V20M17 4V20M3 8H7M17 8H21M3 12H21M3 16H7M17 16H21M7 20H17"/></svg>', 'title' => 'Social Media Growth', 'desc' => 'Auto-post content across platforms with AI captions, hashtags, and analytics.'],
            ];
            @endphp

            @foreach($features as $i => $f)
                <div class="glass-card rounded-2xl p-8" data-animate style="animation-delay: {{ $i * 100 }}ms">
                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-brand-300 mb-6">
                        {!! $f['icon'] !!}
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">{{ $f['title'] }}</h3>
                    <p class="text-sm text-brand-400 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Bento Grid Features --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- AI Builder --}}
            <div class="glass-card rounded-2xl p-10 md:col-span-2 grid md:grid-cols-2 gap-10 items-center" data-animate>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-4">AI Website Builder</p>
                    <h3 class="text-3xl font-display font-bold text-white mb-4">Create a custom website with AI</h3>
                    <p class="text-brand-400 leading-relaxed mb-6">Describe your idea and get a complete website instantly — layouts, design, and content. Edit everything via prompt or visual editor.</p>
                    <a href="{{ route('services') }}" class="btn-outline inline-block text-sm">Learn More</a>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/images/homepage/d0b1f6e5126a0ca38e7cc76dac995e18.png') }}" alt="AI Builder" class="rounded-xl w-full">
                </div>
            </div>

            {{-- Drag & Drop --}}
            <div class="glass-card rounded-2xl p-10" data-animate>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-4">Visual Editor</p>
                <h3 class="text-2xl font-display font-bold text-white mb-3">Build with drag and drop</h3>
                <p class="text-brand-400 text-sm leading-relaxed mb-6">Create pages quickly with a simple drag-and-drop editor. Design visually without code.</p>
                <img src="{{ asset('assets/images/homepage/77b8cce8f7772c1827389dadc02c6388.png') }}" alt="Drag & Drop" class="rounded-xl w-full mt-4">
            </div>

            {{-- Brand Story --}}
            <div class="glass-card rounded-2xl p-10" data-animate>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-4">AI Content</p>
                <h3 class="text-2xl font-display font-bold text-white mb-3">Tell your brand story with AI</h3>
                <p class="text-brand-400 text-sm leading-relaxed mb-6">Generate headlines, descriptions, and blog posts that express your brand's voice clearly and consistently.</p>
                <img src="{{ asset('assets/images/homepage/ea71e7cd4aefc4714efaef22286224b5.png') }}" alt="Brand Story" class="rounded-xl w-full mt-4">
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section id="how-it-works" class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Simple Process</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">From idea to live website<br>in minutes</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $steps = [
                ['num' => '01', 'title' => 'Describe & let AI build', 'desc' => 'Describe your website in a few words, and AI generates a fully structured site in seconds.', 'img' => 'describe_1x.jpg'],
                ['num' => '02', 'title' => 'Customize & personalize', 'desc' => 'Chat with AI or use drag-and-drop to adjust design, personalize content, and fine-tune layout.', 'img' => 'customize_1x-1.jpg'],
                ['num' => '03', 'title' => 'Review & publish', 'desc' => 'Take a final look and launch. AI ensures mobile responsiveness and SEO optimization.', 'img' => 'review_1x-1.jpg'],
            ];
            @endphp

            @foreach($steps as $i => $step)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 150 }}ms">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('assets/images/homepage/' . $step['img']) }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-8">
                        <span class="text-xs font-mono text-brand-500">{{ $step['num'] }}</span>
                        <h3 class="text-xl font-display font-bold text-white mt-2 mb-3">{{ $step['title'] }}</h3>
                        <p class="text-sm text-brand-400 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- AI Automations --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">AI Automation</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Smart tools that work<br>for you</h2>
            <p class="text-brand-400 mt-6 max-w-2xl mx-auto">More than just a website — AI handles your sales, support, and marketing while you focus on growth.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
            $automations = [
                ['title' => 'Subscription Management', 'desc' => 'Automated billing, renewals, and customer lifecycle management.', 'img' => 'SUBSCRIPTION-MANAGMENT-.jpg'],
                ['title' => 'Smart Bookings & Reminders', 'desc' => 'Automated scheduling with calendar sync and reminder notifications.', 'img' => 'Smart-Bookings-Reminders.jpg'],
                ['title' => 'AI Chatbot', 'desc' => '24/7 intelligent customer support that books, sells, and answers automatically.', 'img' => 'Ai-Chatbot-1.jpg'],
                ['title' => 'Social Media Automation', 'desc' => 'Auto-publish content across all platforms with AI captions and analytics.', 'img' => 'Social-Media-Automation.jpg'],
            ];
            @endphp

            @foreach($automations as $i => $auto)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 100 }}ms">
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ asset('assets/images/homepage/' . $auto['img']) }}" alt="{{ $auto['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-display font-bold text-white mb-2">{{ $auto['title'] }}</h3>
                        <p class="text-sm text-brand-400">{{ $auto['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials / Portfolio --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Portfolio</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Websites built with AI</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" data-animate>
            @php
            $portfolio = [
                'd68e20693670adfb0b9ffb72bada6965.webp',
                '716a179ea09e15797f012dbe507356a1.webp',
                'original-87dcd6c29bb6d02f6f42a36441eb2a94.webp',
                'b83cac73d19efcf80b321a2c0454d159.webp',
                '89121dc05d55e7b8793dcd8693de87cd.webp',
                'a48d335741a1ff80e9503aad12fb4dff.webp',
                'original-25a987b5055b056376f5d1a10fad76c2.webp',
                '12dce83cb588515d895ddc03c6c22c2d.webp',
            ];
            @endphp

            @foreach($portfolio as $img)
                <div class="glass-card rounded-xl overflow-hidden group cursor-pointer">
                    <div class="aspect-[3/4] overflow-hidden">
                        <img src="{{ asset('assets/images/homepage/' . $img) }}" alt="Portfolio" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pricing Preview --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Pricing</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Flexible packages for<br>every business</h2>
            <p class="text-brand-400 mt-6 max-w-xl mx-auto">From simple websites to full AI-driven growth engines.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $plans = [
                [
                    'name' => 'Starter',
                    'price' => '$1',
                    'period' => '/mo for 3 months',
                    'after' => 'then $9/mo',
                    'desc' => 'Perfect for freelancers and startups launching a professional online presence.',
                    'features' => ['AI Website Builder', 'AI-Powered Dashboard', 'Built-in AI Assistant', 'Drag-and-Drop Editor', 'AI Image Generation', 'Free SSL Certificate', '24/7 Customer Support'],
                    'featured' => false,
                ],
                [
                    'name' => 'Professional',
                    'price' => '$29',
                    'period' => '/mo',
                    'after' => null,
                    'desc' => 'For growing businesses with advanced automation and e-commerce needs.',
                    'features' => ['Everything in Starter', '24/7 AI Chatbot', 'AI Order Processing', 'Complete E-commerce', 'Automated Bookings', 'Lead Capture & Follow-up', 'Business Email Setup', 'Priority Support'],
                    'featured' => true,
                ],
                [
                    'name' => 'Premium',
                    'price' => '$49',
                    'period' => '/mo',
                    'after' => null,
                    'desc' => 'Full automation, enterprise performance, and a complete digital ecosystem.',
                    'features' => ['Everything in Professional', 'AI Social Media Manager', 'Auto-Publishing System', 'AI Ad Campaigns', 'Advanced Analytics', 'Cloudflare Enterprise CDN', '90+ PageSpeed Score', 'Instagram/FB/WhatsApp AI'],
                    'featured' => false,
                ],
            ];
            @endphp

            @foreach($plans as $i => $plan)
                <div class="rounded-2xl p-8 {{ $plan['featured'] ? 'bg-white text-brand-950 ring-1 ring-white' : 'glass-card' }}" data-animate style="animation-delay: {{ $i * 100 }}ms">
                    @if($plan['featured'])
                        <span class="inline-block text-xs font-semibold uppercase tracking-widest bg-brand-950 text-white px-3 py-1 rounded-full mb-6">Most Popular</span>
                    @endif
                    <h3 class="text-xl font-display font-bold {{ $plan['featured'] ? 'text-brand-950' : 'text-white' }}">{{ $plan['name'] }}</h3>
                    <div class="flex items-baseline gap-1 mt-4 mb-2">
                        <span class="text-5xl font-display font-bold {{ $plan['featured'] ? 'text-brand-950' : 'text-white' }}">{{ $plan['price'] }}</span>
                        <span class="text-sm {{ $plan['featured'] ? 'text-brand-500' : 'text-brand-400' }}">{{ $plan['period'] }}</span>
                    </div>
                    @if($plan['after'])
                        <p class="text-xs {{ $plan['featured'] ? 'text-brand-400' : 'text-brand-500' }} mb-6">{{ $plan['after'] }}</p>
                    @else
                        <div class="mb-6"></div>
                    @endif
                    <p class="text-sm {{ $plan['featured'] ? 'text-brand-500' : 'text-brand-400' }} mb-8">{{ $plan['desc'] }}</p>
                    <a href="#" class="{{ $plan['featured'] ? 'bg-brand-950 text-white hover:bg-brand-800' : 'btn-primary' }} block text-center py-3 rounded-full font-semibold text-sm transition-colors">Get Started</a>
                    <ul class="mt-8 space-y-3">
                        @foreach($plan['features'] as $feature)
                            <li class="flex items-center gap-3 text-sm {{ $plan['featured'] ? 'text-brand-600' : 'text-brand-400' }}">
                                <svg class="w-4 h-4 {{ $plan['featured'] ? 'text-brand-950' : 'text-white' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10" data-animate>
            <a href="{{ route('pricing') }}" class="text-sm text-brand-400 hover:text-white transition-colors">View full pricing details →</a>
        </div>
    </div>
</section>

{{-- All Stack Section --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Full Stack</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">All Stack, AI-native.</h2>
            <p class="text-brand-400 mt-6 max-w-xl mx-auto">Everything you need to build, launch, and grow with confidence.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $stack = [
                ['title' => 'AI Website Builder', 'desc' => 'Describe your idea and get a complete website instantly — layouts, design, and content.'],
                ['title' => 'Social Media Automation', 'desc' => 'Auto-post across all platforms with AI captions, hashtags, and performance tracking.'],
                ['title' => 'WordPress Backend', 'desc' => 'Production-ready WordPress on the world\'s most trusted CMS. 60K+ plugins available.'],
                ['title' => 'Business Automation', 'desc' => 'AI automates inquiries, bookings, payments, follow-ups, inventory — 24/7 on autopilot.'],
                ['title' => 'AI Dashboard', 'desc' => 'Run your entire business from one smart, unified dashboard with AI-powered automation.'],
                ['title' => 'AI Chatbots', 'desc' => 'Your 24/7 assistant — responds to customers, books appointments, answers questions, and closes sales.'],
            ];
            @endphp

            @foreach($stack as $i => $item)
                <div class="glass-card rounded-2xl p-8 group" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center mb-5">
                        <span class="text-sm font-mono text-brand-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3 group-hover:gradient-text transition-all">{{ $item['title'] }}</h3>
                    <p class="text-sm text-brand-400 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ Preview --}}
<section class="py-32 relative z-10">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">FAQ</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Frequently Asked<br>Questions</h2>
        </div>

        @php
        $faqs = [
            ['q' => 'How does AI build my website?', 'a' => 'Describe your business in plain English. AI generates a complete, professional website in 5-10 minutes — custom design, content, layouts, everything. No templates, no coding. Edit via chat or visual editor, then launch.'],
            ['q' => 'Can I edit my website after AI builds it?', 'a' => 'Yes! Edit via AI chat ("make it blue"), visual drag-and-drop editor, or full WordPress control. No coding needed. Update text, images, layouts — anything, anytime.'],
            ['q' => 'Does AI handle social media?', 'a' => 'Yes! AI creates posts, schedules content, and auto-publishes across all platforms. Analyzes performance and optimizes timing. Your social media runs 24/7.'],
            ['q' => 'What\'s the minimum to start?', 'a' => '$1 for 3 months (Starter Plan). Full access, all features, no hidden fees. Cancel anytime.'],
        ];
        @endphp

        <div class="space-y-4" data-animate>
            @foreach($faqs as $i => $faq)
                <div class="glass-card rounded-xl overflow-hidden" x-data="{ open: false }">
                    <button class="w-full flex items-center justify-between p-6 text-left" onclick="this.parentElement.classList.toggle('faq-open'); this.querySelector('.faq-icon').classList.toggle('rotate-45')">
                        <span class="font-semibold text-white pr-4">{{ $faq['q'] }}</span>
                        <svg class="faq-icon w-5 h-5 text-brand-400 flex-shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <div class="faq-answer hidden px-6 pb-6">
                        <p class="text-sm text-brand-400 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10" data-animate>
            <a href="{{ route('faqs') }}" class="text-sm text-brand-400 hover:text-white transition-colors">View all FAQs →</a>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-32 relative z-10">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="glass rounded-3xl p-16 md:p-20 glow relative overflow-hidden" data-animate>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-32 bg-white/[0.03] blur-3xl rounded-full"></div>
            <h2 class="text-4xl md:text-6xl font-display font-bold gradient-text mb-6 relative z-10">Ready to build your<br>next big idea?</h2>
            <p class="text-brand-400 mb-10 relative z-10">Join thousands of builders creating amazing websites with AI.</p>
            <a href="#" class="btn-primary text-base px-12 py-4 relative z-10 inline-block">
                Get Started for $1
                <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // FAQ toggle
    document.querySelectorAll('.faq-open .faq-answer, .glass-card .faq-answer').forEach(el => el.classList.add('hidden'));
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.glass-card button');
        if (!btn) return;
        const answer = btn.parentElement.querySelector('.faq-answer');
        if (answer) answer.classList.toggle('hidden');
    });
</script>
@endpush
