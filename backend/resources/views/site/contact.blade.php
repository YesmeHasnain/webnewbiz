@extends('site.layouts.app')

@section('title', 'Contact Us — WebNewBiz')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-20 relative z-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            {{-- Left --}}
            <div data-animate>
                <p class="text-xs uppercase tracking-[0.3em] text-brand-500 mb-4">Contact</p>
                <h1 class="text-5xl md:text-6xl font-display font-bold leading-tight">
                    <span class="gradient-text">Let's Talk</span>
                </h1>
                <p class="text-lg text-brand-400 mt-6 leading-relaxed">
                    Have questions about our plans or need help getting started? Our team is here 24/7.
                </p>

                <div class="mt-12 space-y-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">Email</h3>
                            <a href="mailto:info@webnewbiz.com" class="text-sm text-brand-400 hover:text-white transition-colors">info@webnewbiz.com</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">Support Hours</h3>
                            <p class="text-sm text-brand-400">24/7 — We're always available</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white mb-1">Free Consultation</h3>
                            <p class="text-sm text-brand-400">Book a call with our team to find the right plan for you.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <img src="{{ asset('assets/images/contact/Lets-Talk-.jpg') }}" alt="Contact" class="rounded-xl w-full">
                </div>
            </div>

            {{-- Form --}}
            <div class="glass-card rounded-2xl p-8 md:p-10 lg:sticky lg:top-32" data-animate>
                <h2 class="text-2xl font-display font-bold text-white mb-2">Send us a message</h2>
                <p class="text-sm text-brand-400 mb-8">We'll get back to you within 24 hours.</p>

                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 mb-6">
                        <p class="text-sm text-emerald-400">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs text-brand-400 mb-2 block">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                   class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-brand-500 focus:border-white/30 focus:outline-none transition-colors"
                                   placeholder="John">
                            @error('first_name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs text-brand-400 mb-2 block">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                   class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-brand-500 focus:border-white/30 focus:outline-none transition-colors"
                                   placeholder="Doe">
                            @error('last_name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-brand-400 mb-2 block">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-brand-500 focus:border-white/30 focus:outline-none transition-colors"
                               placeholder="john@example.com">
                        @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-xs text-brand-400 mb-2 block">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-brand-500 focus:border-white/30 focus:outline-none transition-colors"
                               placeholder="+1 (555) 000-0000">
                    </div>

                    <div>
                        <label class="text-xs text-brand-400 mb-2 block">Message</label>
                        <textarea name="message" rows="5" required
                                  class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-brand-500 focus:border-white/30 focus:outline-none transition-colors resize-none"
                                  placeholder="Tell us about your project...">{{ old('message') }}</textarea>
                        @error('message')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-center block">
                        Send Message
                        <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
