<footer class="bg-white border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-500">&copy; {{ date('Y') }} Webnewbiz. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ url('/features') }}" class="text-sm text-gray-500 hover:text-gray-700">Features</a>
                <a href="{{ url('/pricing') }}" class="text-sm text-gray-500 hover:text-gray-700">Pricing</a>
            </div>
        </div>
    </div>
</footer>
