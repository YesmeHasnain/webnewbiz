@extends('site.layouts.app')

@section('title', 'Solutions — WebNewBiz')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-3xl" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Solutions</p>
            <h1 class="text-5xl md:text-7xl font-display font-bold leading-tight">
                <span class="gradient-text">Solutions for</span><br>
                <span class="text-white">Every Business</span>
            </h1>
            <p class="text-lg text-brand-400 mt-8 leading-relaxed max-w-2xl">
                From freelancers to fast-growing brands — we help you launch, manage, and grow online, all in one place.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mt-10">
                <a href="#" class="btn-primary inline-block">Get Started for $1</a>
            </div>
        </div>
    </div>
</section>

{{-- Freelancers --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-10 md:p-16 grid lg:grid-cols-2 gap-12 items-center" data-animate>
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-3">01</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">Freelancers & Creators</h2>
                <p class="text-lg text-brand-300 mb-6">Showcase your talent with ease</p>
                <p class="text-brand-400 leading-relaxed mb-8">Your portfolio should be simple to create, easy to manage, and professional to present. Our platform helps freelancers, artists, and creators stand out online.</p>
                <ul class="space-y-3 mb-8">
                    @foreach(['Personal websites, blogs & portfolios', 'Ready-to-use templates for creatives', 'Easy content updates without coding', 'Secure hosting & SSL included'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="#" class="btn-primary inline-block">Get Started for $1</a>
            </div>
            <div>
                <img src="{{ asset('assets/images/solution/Freelancers-and-Creators-.jpg') }}" alt="Freelancers" class="rounded-xl w-full">
            </div>
        </div>
    </div>
</section>

{{-- E-Commerce --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-10 md:p-16 grid lg:grid-cols-2 gap-12 items-center" data-animate>
            <div class="order-2 lg:order-1">
                <img src="{{ asset('assets/images/solution/original-0f799887d74b99fc7760b752640974ae.webp') }}" alt="E-Commerce" class="rounded-xl w-full">
            </div>
            <div class="order-1 lg:order-2">
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-3">02</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">Online Stores & E-Commerce</h2>
                <p class="text-lg text-brand-300 mb-6">Turn visitors into loyal customers</p>
                <p class="text-brand-400 leading-relaxed mb-8">Launch your store and start selling quickly. Built-in e-commerce tools let you manage products, process payments, and track sales effortlessly.</p>
                <ul class="space-y-3 mb-8">
                    @foreach(['WooCommerce integration', 'Stripe, PayPal & more', 'Smart order tracking', 'Abandoned cart recovery'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="#" class="btn-primary inline-block">Start Selling Online</a>
            </div>
        </div>
    </div>
</section>

{{-- Service Businesses --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-3">03</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">Service Businesses</h2>
            <p class="text-lg text-brand-300">Keep your schedule full</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
            $serviceFeatures = [
                ['title' => 'Online Booking Forms & Calendar Sync', 'desc' => 'Effortlessly collect bookings with customizable forms', 'img' => 'Online-Booking-Forms-Calendar-Sync-1-1.jpg'],
                ['title' => 'Automated Confirmations & Reminders', 'desc' => 'Send timely reminders to reduce no-shows', 'img' => 'Automated-Confirmations-Reminders-.jpg'],
                ['title' => 'Customer Database & Simple CRM', 'desc' => 'Easily track customer history and preferences', 'img' => 'Customer-Database-Simple-CRM-.jpg'],
                ['title' => 'Payment Options for Paid Services', 'desc' => 'Offer secure online payments for hassle-free transactions', 'img' => 'Payment-Options-for-Paid-Services-.jpg'],
            ];
            @endphp

            @foreach($serviceFeatures as $i => $sf)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 100 }}ms">
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ asset('assets/images/solution/' . $sf['img']) }}" alt="{{ $sf['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-white mb-2">{{ $sf['title'] }}</h3>
                        <p class="text-sm text-brand-400">{{ $sf['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Growing Brands --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-10 md:p-16 grid lg:grid-cols-2 gap-12 items-center" data-animate>
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-3">05</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">Growing Brands & Enterprises</h2>
                <p class="text-lg text-brand-300 mb-6">All-in-one growth platform</p>
                <p class="text-brand-400 leading-relaxed mb-8">As your business grows, our Growth Plan combines websites, stores, social media, and automation in one ecosystem.</p>
                <ul class="space-y-3 mb-8">
                    @foreach(['Multi-channel marketing & social posts', 'Automated customer journeys', 'Performance insights & analytics', 'Secure hosting + advanced scalability'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="#" class="btn-primary inline-block">Start Your Growth Plan</a>
            </div>
            <div>
                <img src="{{ asset('assets/images/solution/GROWING-BRANDS-AND-ENTRPRISES-.jpg') }}" alt="Growing Brands" class="rounded-xl w-full">
            </div>
        </div>
    </div>
</section>

{{-- How Our Solutions Help --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">How Our Solutions<br>Help You Grow</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $benefits = [
                ['title' => 'Save Time', 'desc' => 'No coding or complex setups — everything is built-in.'],
                ['title' => 'Stay Flexible', 'desc' => 'From small sites to full business platforms, scale when needed.'],
                ['title' => 'Increase Sales', 'desc' => 'Smarter e-commerce, bookings, and lead management.'],
                ['title' => 'Boost Engagement', 'desc' => 'Keep customers connected through chat, reminders, and social.'],
            ];
            @endphp

            @foreach($benefits as $i => $b)
                <div class="glass-card rounded-2xl p-8" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center mb-5">
                        <span class="text-sm font-mono text-brand-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">{{ $b['title'] }}</h3>
                    <p class="text-sm text-brand-400 leading-relaxed">{{ $b['desc'] }}</p>
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
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text mb-6 relative z-10">Why Choose Our Platform</h2>
            <p class="text-brand-400 mb-10 relative z-10">All-in-one solution. Simple for beginners, powerful for growing businesses.</p>
            <a href="#" class="btn-primary text-base px-12 py-4 relative z-10 inline-block">Get Started for $1</a>
        </div>
    </div>
</section>

@endsection
