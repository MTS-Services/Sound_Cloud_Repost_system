<x-user::layout>
    <x-slot name="page_slug">faq</x-slot>



    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .faq-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

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

        /* Pure CSS version */
        html.dark .category-card {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.6), rgba(31, 41, 55, 0.4));
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        html.dark .faq-item {
            background-color: #1f2937;
            /* gray-800 */
            color: white;
        }

        .search-glow {
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(14, 165, 233, 0.1), rgba(245, 158, 11, 0.1));
            animation: float 6s ease-in-out infinite;
        }
    </style>


    <!-- Floating Background Shapes -->
    <div class="floating-shape w-64 h-64 top-10 -left-32 opacity-30" style="animation-delay: 0s;"></div>
    <div class="floating-shape w-48 h-48 top-1/3 -right-24 opacity-20" style="animation-delay: 2s;"></div>
    <div class="floating-shape w-32 h-32 bottom-1/4 left-1/4 opacity-25" style="animation-delay: 4s;"></div>



    <!-- Hero Section -->
    <section class="relative z-10 py-16 md:py-24 bg-orange-500">

        <div class="container mx-auto px-4 text-center">
            <div class="animate-fade-in">
                <h1 class="text-4xl md:text-6xl font-bold text-slate-800 mb-6">
                    How can we
                    <span class="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-gray-800">
                        help you?
                    </span>
                </h1>
                <p class="text-xl text-slate-600 mb-8 max-w-2xl mx-auto">
                    Find answers to your questions quickly and easily. Search through our comprehensive FAQ database or
                    browse by category.
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto animate-fade-in"
                style="animation-delay: 0.4s;">
                <div class="bg-white/60 backdrop-blur-md rounded-xl p-6 border border-white/30">
                    <div class="text-3xl font-bold text-primary-600 mb-2">150+</div>
                    <div class="text-slate-600">Questions Answered</div>
                </div>
                <div class="bg-white/60 backdrop-blur-md rounded-xl p-6 border border-white/30">
                    <div class="text-3xl font-bold text-accent-500 mb-2">8</div>
                    <div class="text-slate-600">Categories</div>
                </div>
                <div class="bg-white/60 backdrop-blur-md rounded-xl p-6 border border-white/30">
                    <div class="text-3xl font-bold text-green-500 mb-2">24/7</div>
                    <div class="text-slate-600">Support Available</div>
                </div>
            </div>
        </div>

    </section>

    <!-- Category Navigation -->
    <section
        class="relative z-10 py-8 bg-white/40 dark:bg-slate-800 bark:text-white backdrop-blur-md border-y border-white/30">

        <div class="container mx-auto px-4">


            <div class="flex flex-wrap justify-center gap-4" id="categoryNav">
                @foreach ($faqCategories as $faqCategory)
                    <button 
                        class="category-btn active px-6 py-3 rounded-full bg-primary-500 text- font-medium   hover:bg-primary-600 "
                        data-category="{{ $faqCategory->slug }}">
                        {{ $faqCategory->name }}
                    </button>
                @endforeach
            </div>

        </div>

    </section>

    <!-- FAQ Content -->
    <main class="relative z-10 py-16">
        <div class="container mx-auto px-4">
            <!-- Getting Started Category -->
            
            @foreach ($faqCategories as $category)
                @if ($category->faqs_count > 0)
                    <div class="faq-category mb-16" data-category="{{ $category->slug }}">
                    <div class="flex items-center mb-8">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 bg-green-600 rounded-xl flex items-center justify-center mr-4">
                            <i data-lucide="play-circle" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-800">{{ $category->name }}</h2>
                        </div>
                    </div>
                    <div class="grid gap-4">
                        @foreach ($category->faqs as $faq)
                            <div
                            class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                            <button
                                class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                                <span class="font-semibold text-slate-800 pr-4">{{ $faq->question }}</span>
                                <i data-lucide="chevron-down"
                                    class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                            </button>
                            <div class="faq-content px-6 text-slate-600 leading-relaxed">
                                <p>{{ $faq->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </main>

    <!-- Contact Section -->
    <section class="relative z-10 py-16 bg-gradient-to-r from-primary-600 bg-orange-500 to-accent-500">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Still have questions?
                </h2>
                <p class="text-xl text-white/90 mb-8">
                    Our support team is here to help you 24/7. Get in touch and we'll get back to you as soon as
                    possible.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">

                    <button
                        class="bg-white/20 text-white border border-white/30 px-8 py-3 rounded-xl font-semibold hover:bg-white/30 transition-colors duration-200 flex items-center justify-center">
                        <i data-lucide="mail" class="h-5 w-5 mr-2"></i>
                        Send Email
                    </button>
                </div>
            </div>
        </div>
    </section>


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
                        btn.classList.remove('active', 'bg-primary-500', 'text-white');
                        btn.classList.add('bg-white/60', 'text-slate-700');
                    });
                    this.classList.add('active', 'bg-primary-500', 'text-white');
                    this.classList.remove('bg-white/60', 'text-slate-700');

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

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');

            // Sample search data (in a real app, this would come from your backend)
            const searchData = [{
                    question: "How do I create my first account?",
                    category: "getting-started",
                    answer: "Creating your account is simple and takes just a few minutes..."
                },
                {
                    question: "How do I update my billing information?",
                    category: "account",
                    answer: "Go to your Account Settings, click on Billing & Payments..."
                },
                {
                    question: "What's included in the free plan?",
                    category: "features",
                    answer: "The free plan includes up to 3 projects, 1GB storage..."
                },
                {
                    question: "What are the system requirements?",
                    category: "technical",
                    answer: "Our platform works on any modern web browser..."
                },
                {
                    question: "How is my data protected?",
                    category: "security",
                    answer: "We use enterprise-grade security including 256-bit SSL encryption..."
                }
            ];

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                const results = searchData.filter(item =>
                    item.question.toLowerCase().includes(query) ||
                    item.answer.toLowerCase().includes(query)
                );

                if (results.length > 0) {
                    searchResults.innerHTML = results.map(result => `
                        <div class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="highlightFAQ('${result.category}', '${result.question}')">
                            <div class="font-semibold text-slate-800 mb-1">${result.question}</div>
                            <div class="text-sm text-slate-600">${result.answer.substring(0, 100)}...</div>
                            <div class="text-xs text-primary-600 mt-1 capitalize">${result.category.replace('-', ' ')}</div>
                        </div>
                    `).join('');
                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.innerHTML =
                        '<div class="p-4 text-slate-600 text-center">No results found</div>';
                    searchResults.classList.remove('hidden');
                }
            });

            // Hide search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });

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

            // Show the specific category
            const categoryButtons = document.querySelectorAll('.category-btn');
            const faqCategories = document.querySelectorAll('.faq-category');

            categoryButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-primary-500', 'text-white');
                btn.classList.add('bg-white/60', 'text-slate-700');
                if (btn.dataset.category === category) {
                    btn.classList.add('active', 'bg-primary-500', 'text-white');
                    btn.classList.remove('bg-white/60', 'text-slate-700');
                }
            });

            faqCategories.forEach(cat => {
                cat.style.display = cat.dataset.category === category ? 'block' : 'none';
            });

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

</x-user::layout>
