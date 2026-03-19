@extends('site.layouts.app')

@section('title', 'Pricing — WebNewBiz')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <div data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Pricing</p>
            <h1 class="text-5xl md:text-7xl font-display font-bold gradient-text leading-tight">
                Flexible packages for<br>every business
            </h1>
            <p class="text-lg text-brand-400 mt-8 max-w-2xl mx-auto">
                From simple websites to full AI-driven growth engines — pick the plan that's right for you.
            </p>
        </div>

        {{-- Toggle --}}
        <div class="flex items-center justify-center gap-4 mt-12" data-animate>
            <span class="text-sm text-white" id="monthly-label">Monthly</span>
            <button class="relative w-14 h-7 rounded-full bg-white/10 border border-white/10 transition-colors" id="billing-toggle" onclick="toggleBilling()">
                <span class="absolute top-0.5 left-0.5 w-6 h-6 rounded-full bg-white transition-transform duration-300" id="toggle-dot"></span>
            </button>
            <span class="text-sm text-brand-400" id="yearly-label">Yearly <span class="text-emerald-400 text-xs">Save 15%</span></span>
        </div>
    </div>
</section>

{{-- Pricing Cards --}}
<section class="pb-32 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Starter --}}
            <div class="glass-card rounded-2xl p-10" data-animate>
                <h3 class="text-xl font-display font-bold text-white">Starter</h3>
                <div class="mt-6 mb-2">
                    <span class="text-5xl font-display font-bold text-white monthly-price">$1</span>
                    <span class="text-5xl font-display font-bold text-white yearly-price hidden">$84</span>
                    <span class="text-sm text-brand-400 monthly-period">/mo <span class="text-xs">(first 3 months)</span></span>
                    <span class="text-sm text-brand-400 yearly-period hidden">/year</span>
                </div>
                <p class="text-xs text-brand-500 mb-6 monthly-after">then $9/mo</p>
                <p class="text-xs text-brand-500 mb-6 yearly-after hidden">$7/mo billed yearly</p>
                <p class="text-sm text-brand-400 mb-8">Perfect for individuals, freelancers, and startups launching a professional online presence.</p>

                <a href="#" class="btn-primary block text-center py-3 text-sm">Get Started</a>

                <ul class="mt-8 space-y-3">
                    @foreach([
                        'WebNewBiz AI Builder',
                        'AI-Powered Dashboard',
                        'Built-in AI Assistant',
                        'Drag-and-Drop Editor',
                        'AI Image Generation',
                        'Free SSL Certificate',
                        'AI WordPress Converter',
                        '24/7 Customer Support',
                    ] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-400">
                            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Professional --}}
            <div class="rounded-2xl p-10 bg-white text-brand-950 ring-1 ring-white relative" data-animate style="animation-delay: 100ms">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs font-semibold uppercase tracking-widest bg-brand-950 text-white px-4 py-1 rounded-full">Most Popular</span>
                <h3 class="text-xl font-display font-bold text-brand-950">Professional</h3>
                <div class="mt-6 mb-2">
                    <span class="text-5xl font-display font-bold text-brand-950 monthly-price">$29</span>
                    <span class="text-5xl font-display font-bold text-brand-950 yearly-price hidden">$296</span>
                    <span class="text-sm text-brand-500 monthly-period">/mo</span>
                    <span class="text-sm text-brand-500 yearly-period hidden">/year</span>
                </div>
                <p class="text-xs text-brand-400 mb-6 yearly-after hidden">Save $52/year</p>
                <div class="mb-6 monthly-after"></div>
                <p class="text-sm text-brand-500 mb-8">For growing businesses with advanced automation, e-commerce, and CRM scalability.</p>

                <a href="#" class="bg-brand-950 text-white block text-center py-3 rounded-full font-semibold text-sm hover:bg-brand-800 transition-colors">Get Started</a>

                <p class="text-xs font-semibold text-brand-400 mt-8 mb-4 uppercase tracking-wider">Everything in Starter, plus:</p>
                <ul class="space-y-3">
                    @foreach([
                        '24/7 AI Chatbot',
                        'AI Order Processing',
                        'AI Editing Dashboard',
                        'Complete E-commerce System',
                        'Automated Appointment Booking',
                        'Lead Capture & Follow-up Emails',
                        'Business Email Setup',
                        'Priority Technical Support',
                        'High-Performance Hosting',
                        '99.9% Uptime Guarantee',
                        'Advanced SEO Tools',
                        'Complete Website Design & Setup',
                    ] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-600">
                            <svg class="w-4 h-4 text-brand-950 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Premium --}}
            <div class="glass-card rounded-2xl p-10" data-animate style="animation-delay: 200ms">
                <h3 class="text-xl font-display font-bold text-white">Premium</h3>
                <div class="mt-6 mb-2">
                    <span class="text-5xl font-display font-bold text-white monthly-price">$49</span>
                    <span class="text-5xl font-display font-bold text-white yearly-price hidden">$500</span>
                    <span class="text-sm text-brand-400 monthly-period">/mo</span>
                    <span class="text-sm text-brand-400 yearly-period hidden">/year</span>
                </div>
                <p class="text-xs text-brand-500 mb-6 yearly-after hidden">Save $88/year</p>
                <div class="mb-6 monthly-after"></div>
                <p class="text-sm text-brand-400 mb-8">Full automation, enterprise performance, and a complete digital ecosystem.</p>

                <a href="#" class="btn-primary block text-center py-3 text-sm">Get Started</a>

                <p class="text-xs font-semibold text-brand-500 mt-8 mb-4 uppercase tracking-wider">Everything in Professional, plus:</p>
                <ul class="space-y-3">
                    @foreach([
                        'AI Social Media Manager',
                        'Auto-Publishing System',
                        'AI-Powered Ad Campaigns',
                        'Engagement Tracking & Analytics',
                        'Advanced AI Chatbot + Lead Gen',
                        'Advanced E-commerce Suite',
                        'Advanced Analytics Dashboard',
                        'Customer Tracking & Management',
                        '90+ PageSpeed Score',
                        'Cloudflare Enterprise CDN',
                        '10x Faster Load Times',
                        'Instagram/Facebook/WhatsApp AI',
                    ] as $f)
                        <li class="flex items-center gap-3 text-sm text-brand-400">
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
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="glass rounded-3xl p-16 md:p-20 glow relative overflow-hidden" data-animate>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-32 bg-white/[0.03] blur-3xl rounded-full"></div>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text mb-6 relative z-10">No hidden fees.<br>Cancel anytime.</h2>
            <p class="text-brand-400 mb-10 relative z-10">All pricing is transparent. What you see is exactly what you pay.</p>
            <a href="#" class="btn-primary text-base px-12 py-4 relative z-10 inline-block">Get Started for $1</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    let isYearly = false;
    function toggleBilling() {
        isYearly = !isYearly;
        const dot = document.getElementById('toggle-dot');
        const monthlyLabel = document.getElementById('monthly-label');
        const yearlyLabel = document.getElementById('yearly-label');

        if (isYearly) {
            dot.style.transform = 'translateX(28px)';
            monthlyLabel.classList.replace('text-white', 'text-brand-400');
            yearlyLabel.classList.replace('text-brand-400', 'text-white');
        } else {
            dot.style.transform = 'translateX(0)';
            monthlyLabel.classList.replace('text-brand-400', 'text-white');
            yearlyLabel.classList.replace('text-white', 'text-brand-400');
        }

        document.querySelectorAll('.monthly-price').forEach(el => el.classList.toggle('hidden', isYearly));
        document.querySelectorAll('.yearly-price').forEach(el => el.classList.toggle('hidden', !isYearly));
        document.querySelectorAll('.monthly-period').forEach(el => el.classList.toggle('hidden', isYearly));
        document.querySelectorAll('.yearly-period').forEach(el => el.classList.toggle('hidden', !isYearly));
        document.querySelectorAll('.monthly-after').forEach(el => el.classList.toggle('hidden', isYearly));
        document.querySelectorAll('.yearly-after').forEach(el => el.classList.toggle('hidden', !isYearly));
    }
</script>
@endpush
