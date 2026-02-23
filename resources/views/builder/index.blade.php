@extends('layouts.app')
@section('title', 'AI Website Builder')

@section('content')
<div x-data="wizardForm()" class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Build Your Website with AI</h1>
        <p class="text-gray-500 mt-1">{{ $websiteCount }} of {{ $maxWebsites }} websites used</p>
    </div>

    {{-- Step Indicators --}}
    <div class="flex items-center mb-10">
        <template x-for="(label, i) in ['Business Info', 'Your Vision', 'Choose Style', 'Review']" :key="i">
            <div class="flex items-center" :class="i < 3 ? 'flex-1' : ''">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all"
                        :class="step > i + 1 ? 'bg-green-500 text-white' : (step === i + 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500')">
                        <template x-if="step > i + 1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></template>
                        <template x-if="step <= i + 1"><span x-text="i + 1"></span></template>
                    </div>
                    <span class="text-sm font-medium hidden sm:inline" :class="step === i + 1 ? 'text-blue-600' : 'text-gray-500'" x-text="label"></span>
                </div>
                <div x-show="i < 3" class="flex-1 h-0.5 mx-3" :class="step > i + 1 ? 'bg-green-500' : 'bg-gray-200'"></div>
            </div>
        </template>
    </div>

    <form action="{{ route('builder.generate') }}" method="POST">
        @csrf

        {{-- Step 1: Business Info --}}
        <div x-show="step === 1" x-transition>
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Tell us about your business</h2>
                <p class="text-sm text-gray-500 mb-6">This helps our AI create the perfect website for you.</p>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Business Name</label>
                    <input type="text" name="name" x-model="form.name" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="My Awesome Business">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Business Type</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @php
                            $types = [
                                ['value' => 'restaurant', 'label' => 'Restaurant', 'icon' => 'M3 3h18v18H3V3z'],
                                ['value' => 'ecommerce', 'label' => 'E-commerce', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                                ['value' => 'portfolio', 'label' => 'Portfolio', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                ['value' => 'blog', 'label' => 'Blog', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                                ['value' => 'agency', 'label' => 'Agency', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                ['value' => 'saas', 'label' => 'SaaS', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['value' => 'nonprofit', 'label' => 'Nonprofit', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                                ['value' => 'consulting', 'label' => 'Consulting', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['value' => 'other', 'label' => 'Other', 'icon' => 'M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z'],
                            ];
                        @endphp
                        @foreach($types as $type)
                            <button type="button"
                                @click="form.business_type = '{{ $type['value'] }}'"
                                :class="form.business_type === '{{ $type['value'] }}' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                                class="flex items-center gap-2 p-3 rounded-lg border-2 text-sm font-medium transition">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $type['icon'] }}"/></svg>
                                {{ $type['label'] }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="business_type" :value="form.business_type">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" @click="nextStep()" :disabled="!form.name || !form.business_type"
                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    Continue
                </button>
            </div>
        </div>

        {{-- Step 2: Your Vision --}}
        <div x-show="step === 2" x-transition>
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Describe your vision</h2>
                <p class="text-sm text-gray-500 mb-6">Tell our AI what you want on your website. The more detail, the better!</p>

                <div class="mb-4">
                    <textarea name="prompt" x-model="form.prompt" rows="6" maxlength="2000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Describe your ideal website. For example: 'I want a modern restaurant website with a menu page, online reservation form, photo gallery of our dishes, and a section about our chef's story.'"></textarea>
                    <p class="text-right text-xs text-gray-400 mt-1"><span x-text="form.prompt.length"></span>/2000</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Quick suggestions:</p>
                    <div class="flex flex-wrap gap-2">
                        @php $suggestions = ['Include testimonials', 'Add pricing page', 'Show team members', 'Include contact form', 'Add photo gallery', 'Show business hours']; @endphp
                        @foreach($suggestions as $suggestion)
                            <button type="button"
                                @click="addSuggestion('{{ $suggestion }}')"
                                class="px-3 py-1.5 rounded-full border border-gray-200 text-sm text-gray-600 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition">
                                + {{ $suggestion }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="step = 1" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2.5">Back</button>
                <button type="button" @click="nextStep()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">Continue</button>
            </div>
        </div>

        {{-- Step 3: Choose Style --}}
        <div x-show="step === 3" x-transition>
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Choose a style</h2>
                <p class="text-sm text-gray-500 mb-6">Select the visual style that best fits your brand.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $styles = [
                            ['value' => 'modern', 'label' => 'Modern', 'desc' => 'Clean lines, bold typography, vibrant accents', 'colors' => ['#3B82F6', '#1F2937', '#F9FAFB']],
                            ['value' => 'classic', 'label' => 'Classic', 'desc' => 'Timeless elegance, serif fonts, warm tones', 'colors' => ['#92400E', '#1C1917', '#FFFBEB']],
                            ['value' => 'minimal', 'label' => 'Minimal', 'desc' => 'White space, simplicity, subtle details', 'colors' => ['#6B7280', '#111827', '#FFFFFF']],
                            ['value' => 'bold', 'label' => 'Bold', 'desc' => 'Strong colors, large text, high impact', 'colors' => ['#DC2626', '#000000', '#FEF2F2']],
                            ['value' => 'elegant', 'label' => 'Elegant', 'desc' => 'Refined aesthetics, soft gradients, luxury feel', 'colors' => ['#7C3AED', '#1E1B4B', '#F5F3FF']],
                        ];
                    @endphp
                    @foreach($styles as $style)
                        <button type="button"
                            @click="form.style = '{{ $style['value'] }}'"
                            :class="form.style === '{{ $style['value'] }}' ? 'border-blue-600 ring-2 ring-blue-100' : 'border-gray-200 hover:border-gray-300'"
                            class="text-left p-4 rounded-xl border-2 transition">
                            <div class="flex gap-1.5 mb-3">
                                @foreach($style['colors'] as $color)
                                    <div class="w-6 h-6 rounded-full border border-gray-200" style="background-color: {{ $color }}"></div>
                                @endforeach
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $style['label'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $style['desc'] }}</p>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="style" :value="form.style">
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="step = 2" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2.5">Back</button>
                <button type="button" @click="nextStep()" :disabled="!form.style"
                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    Continue
                </button>
            </div>
        </div>

        {{-- Step 4: Review & Build --}}
        <div x-show="step === 4" x-transition>
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">Review & Build</h2>
                <p class="text-sm text-gray-500 mb-6">Make sure everything looks good before we start building.</p>

                <div class="space-y-4">
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Business Name</p>
                            <p class="text-gray-900 font-medium" x-text="form.name"></p>
                        </div>
                        <button type="button" @click="step = 1" class="text-sm text-blue-600 hover:text-blue-700">Edit</button>
                    </div>
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Business Type</p>
                            <p class="text-gray-900 font-medium capitalize" x-text="form.business_type"></p>
                        </div>
                        <button type="button" @click="step = 1" class="text-sm text-blue-600 hover:text-blue-700">Edit</button>
                    </div>
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1 mr-4">
                            <p class="text-sm font-medium text-gray-500">Your Vision</p>
                            <p class="text-gray-900 text-sm mt-1" x-text="form.prompt || 'No description provided'"></p>
                        </div>
                        <button type="button" @click="step = 2" class="text-sm text-blue-600 hover:text-blue-700 flex-shrink-0">Edit</button>
                    </div>
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Style</p>
                            <p class="text-gray-900 font-medium capitalize" x-text="form.style"></p>
                        </div>
                        <button type="button" @click="step = 3" class="text-sm text-blue-600 hover:text-blue-700">Edit</button>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <strong>{{ $maxWebsites - $websiteCount }}</strong> website slot{{ ($maxWebsites - $websiteCount) !== 1 ? 's' : '' }} remaining on your <strong>{{ $plan->name }}</strong> plan.
                    </p>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="step = 3" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2.5">Back</button>
                <button type="submit" :disabled="submitting"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold px-8 py-2.5 rounded-lg transition">
                    <template x-if="!submitting">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Build My Website
                        </span>
                    </template>
                    <template x-if="submitting">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Building...
                        </span>
                    </template>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function wizardForm() {
    return {
        step: 1,
        submitting: false,
        form: {
            name: '',
            business_type: '',
            prompt: '',
            style: 'modern',
        },
        nextStep() {
            if (this.step === 1 && (!this.form.name || !this.form.business_type)) return;
            if (this.step === 3 && !this.form.style) return;
            this.step = Math.min(this.step + 1, 4);
        },
        addSuggestion(text) {
            if (this.form.prompt.length > 0 && !this.form.prompt.endsWith('. ') && !this.form.prompt.endsWith('\n')) {
                this.form.prompt += '. ';
            }
            this.form.prompt += text;
        },
        init() {
            this.$watch('submitting', () => {});
            this.$el.closest('form')?.addEventListener('submit', () => { this.submitting = true; });
        }
    };
}
</script>
@endpush
@endsection
