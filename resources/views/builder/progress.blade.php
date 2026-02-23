@extends('layouts.app')
@section('title', 'Building Your Website')

@section('content')
<div class="max-w-2xl mx-auto" x-data="buildProgress()">
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Building "{{ $website->name }}"</h1>
        <p class="text-gray-500 mt-1" x-text="statusMessage">Setting things up...</p>
    </div>

    {{-- Build Steps --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="space-y-0">
            <template x-for="(bStep, index) in steps" :key="index">
                <div class="flex items-start gap-4" :class="index < steps.length - 1 ? 'pb-6' : ''">
                    <div class="flex flex-col items-center">
                        {{-- Icon --}}
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-all"
                            :class="{
                                'bg-green-500 text-white': bStep.status === 'done',
                                'bg-blue-600 text-white': bStep.status === 'active',
                                'bg-gray-200 text-gray-400': bStep.status === 'pending',
                                'bg-red-500 text-white': bStep.status === 'error'
                            }">
                            <template x-if="bStep.status === 'done'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="bStep.status === 'active'">
                                <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </template>
                            <template x-if="bStep.status === 'pending'">
                                <span class="text-xs font-bold" x-text="index + 1"></span>
                            </template>
                            <template x-if="bStep.status === 'error'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </template>
                        </div>
                        {{-- Line --}}
                        <div x-show="index < steps.length - 1" class="w-0.5 flex-1 mt-1"
                            :class="bStep.status === 'done' ? 'bg-green-300' : 'bg-gray-200'"></div>
                    </div>
                    <div class="pt-1">
                        <p class="text-sm font-medium" :class="bStep.status === 'active' ? 'text-blue-600' : (bStep.status === 'done' ? 'text-gray-900' : 'text-gray-400')" x-text="bStep.label"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Error state --}}
    <div x-show="currentStatus === 'error'" x-cloak class="mt-6 bg-red-50 border border-red-200 rounded-xl p-6 text-center">
        <p class="text-red-700 font-medium mb-4">Something went wrong during the build process.</p>
        <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
            Try Again
        </a>
    </div>
</div>

@push('scripts')
<script>
function buildProgress() {
    return {
        currentStatus: '{{ $website->status }}',
        currentStep: 0,
        statusMessage: 'Setting things up...',
        steps: [
            { label: 'Creating website record', status: 'done' },
            { label: 'Assigning server', status: 'active' },
            { label: 'AI generating content', status: 'pending' },
            { label: 'Installing WordPress', status: 'pending' },
            { label: 'Injecting custom content', status: 'pending' },
            { label: 'Configuring DNS', status: 'pending' },
            { label: 'Setting up SSL', status: 'pending' },
            { label: 'Going live!', status: 'pending' },
        ],
        pollInterval: null,
        init() {
            this.simulateProgress();
            this.pollInterval = setInterval(() => this.checkStatus(), 3000);
        },
        simulateProgress() {
            const advance = () => {
                if (this.currentStatus === 'active' || this.currentStatus === 'error') return;
                if (this.currentStep < this.steps.length - 1) {
                    this.steps[this.currentStep].status = 'done';
                    this.currentStep++;
                    this.steps[this.currentStep].status = 'active';
                    this.statusMessage = this.steps[this.currentStep].label + '...';
                    setTimeout(advance, 3000 + Math.random() * 4000);
                }
            };
            setTimeout(advance, 2000);
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
                    }, 1500);
                } else if (data.status === 'error') {
                    clearInterval(this.pollInterval);
                    this.steps.forEach(s => { if (s.status === 'active') s.status = 'error'; });
                    this.statusMessage = 'Build failed. Please try again.';
                }
            } catch (e) {
                // Silently retry on network error
            }
        }
    };
}
</script>
@endpush
@endsection
