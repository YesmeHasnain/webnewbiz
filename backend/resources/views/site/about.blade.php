@extends('site.layouts.app')

@section('title', 'About Us — WebNewBiz')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-3xl" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">About Us</p>
            <h1 class="text-5xl md:text-7xl font-display font-bold leading-tight">
                <span class="gradient-text">Smarter Websites.</span><br>
                <span class="text-white">Smarter Growth.</span>
            </h1>
            <p class="text-lg text-brand-400 mt-8 leading-relaxed max-w-2xl">
                We're on a mission to make website building simple, fast, and powered by AI.
            </p>
        </div>
    </div>
</section>

{{-- Our Story --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-animate>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-4">Our Story</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-6">Why We Started WebNewBiz</h2>
                <div class="space-y-4 text-brand-400 leading-relaxed">
                    <p>We started WebNewBiz with a simple belief — building a website shouldn't be complicated, time-consuming, or expensive.</p>
                    <p>With AI-powered WebNewBiz, anyone can create a professional website in minutes, no technical skills required. Our team of designers, developers, and AI engineers came together with one goal: to make website creation instant, affordable, and accessible to everyone with an idea.</p>
                    <p>Today, more than 12,000 businesses — from solo entrepreneurs to fast-growing startups — have launched with WebNewBiz. We've removed the technical barriers so your ideas can reach the world, faster than ever before.</p>
                </div>
            </div>
            <div class="relative" data-animate>
                <div class="glass rounded-2xl p-2 glow">
                    <img src="{{ asset('assets/images/about/about-hero.jpg') }}" alt="Our Story" class="rounded-xl w-full">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1 relative" data-animate>
                <div class="glass rounded-2xl p-2 glow">
                    <img src="{{ asset('assets/images/about/mission.jpg') }}" alt="Our Mission" class="rounded-xl w-full">
                </div>
            </div>
            <div class="order-1 lg:order-2" data-animate>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-4">Our Mission</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-6">Our Mission</h2>
                <p class="text-brand-400 leading-relaxed">
                    At WebNewBiz, our mission is to help you save time, grow faster, and succeed online. We believe every business — no matter the size or budget — deserves a professional online presence without technical complexity, long timelines, or high costs. With our AI Website Builder, you can launch your digital journey for as little as one dollar, turning your ideas into a live, automated website in minutes.
                </p>
                <p class="text-brand-400 leading-relaxed mt-4">
                    By combining the flexibility of WordPress with the power of AI automation, we don't just build websites — we create complete digital growth engines designed to evolve with your business every step of the way.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- What Makes Us Different --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">What Makes Us Different</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Why choose us?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $diffs = [
                ['title' => 'AI-Powered Simplicity', 'desc' => 'Our AI Builder creates custom websites automatically — no coding needed.'],
                ['title' => 'Speed Without Compromise', 'desc' => 'Launch your site in minutes. Just describe your idea and go live.'],
                ['title' => 'Scalable by Design', 'desc' => 'Easily add products, pages, or bookings as you expand with just one prompt.'],
                ['title' => 'Smart Automation', 'desc' => 'AI handles content, chats, and orders while you focus on growth.'],
                ['title' => 'Affordable Excellence', 'desc' => 'Get professional websites and automation tools starting from $1.'],
                ['title' => 'Complete WordPress Setup', 'desc' => 'Fully optimized WordPress ready in one prompt with all essentials.'],
            ];
            @endphp

            @foreach($diffs as $i => $d)
                <div class="glass-card rounded-2xl p-8" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center mb-5">
                        <span class="text-sm font-mono text-brand-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">{{ $d['title'] }}</h3>
                    <p class="text-sm text-brand-400 leading-relaxed">{{ $d['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Who We Serve --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Who We Serve</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Built for every business</h2>
            <p class="text-brand-400 mt-6 max-w-xl mx-auto">Not every business is the same — our platform adapts to different needs.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $serves = [
                ['title' => 'Digital Agencies', 'desc' => 'Launch client websites 10x faster with AI-powered design and white-label tools.'],
                ['title' => 'E-Commerce Stores', 'desc' => 'Sell more with WooCommerce, AI product descriptions, and automated inventory.'],
                ['title' => 'Small Businesses', 'desc' => 'Run on autopilot with AI chatbots, automated bookings, and CRM integration.'],
                ['title' => 'Growing Brands', 'desc' => 'AI-generated social content, advanced analytics, SEO, and multi-channel marketing.'],
                ['title' => 'Freelancers & Creators', 'desc' => 'Stunning portfolios, integrated booking systems, and client management tools.'],
            ];
            @endphp

            @foreach($serves as $i => $s)
                <div class="glass-card rounded-2xl p-8 {{ $i === 4 ? 'md:col-span-2 lg:col-span-1' : '' }}" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <h3 class="text-lg font-semibold text-white mb-3">{{ $s['title'] }}</h3>
                    <p class="text-sm text-brand-400 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Values --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Our Values</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">We don't just build websites,<br>we build trust.</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $values = [
                ['title' => 'Radical Simplicity', 'desc' => 'Technology should work for you, not against you. We obsess over removing every unnecessary click, every confusing feature, and every moment of frustration.'],
                ['title' => 'Fearless Innovation', 'desc' => 'We don\'t follow trends — we create them. Combining cutting-edge AI with human-centered design, we push boundaries to give you tools that don\'t exist anywhere else.'],
                ['title' => 'Your Success = Ours', 'desc' => 'We measure our success by your growth. Every feature is designed to save you time, make you money, or help you reach more customers.'],
            ];
            @endphp

            @foreach($values as $i => $v)
                <div class="glass-card rounded-2xl p-10" data-animate style="animation-delay: {{ $i * 100 }}ms">
                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mb-6">
                        <span class="text-lg font-display font-bold text-white">{{ $i + 1 }}</span>
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-4">{{ $v['title'] }}</h3>
                    <p class="text-sm text-brand-400 leading-relaxed">{{ $v['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-32 relative z-10">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="glass rounded-3xl p-16 md:p-20 glow relative overflow-hidden" data-animate>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-32 bg-white/[0.03] blur-3xl rounded-full"></div>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text mb-6 relative z-10">Ready to build your<br>next big idea?</h2>
            <p class="text-brand-400 mb-10 relative z-10">Join thousands of builders creating amazing websites with AI.</p>
            <a href="#" class="btn-primary text-base px-12 py-4 relative z-10 inline-block">Get Started</a>
        </div>
    </div>
</section>

@endsection
