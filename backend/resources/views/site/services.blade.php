@extends('site.layouts.app')

@section('title', 'Services — WebNewBiz')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-3xl" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Services</p>
            <h1 class="text-5xl md:text-7xl font-display font-bold leading-tight">
                <span class="gradient-text">Everything You Need</span><br>
                <span class="text-white">to Launch & Grow</span>
            </h1>
            <p class="text-lg text-brand-400 mt-8 leading-relaxed max-w-2xl">
                From instant websites to automated tools, all the services your business needs in one place.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mt-10">
                <a href="#" class="btn-primary inline-block">Get Started</a>
            </div>
        </div>
    </div>
</section>

{{-- Website Builder --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-10 md:p-16 grid lg:grid-cols-2 gap-12 items-center" data-animate>
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-3">01</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">Website Builder</h2>
                <h3 class="text-xl text-brand-300 mb-6">Launch Your Website in Minutes</h3>
                <p class="text-brand-400 leading-relaxed mb-8">No coding, no delays, no stress. Our smart website builder helps you create a professional site instantly — ready to go live the same day.</p>
                <ul class="space-y-3">
                    @foreach(['One-click setup, no coding needed', 'Modern, mobile-friendly templates', 'Built-in SEO essentials', 'Simple drag-and-drop editing'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <img src="{{ asset('assets/images/services/AI-Built-Website-.jpg') }}" alt="Website Builder" class="rounded-xl w-full">
            </div>
        </div>
    </div>
</section>

{{-- WordPress Dashboard --}}
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-10 md:p-16 grid lg:grid-cols-2 gap-12 items-center" data-animate>
            <div class="order-2 lg:order-1">
                <img src="{{ asset('assets/images/services/Wordpress-dashboard-.jpg') }}" alt="WordPress Dashboard" class="rounded-xl w-full">
            </div>
            <div class="order-1 lg:order-2">
                <p class="text-xs uppercase tracking-[0.2em] text-brand-500 mb-3">02</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">WordPress Dashboard</h2>
                <h3 class="text-xl text-brand-300 mb-6">Full Control, Made Simple</h3>
                <p class="text-brand-400 leading-relaxed mb-8">Manage your site with the world's most trusted platform. From content and plugins to layouts and themes, everything is at your fingertips.</p>
                <ul class="space-y-3">
                    @foreach(['Easily update pages and content', 'Add tools and extensions in seconds', 'Simple, familiar dashboard', 'Grows with your business'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Online Stores --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">E-Commerce</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Online Stores</h2>
            <p class="text-brand-400 mt-4">Launch your store and start selling with AI-powered tools.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $stores = [
                ['name' => 'Fashion & Apparel', 'img' => 'Fashion-Apparel-Store-.jpg'],
                ['name' => 'Beauty & Skincare', 'img' => 'Beauty-Skincare-Store-.jpg'],
                ['name' => 'Home & Decor', 'img' => 'Home-Decor-Store-.jpg'],
                ['name' => 'Tech & Gadgets', 'img' => 'Tech-Gadget-Store-.jpg'],
                ['name' => 'Lifestyle & Accessories', 'img' => 'Lifestyle-Accessories-Store-.jpg'],
                ['name' => 'Any Business', 'img' => 'widgets.jpg'],
            ];
            @endphp

            @foreach($stores as $i => $store)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('assets/images/services/' . $store['img']) }}" alt="{{ $store['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-white">{{ $store['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Smart Support --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Support</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Smart Support Tools</h2>
            <p class="text-brand-400 mt-4">Your customers expect instant answers. AI handles it all.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $support = [
                ['name' => 'AI Helpdesk', 'img' => 'AI-Helpdesk-.jpg'],
                ['name' => '24/7 Online Store Support', 'img' => '24_7-Support-for-Online-Stores-.jpg'],
                ['name' => 'Knowledge Base + Chatbot', 'img' => 'Knowledge-Base-Chatbot-.jpg'],
                ['name' => 'Service & Bookings', 'img' => 'Service-Booking-Businesses-.jpg'],
                ['name' => 'Multi-Channel', 'img' => 'Multi-channel-.jpg'],
            ];
            @endphp

            @foreach($support as $i => $s)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('assets/images/services/' . $s['img']) }}" alt="{{ $s['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-white">{{ $s['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Bookings --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Scheduling</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Bookings & Scheduling</h2>
            <p class="text-brand-400 mt-4">Appointments that run themselves. No double bookings, no missed meetings.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $bookings = [
                ['name' => 'Restaurant & Cafes', 'img' => 'Restaurant-Cafes-.jpg'],
                ['name' => 'Fitness Studio & Gym', 'img' => 'Fitness-Studio-_-Gym-Classes-.jpg'],
                ['name' => 'Coaching & Consulting', 'img' => 'Coaching-Consulting-Sessions-.jpg'],
                ['name' => 'Clinics & Wellness', 'img' => 'Clinics-Health-Wellness-.jpg'],
                ['name' => 'Events & Tours', 'img' => 'Events-Tours-Experiences-.jpg'],
            ];
            @endphp

            @foreach($bookings as $i => $b)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('assets/images/services/' . $b['img']) }}" alt="{{ $b['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-white">{{ $b['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Social Media --}}
<section class="py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Social Media</p>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text">Social Media Growth</h2>
            <p class="text-brand-400 mt-4">Stay active everywhere with AI-powered content creation.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $social = [
                ['name' => 'Weekly Visual Ideas', 'img' => 'Weekly-Visual-Ideas-.jpg'],
                ['name' => 'Product Highlight Reels', 'img' => 'Product-Highlight-Reels-.jpg'],
                ['name' => 'Story & Reel Prompts', 'img' => 'Story-Reel-Prompts-.jpg'],
                ['name' => 'Community & UGC', 'img' => 'Community-UGC-.jpg'],
                ['name' => 'Carousel & Feed Themes', 'img' => 'Carousel-Feed-Themes-.jpg'],
            ];
            @endphp

            @foreach($social as $i => $s)
                <div class="glass-card rounded-2xl overflow-hidden group" data-animate style="animation-delay: {{ $i * 80 }}ms">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('assets/images/services/' . $s['img']) }}" alt="{{ $s['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold text-white">{{ $s['name'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Hosting & Security + Business Automations --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Hosting --}}
            <div class="glass-card rounded-2xl p-10" data-animate>
                <div class="mb-8">
                    <img src="{{ asset('assets/images/services/security.jpg') }}" alt="Security" class="rounded-xl w-full aspect-video object-cover">
                </div>
                <h3 class="text-2xl font-display font-bold text-white mb-4">Hosting & Security</h3>
                <p class="text-brand-400 mb-6">Fast performance and total peace of mind. Every plan includes secure hosting and free SSL.</p>
                <ul class="space-y-3">
                    @foreach(['99.9% uptime guarantee', 'Free SSL certificate', 'Automatic daily backups', 'Advanced DDoS protection'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Business Automations --}}
            <div class="glass-card rounded-2xl p-10" data-animate>
                <div class="mb-8">
                    <img src="{{ asset('assets/images/services/co-pilot-white.jpg') }}" alt="Business Automations" class="rounded-xl w-full aspect-video object-cover">
                </div>
                <h3 class="text-2xl font-display font-bold text-white mb-4">Business Automations</h3>
                <p class="text-brand-400 mb-6">AI handles the busywork so you can focus on growth. Everything runs automatically.</p>
                <ul class="space-y-3">
                    @foreach(['Instant AI-powered responses', 'Recover abandoned carts', 'Track customer behavior', 'Smart lead nurturing'] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-32 relative z-10">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="glass rounded-3xl p-16 md:p-20 glow relative overflow-hidden" data-animate>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-32 bg-white/[0.03] blur-3xl rounded-full"></div>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text mb-4 relative z-10">Why Our Services Work Together</h2>
            <p class="text-brand-400 mb-10 relative z-10 max-w-xl mx-auto">Your store, bookings, emails, and social channels all stay in sync — giving you more time to focus on growth.</p>
            <a href="#" class="btn-primary text-base px-12 py-4 relative z-10 inline-block">Get Started Today</a>
        </div>
    </div>
</section>

@endsection
