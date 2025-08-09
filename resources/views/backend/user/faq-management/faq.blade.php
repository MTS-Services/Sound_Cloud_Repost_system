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

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-12 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search for answers..."
                        class="w-full px-6 py-4 pl-14 text-lg bg-white/80 backdrop-blur-md border border-white/30 rounded-2xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent search-glow transition-all duration-300">
                    <i data-lucide="search"
                        class="absolute left-5 top-1/2 transform -translate-y-1/2 h-6 w-6 text-slate-400"></i>
                    <div id="searchResults"
                        class="absolute top-full left-0 right-0 bg-white rounded-xl shadow-xl mt-2 hidden z-50 max-h-96 overflow-y-auto">
                    </div>
                </div>
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
                <button
                    class="category-btn active px-6 py-3 rounded-full bg-primary-500 text-white font-medium transition-all duration-300 hover:bg-primary-600 hover:scale-105"
                    data-category="all">
                    All Categories
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="getting-started">
                    Getting Started
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="account">
                    Account & Billing
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="features">
                    Features
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="technical">
                    Technical
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="security">
                    Security
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="integrations">
                    Integrations
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="troubleshooting">
                    Troubleshooting
                </button>
                <button
                    class="category-btn px-6 py-3 rounded-full bg-white/60 text-slate-700 font-medium transition-all duration-300 hover:bg-white hover:scale-105"
                    data-category="policies">
                    Policies
                </button>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <main class="relative z-10 py-16">
        <div class="container mx-auto px-4">
            <!-- Getting Started Category -->
            <div class="faq-category mb-16" data-category="getting-started">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 bg-green-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="play-circle" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Getting Started</h2>
                        <p class="text-slate-600">Everything you need to know to begin your journey</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">How do I create my first account?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Creating your account is simple and takes just a few minutes. Click the "Sign Up" button
                                in the top right corner, enter your email address, create a secure password, and verify
                                your email. You'll be guided through a quick onboarding process to set up your profile
                                and preferences.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">What information do I need to provide during
                                registration?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>You'll need to provide a valid email address, create a password, and choose a username.
                                Optionally, you can add your full name, profile picture, and company information. All
                                personal information is encrypted and stored securely according to our privacy policy.
                            </p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">How long does the setup process take?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>The basic setup takes about 2-3 minutes. This includes account creation, email
                                verification, and basic profile setup. If you choose to configure advanced settings or
                                integrations, it may take an additional 5-10 minutes depending on your needs.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account & Billing Category -->
            <div class="faq-category mb-16" data-category="account">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="user-circle" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Account & Billing</h2>
                        <p class="text-slate-600">Manage your account settings and billing information</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">How do I update my billing
                                information?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Go to your Account Settings, click on "Billing & Payments," and select "Update Payment
                                Method." You can add new credit cards, update existing ones, or change your billing
                                address. All payment information is processed securely through our encrypted payment
                                gateway.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">Can I change my subscription plan
                                anytime?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately
                                for upgrades, while downgrades take effect at the end of your current billing cycle.
                                You'll receive a prorated credit or charge based on the timing of your change.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">What payment methods do you accept?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>We accept all major credit cards (Visa, MasterCard, American Express, Discover), PayPal,
                                Apple Pay, Google Pay, and bank transfers for enterprise accounts. All transactions are
                                processed securely with 256-bit SSL encryption.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Category -->
            <div class="faq-category mb-16" data-category="features">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 bg-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="zap" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Features</h2>
                        <p class="text-slate-600">Learn about our powerful features and capabilities</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">What's included in the free plan?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>The free plan includes up to 3 projects, 1GB storage, basic analytics, community support,
                                and access to core features. You can upgrade anytime to unlock advanced features like
                                unlimited projects, priority support, and advanced integrations.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">How do I enable two-factor
                                authentication?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Go to Security Settings in your account dashboard, click "Enable 2FA," and follow the
                                setup wizard. You can use authenticator apps like Google Authenticator, Authy, or
                                receive SMS codes. We highly recommend enabling 2FA for enhanced account security.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">Can I collaborate with team members?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Yes! Pro and Enterprise plans include team collaboration features. You can invite team
                                members, set permissions, share projects, and collaborate in real-time. Each team member
                                gets their own account with customizable access levels.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Category -->
            <div class="faq-category mb-16" data-category="technical">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-red-400 to-red-600 bg-red-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="settings" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Technical</h2>
                        <p class="text-slate-600">Technical specifications and requirements</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">What are the system requirements?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Our platform works on any modern web browser (Chrome 90+, Firefox 88+, Safari 14+, Edge
                                90+). For mobile, we support iOS 13+ and Android 8+. A stable internet connection is
                                required, with minimum 1 Mbps for basic features and 5 Mbps for video features.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">Do you have an API for developers?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Yes, we offer a comprehensive REST API with full documentation. Pro and Enterprise plans
                                include API access with different rate limits. Visit our Developer Portal for API
                                documentation, SDKs, and code examples in multiple programming languages.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">How do I export my data?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>You can export your data anytime from the Account Settings page. We support multiple
                                formats including JSON, CSV, and XML. Large exports are processed in the background and
                                you'll receive an email when ready for download. Data exports include all your projects,
                                settings, and metadata.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Category -->
            <div class="faq-category mb-16" data-category="security">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 bg-orange-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="shield" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Security</h2>
                        <p class="text-slate-600">Your security and privacy are our top priority</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">How is my data protected?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>We use enterprise-grade security including 256-bit SSL encryption, regular security
                                audits, SOC 2 compliance, and data centers with 24/7 monitoring. All data is encrypted
                                at rest and in transit. We never share your personal information with third parties
                                without your consent.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">What happens if I forget my
                                password?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Click "Forgot Password" on the login page and enter your email address. You'll receive a
                                secure reset link within minutes. The link expires after 24 hours for security. If you
                                have 2FA enabled, you'll need access to your authenticator app to complete the reset
                                process.</p>
                        </div>
                    </div>
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">Do you comply with GDPR and other privacy
                                regulations?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Yes, we are fully GDPR compliant and also adhere to CCPA, PIPEDA, and other major privacy
                                regulations. You have full control over your data including the right to access, modify,
                                or delete it. Our privacy policy details all data handling practices and your rights.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional categories would follow the same pattern... -->
            <!-- For brevity, I'll add a few more key categories -->

            <!-- Integrations Category -->
            <div class="faq-category mb-16" data-category="integrations">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-indigo-400 to-indigo-600 bg-indigo-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="link" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Integrations</h2>
                        <p class="text-slate-600">Connect with your favorite tools and services</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">Which third-party services do you integrate
                                with?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>We integrate with 100+ popular services including Slack, Microsoft Teams, Google
                                Workspace, Salesforce, HubSpot, Zapier, GitHub, Jira, and many more. New integrations
                                are added regularly based on user feedback and demand.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting Category -->
            <div class="faq-category mb-16" data-category="troubleshooting">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-pink-400 to-pink-600 bg-pink-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="wrench" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Troubleshooting</h2>
                        <p class="text-slate-600">Common issues and their solutions</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">Why is the platform running slowly?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>Slow performance can be caused by browser cache, network connectivity, or high server
                                load. Try clearing your browser cache, checking your internet connection, or switching
                                to a different browser. If issues persist, check our status page or contact support.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policies Category -->
            <div class="faq-category mb-16" data-category="policies">
                <div class="flex items-center mb-8">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-teal-400 to-teal-600 bg-teal-600 rounded-xl flex items-center justify-center mr-4">
                        <i data-lucide="file-text" class="h-6 w-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">Policies</h2>
                        <p class="text-slate-600">Terms, privacy, and usage policies</p>
                    </div>
                </div>
                <div class="grid gap-4">
                    <div
                        class="faq-item bg-white/70 backdrop-blur-md rounded-xl border border-white/30 overflow-hidden hover:shadow-lg transition-all duration-300">
                        <button
                            class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-white/50 transition-colors duration-200">
                            <span class="font-semibold text-slate-800 pr-4">What is your refund policy?</span>
                            <i data-lucide="chevron-down"
                                class="h-5 w-5 text-slate-500 transform transition-transform duration-200"></i>
                        </button>
                        <div class="faq-content px-6 text-slate-600 leading-relaxed">
                            <p>We offer a 30-day money-back guarantee for all paid plans. If you're not satisfied,
                                contact our support team within 30 days of your purchase for a full refund. Refunds are
                                processed within 5-7 business days to your original payment method.</p>
                        </div>
                    </div>
                </div>
            </div>
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
