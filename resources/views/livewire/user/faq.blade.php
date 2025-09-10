<div x-data="{ activeCategory: 'all', openFaq: null }">
    <x-slot name="page_slug">faq</x-slot>

    <!-- Hero Section -->
    <section class="relative z-10 py-16 md:py-24 bg-orange-500">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                How can we <span class="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-black">help
                    you?</span>
            </h1>
            <p class="text-xl text-slate-100 mb-8 max-w-2xl mx-auto">
                Find answers to your questions quickly and easily.
            </p>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-white/60 backdrop-blur-md rounded-xl p-6 border border-white/30">
                    <div class="text-3xl font-bold text-primary-600 mb-2">{{ $faqCount }}+</div>
                    <div class="text-slate-600">Questions Answered</div>
                </div>
                <div class="bg-white/60 backdrop-blur-md rounded-xl p-6 border border-white/30">
                    <div class="text-3xl font-bold text-accent-500 mb-2">{{ $categoryCount }}</div>
                    <div class="text-slate-600">Categories</div>
                </div>
                <div class="bg-white/60 backdrop-blur-md rounded-xl p-6 border border-white/30">
                    <div class="text-3xl font-bold text-green-500 mb-2">24/7</div>
                    <div class="text-slate-600">Support Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Buttons -->
    <section
        class="relative z-10 py-8 bg-white/60 dark:bg-slate-800 backdrop-blur-md border-y border-gray-200 dark:border-white/30">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4">
                <!-- All Button -->
                <button
                    class="category-btn px-6 py-3 rounded-full font-medium"
                    :class="activeCategory === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-500 text-white hover:bg-orange-500'"
                    @click="activeCategory = 'all'">
                    All
                </button>
                @foreach ($faqCategories as $faqCategory)
                    @if ($faqCategory->faqs_count > 0)
                        <button
                            class="category-btn px-6 py-3 rounded-full font-medium"
                            :class="activeCategory === '{{ $faqCategory->slug }}' ? 'bg-orange-500 text-white' : 'bg-gray-500 text-white hover:bg-orange-500'"
                            @click="activeCategory = '{{ $faqCategory->slug }}'">
                            {{ $faqCategory->name }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
   <section class="relative z-10 py-16" x-data="{ openFaq: null, activeCategory: 'all' }">
    <div class="container mx-auto px-4">
        @foreach ($faqCategories as $category)
            @if ($category->faqs_count > 0)
                <div class="faq-category mb-16 dark:text-white"
                    x-show="activeCategory === 'all' || activeCategory === '{{ $category->slug }}'"
                    x-transition>
                    
                    <!-- Category Heading -->
                    <div class="flex items-center mb-8">
                        <div
                            class="w-12 h-12 bg-gradient-to-r bg-orange-500 rounded-xl flex items-center justify-center mr-4">
                            <i data-lucide="help-circle" class="h-6 w-6 text-white"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-800 dark:text-white dark:bg-gray-900">
                            {{ $category->name }}
                        </h2>
                    </div>

                    <!-- FAQs List -->
                    <div class="grid gap-4">
                        @foreach ($category->faqs as $faq)
                            <div class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 dark:border-gray-700 overflow-hidden"
                                id="faq-{{ $faq->id }}">
                                
                                <!-- Question -->
                                <button
                                    class="faq-question w-full text-left p-6 flex items-center justify-between transition-colors dark:bg-gray-900"
                                    @click="openFaq === {{ $faq->id }} ? openFaq = null : openFaq = {{ $faq->id }}">
                                    <span
                                        class="font-semibold text-slate-800 pr-4 text-xl dark:text-white">{{ $faq->question }}</span>
                                    <i data-lucide="chevron-down"
                                        class="h-5 w-5 text-slate-500 transform transition-transform"
                                        :class="openFaq === {{ $faq->id }} ? 'rotate-180' : ''"></i>
                                </button>

                                <!-- Answer -->
                                <div class="faq-content px-6 pb-4 text-slate-600 leading-relaxed dark:text-white dark:bg-gray-900"
                                    x-show="openFaq === {{ $faq->id }}"
                                    x-collapse>
                                    <p>{{ $faq->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>

    <!-- Contact Section -->
    <section class="relative z-10 py-16 bg-gradient-to-r from-primary-600 bg-orange-500 to-accent-500">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Still have questions?</h2>
            <p class="text-xl text-white/90 mb-8">Our support team is here to help you 24/7.</p>
            <button
                class="bg-white/20 text-white border border-white/30 px-8 py-3 rounded-xl font-semibold hover:bg-white/30 flex items-center justify-center mx-auto">
                <i data-lucide="mail" class="h-5 w-5 mr-2"></i> Send Email
            </button>
        </div>
    </section>

    @push('js')
        <script>
            lucide.createIcons();
        </script>
    @endpush
</div>
