<div>
    <x-slot name="page_slug">faq</x-slot>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    @push('cs')
        <style>
            .faq-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            }

            .faq-content.active {
                max-height: 500px;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        </style>
    @endpush

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
                    class="category-btn px-6 py-3 rounded-full bg-orange-500  text-white hover:bg-orange-500 font-medium"
                    data-category="all">
                    All
                </button>
                @foreach ($faqCategories as $faqCategory)
                    @if ($faqCategory->faqs_count > 0)
                        <button
                            class="category-btn px-6 py-3 rounded-full bg-gray-500  text-white hover:bg-orange-500 font-medium"
                            data-category="{{ $faqCategory->slug }}">
                            {{ $faqCategory->name }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="relative z-10 py-16">
        <div class="container mx-auto px-4">
            @foreach ($faqCategories as $category)
                @if ($category->faqs_count > 0)
                    <div class="faq-category mb-16 dark:text-white" data-category="{{ $category->slug }}">
                        <div class="flex items-center mb-8">
                            <div
                                class="w-12 h-12 bg-gradient-to-r bg-orange-500 rounded-xl flex items-center justify-center mr-4">
                                <i data-lucide="help-circle" class="h-6 w-6 text-white"></i>
                            </div>

                            <h2 class="text-3xl font-bold text-slate-800 dark:text-white dark:bg-gray-900">
                                {{ $category->name }}</h2>
                        </div>
                        <div class="grid gap-4 ">
                            @foreach ($category->faqs as $faq)
                                <div class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 dark:border-gray-700 overflow-hidden"
                                    id="faq-{{ $faq->id }}">
                                    <button
                                        class="faq-question w-full text-left p-6 flex items-center justify-between  transition-colors dark:bg-gray-900">
                                        <span
                                            class="font-samibold text-slate-800 pr-4 text-xl dark:text-white ">{{ $faq->question }}</span>
                                        <i data-lucide="chevron-down"
                                            class="h-5 w-5 text-slate-500 transform transition-transform"></i>
                                    </button>
                                    <div
                                        class="faq-content px-6 text-slate-600 leading-relaxed dark:text-white dark:bg-gray-900">
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
            // Initialize Lucide icons
            lucide.createIcons();

            // FAQ functionality
            document.addEventListener('DOMContentLoaded', function() {
                // FAQ toggle functionality
                const faqQuestions = document.querySelectorAll('.faq-question');
                faqQuestions.forEach(question => {
                    question.addEventListener('click', function() {
                        const faqItem = this.closest('.faq-item');
                        const content = faqItem.querySelector('.faq-content');
                        const chevron = this.querySelector('[data-lucide="chevron-down"]');

                        // Close other open FAQs in the same category
                        const category = faqItem.closest('.faq-category');
                        const otherItems = category.querySelectorAll('.faq-item');
                        otherItems.forEach(item => {
                            if (item !== faqItem) {
                                const otherContent = item.querySelector('.faq-content');
                                const otherChevron = item.querySelector(
                                    '[data-lucide="chevron-down"]');
                                otherContent.classList.remove('active');
                                otherChevron.style.transform = 'rotate(0deg)';
                            }
                        });

                        // Toggle current FAQ
                        content.classList.toggle('active');
                        const isActive = content.classList.contains('active');
                        chevron.style.transform = isActive ? 'rotate(180deg)' : 'rotate(0deg)';
                    });
                });

                // Category filtering
                const categoryButtons = document.querySelectorAll('.category-btn');
                const faqCategories = document.querySelectorAll('.faq-category');

                categoryButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const category = this.dataset.category;

                        // Update active button
                        categoryButtons.forEach(btn => {
                            btn.classList.remove('active', 'text-white', 'bg-orange-500');
                            btn.classList.add('text-slate-100', 'bg-gray-500');
                        });
                        this.classList.add('active', 'bg-orange-500', 'text-white');
                        this.classList.remove('text-slate-100', );

                        // Show/hide categories
                        faqCategories.forEach(cat => {
                            if (category === 'all' || cat.dataset.category === category) {
                                cat.style.display = 'block';
                                cat.style.animation = 'fadeIn 0.5s ease-in-out';
                            } else {
                                cat.style.display = 'none';
                            }
                        });
                    });
                });

                // // Search functionality
                // const searchInput = document.getElementById('searchInput');
                // const searchResults = document.getElementById('searchResults');

                // // Sample search data (in a real app, this would come from your backend)
                // const searchData = [
                //     { question: "How do I create my first account?", category: "getting-started", answer: "Creating your account is simple and takes just a few minutes..." },
                //     { question: "How do I update my billing information?", category: "account", answer: "Go to your Account Settings, click on Billing & Payments..." },
                //     { question: "What's included in the free plan?", category: "features", answer: "The free plan includes up to 3 projects, 1GB storage..." },
                //     { question: "What are the system requirements?", category: "technical", answer: "Our platform works on any modern web browser..." },
                //     { question: "How is my data protected?", category: "security", answer: "We use enterprise-grade security including 256-bit SSL encryption..." }
                // ];

                // searchInput.addEventListener('input', function() {
                //     const query = this.value.toLowerCase().trim();

                //     if (query.length < 2) {
                //         searchResults.classList.add('hidden');
                //         return;
                //     }

                //     const results = searchData.filter(item =>
                //         item.question.toLowerCase().includes(query) ||
                //         item.answer.toLowerCase().includes(query)
                //     );

                //     if (results.length > 0) {
                //         searchResults.innerHTML = results.map(result => `
        //             <div class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="highlightFAQ('${result.category}', '${result.question}')">
        //                 <div class="font-semibold text-slate-800 mb-1">${result.question}</div>
        //                 <div class="text-sm text-slate-600">${result.answer.substring(0, 100)}...</div>
        //                 <div class="text-xs text-primary-600 mt-1 capitalize">${result.category.replace('-', ' ')}</div>
        //             </div>
        //         `).join('');
                //         searchResults.classList.remove('hidden');
                //     } else {
                //         searchResults.innerHTML = '<div class="p-4 text-slate-600 text-center">No results found</div>';
                //         searchResults.classList.remove('hidden');
                //     }
                // });

                // Hide search results when clicking outside
                // document.addEventListener('click', function(e) {
                //     if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                //         searchResults.classList.add('hidden');
                //     }
                // });

                // Smooth scrolling for navigation
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            });

            // Function to highlight specific FAQ from search results
            function highlightFAQ(category, question) {
                // Hide search results
                document.getElementById('searchResults').classList.add('hidden');
                document.getElementById('searchInput').value = '';

                // // Show the specific category
                // const categoryButtons = document.querySelectorAll('.category-btn');
                // const faqCategories = document.querySelectorAll('.faq-category');

                // categoryButtons.forEach(btn => {
                //     btn.classList.remove('active', 'bg-primary-500', 'text-white');
                //     btn.classList.add('bg-white/60', 'text-slate-700');
                //     if (btn.dataset.category === category) {
                //         btn.classList.add('active', 'bg-primary-500', 'text-white');
                //         btn.classList.remove('bg-white/60', 'text-slate-700');
                //     }
                // });

                // faqCategories.forEach(cat => {
                //     cat.style.display = cat.dataset.category === category ? 'block' : 'none';
                // });

                // Find and open the specific FAQ
                setTimeout(() => {
                    const targetCategory = document.querySelector(`[data-category="${category}"]`);
                    const faqItems = targetCategory.querySelectorAll('.faq-question');

                    faqItems.forEach(item => {
                        if (item.textContent.trim() === question) {
                            // Scroll to the FAQ
                            item.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            // Open the FAQ
                            setTimeout(() => {
                                item.click();
                                // Add highlight effect
                                const faqItem = item.closest('.faq-item');
                                faqItem.style.background = 'rgba(14, 165, 233, 0.1)';
                                setTimeout(() => {
                                    faqItem.style.background = '';
                                }, 2000);
                            }, 500);
                        }
                    });
                }, 100);
            }
        </script>
    @endpush
</div>
