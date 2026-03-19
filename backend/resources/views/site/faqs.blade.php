@extends('site.layouts.app')

@section('title', 'FAQs — WebNewBiz')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-20 relative z-10">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <div data-animate>
            <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">FAQ</p>
            <h1 class="text-5xl md:text-7xl font-display font-bold gradient-text leading-tight">
                Frequently Asked<br>Questions
            </h1>
            <p class="text-lg text-brand-400 mt-8">
                Everything you need to know about WebNewBiz.
            </p>
        </div>
    </div>
</section>

{{-- FAQs --}}
<section class="pb-32 relative z-10">
    <div class="max-w-3xl mx-auto px-6">
        @php
        $categories = [
            'Getting Started' => [
                ['q' => 'Do I need coding or technical skills to use this platform?', 'a' => 'Our website builder and WordPress dashboard are designed for beginners. You can create and manage your site with simple clicks — no coding required.'],
                ['q' => 'How fast can I launch my website?', 'a' => 'You can have a fully functional website up and running in just minutes. Simply choose a plan, and your site will be generated instantly.'],
                ['q' => 'Can I connect my own domain?', 'a' => 'Yes. You can use your own custom domain name with any of our plans.'],
                ['q' => 'Is hosting included in all plans?', 'a' => 'Yes. Every plan comes with managed hosting, free SSL certificate, and daily backups.'],
            ],
            'Security & Performance' => [
                ['q' => 'Will my website be secure?', 'a' => 'All sites are protected with SSL encryption, malware protection, and DDoS defense.'],
                ['q' => 'What is the uptime guarantee?', 'a' => 'We provide 99.9% uptime so your website stays online and reliable.'],
            ],
            'Customization' => [
                ['q' => 'Can I edit my website after it\'s launched?', 'a' => 'Yes, you can edit any section — text, images, or design — directly from your WordPress dashboard, or use our smart AI tools to make instant changes.'],
                ['q' => 'Can I add extra features or plugins?', 'a' => 'Yes. You have full access to WordPress plugins and themes to extend your website functionality.'],
                ['q' => 'What if I outgrow my current plan?', 'a' => 'You can upgrade anytime to access advanced features like automation, chat support, or social media tools.'],
            ],
            'E-Commerce & Bookings' => [
                ['q' => 'Does the platform support e-commerce?', 'a' => 'Yes. You can set up an online store with WooCommerce, accept payments, and manage orders. Smart AI setup assistance guides you step by step.'],
                ['q' => 'How does the booking system work?', 'a' => 'Customers can book appointments online, while automated reminders keep your schedule organized and reduce no-shows.'],
                ['q' => 'Can I accept payments for services?', 'a' => 'Yes. You can enable payment gateways like PayPal or Stripe for services, events, or bookings.'],
            ],
            'Integrations' => [
                ['q' => 'Which apps and tools can I integrate?', 'a' => 'You can connect with popular tools like Google Calendar, Zoom, Gmail, Outlook, Slack, Facebook, Instagram, LinkedIn, TikTok, PayPal, Stripe, and more.'],
                ['q' => 'Do you support social media automation?', 'a' => 'Yes. In our Growth Plan, you can auto-publish posts, reels, and captions across multiple platforms with built-in analytics.'],
            ],
            'Billing & Support' => [
                ['q' => 'Is there a free trial?', 'a' => 'Our Starter plan is only $1 for the first 3 months so you can try everything risk-free.'],
                ['q' => 'Can I switch plans later?', 'a' => 'Absolutely. You can upgrade or downgrade anytime depending on your needs.'],
                ['q' => 'What payment methods do you accept?', 'a' => 'We accept major credit/debit cards, PayPal, and Stripe.'],
                ['q' => 'Are there any hidden fees?', 'a' => 'No. All pricing is transparent. What you see on our pricing page is exactly what you pay.'],
                ['q' => 'What kind of support do you offer?', 'a' => 'We provide 24/7 support through live chat and email. You can reach us anytime.'],
                ['q' => 'Do you help with setup?', 'a' => 'Yes. We provide step-by-step guidance to help you launch and customize your site.'],
                ['q' => 'Is consultation available before buying?', 'a' => 'Yes. You can book a free consultation with our team to find the plan that\'s right for you.'],
            ],
        ];
        @endphp

        @foreach($categories as $cat => $faqs)
            <div class="mb-12" data-animate>
                <h2 class="text-lg font-display font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-8 h-[1px] bg-white/20"></span>
                    {{ $cat }}
                </h2>

                <div class="space-y-3">
                    @foreach($faqs as $faq)
                        <div class="glass-card rounded-xl overflow-hidden">
                            <button class="w-full flex items-center justify-between p-5 text-left faq-toggle" onclick="this.parentElement.querySelector('.faq-body').classList.toggle('hidden'); this.querySelector('.faq-icon').classList.toggle('rotate-45')">
                                <span class="font-medium text-white text-sm pr-4">{{ $faq['q'] }}</span>
                                <svg class="faq-icon w-4 h-4 text-brand-400 flex-shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                            </button>
                            <div class="faq-body hidden px-5 pb-5">
                                <p class="text-sm text-brand-400 leading-relaxed">{{ $faq['a'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="py-32 relative z-10 border-t border-white/5">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="glass rounded-3xl p-16 md:p-20 glow relative overflow-hidden" data-animate>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-32 bg-white/[0.03] blur-3xl rounded-full"></div>
            <h2 class="text-4xl md:text-5xl font-display font-bold gradient-text mb-6 relative z-10">Still have questions?</h2>
            <p class="text-brand-400 mb-10 relative z-10">Our team is here to help 24/7.</p>
            <a href="{{ route('contact') }}" class="btn-primary text-base px-12 py-4 relative z-10 inline-block">Contact Us</a>
        </div>
    </div>
</section>

@endsection
