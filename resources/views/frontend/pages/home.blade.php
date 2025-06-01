<x-frontend::layout>

    <x-slot name="title">Home</x-slot>
    <x-slot name="page_slug">home</x-slot>

    @push('css')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

            body {
                font-family: 'Inter', sans-serif;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .gradient-bg {
                background: linear-gradient(135deg, #ff3e3e 0%, #ff7e54 100%);
            }

            .testimonial-card {
                transition: transform 0.3s ease;
            }

            .testimonial-card:hover {
                transform: translateY(-5px);
            }

            .nav-link.active {
                color: #ff3e3e !important;
                font-weight: 600;
            }

            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -8px;
                left: 0;
                right: 0;
                height: 2px;
                background-color: #ff3e3e;
                border-radius: 1px;
            }

            .nav-link {
                position: relative;
            }

            .theme-toggle {
                transition: transform 0.3s ease;
            }

            .theme-toggle:hover {
                transform: scale(1.1);
            }

            .dark .gradient-bg {
                background: linear-gradient(135deg, #ff3e3e 0%, #ff7e54 100%);
            }
        </style>
    @endpush

    <main>

        <body class="bg-white dark:bg-dark-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">
            <!-- Navigation -->
            <nav class="bg-white dark:bg-dark-800 shadow-sm sticky top-0 z-50 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <span class="text-2xl font-bold text-primary-600">Repostchain</span>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="#features"
                                class="nav-link text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">Features</a>
                            <a href="#how-it-works"
                                class="nav-link text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">How
                                It Works</a>
                            <a href="#pricing"
                                class="nav-link text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">Pricing</a>
                            <a href="#about"
                                class="nav-link text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">About</a>
                            <a href="#testimonials"
                                class="nav-link text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">Testimonials</a>
                            <a href="#faq"
                                class="nav-link text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">FAQ</a>

                            <!-- Theme Toggle -->
                            <button id="theme-toggle"
                                class="theme-toggle p-2 rounded-lg bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-600 transition-colors duration-200">
                                <i class="fas fa-sun dark:hidden"></i>
                                <i class="fas fa-moon hidden dark:block"></i>
                            </button>

                            <a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors duration-200 font-medium">Login</a>
                            <a href="#"
                                class="bg-primary-600 text-white px-4 py-2 rounded-md font-medium hover:bg-primary-700 transition-colors duration-200">Sign
                                Up Free</a>
                        </div>
                        <div class="flex md:hidden items-center space-x-2">
                            <!-- Mobile Theme Toggle -->
                            <button id="mobile-theme-toggle"
                                class="theme-toggle p-2 rounded-lg bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-300">
                                <i class="fas fa-sun dark:hidden"></i>
                                <i class="fas fa-moon hidden dark:block"></i>
                            </button>
                            <button type="button" class="text-gray-700 dark:text-gray-300" id="mobile-menu-button">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="hidden md:hidden bg-white dark:bg-dark-800 shadow-md transition-colors duration-300"
                    id="mobile-menu">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="#features"
                            class="nav-link block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">Features</a>
                        <a href="#how-it-works"
                            class="nav-link block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">How
                            It Works</a>
                        <a href="#pricing"
                            class="nav-link block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">Pricing</a>
                        <a href="#about"
                            class="nav-link block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">About</a>
                        <a href="#testimonials"
                            class="nav-link block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">Testimonials</a>
                        <a href="#faq"
                            class="nav-link block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">FAQ</a>
                        <a href="#"
                            class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-dark-700 hover:text-primary-600 rounded-md">Login</a>
                        <a href="#"
                            class="block px-3 py-2 bg-primary-600 text-white rounded-md font-medium hover:bg-primary-700 transition-colors duration-200 text-center mt-2">Sign
                            Up Free</a>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <section class="relative bg-gradient-to-r from-dark-900 to-dark-800 text-white overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-pattern"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 relative z-10">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 mb-10 md:mb-0">
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                                Promote Your Music.<br>
                                <span class="text-primary-500">Build Your Network.</span><br>
                                Get Real Feedback.
                            </h1>
                            <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-lg">
                                Connect with 400,000+ SoundCloud artists and producers. Grow your audience and get
                                valuable insights from real musicians.
                            </p>
                            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                                <a href="#"
                                    class="gradient-bg text-white px-8 py-4 rounded-lg font-semibold text-center hover:opacity-90 transition-opacity duration-200 shadow-lg">
                                    Start For Free
                                </a>
                                <a href="#how-it-works"
                                    class="bg-white bg-opacity-10 text-white px-8 py-4 rounded-lg font-semibold text-center hover:bg-opacity-20 transition-all duration-200 backdrop-blur-sm">
                                    How It Works
                                </a>
                            </div>
                        </div>
                        <div class="md:w-1/2">
                            <div class="relative">
                                <div
                                    class="absolute -top-10 -right-10 w-40 h-40 bg-primary-500 rounded-full opacity-20 blur-3xl">
                                </div>
                                <img src="https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80"
                                    alt="Artist in studio" class="rounded-xl shadow-2xl w-full object-cover h-[400px]">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="bg-white dark:bg-dark-900 py-12 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div
                            class="p-6 rounded-xl bg-gray-50 dark:bg-dark-800 hover:shadow-md transition-all duration-300">
                            <div class="text-primary-600 mb-2">
                                <i class="fas fa-users text-3xl"></i>
                            </div>
                            <div class="text-3xl font-bold mb-2">400,000+</div>
                            <div class="text-gray-600 dark:text-gray-400">Active Artists</div>
                        </div>
                        <div
                            class="p-6 rounded-xl bg-gray-50 dark:bg-dark-800 hover:shadow-md transition-all duration-300">
                            <div class="text-primary-600 mb-2">
                                <i class="fas fa-exchange-alt text-3xl"></i>
                            </div>
                            <div class="text-3xl font-bold mb-2">3.4M+</div>
                            <div class="text-gray-600 dark:text-gray-400">Monthly Promotions</div>
                        </div>
                        <div
                            class="p-6 rounded-xl bg-gray-50 dark:bg-dark-800 hover:shadow-md transition-all duration-300">
                            <div class="text-primary-600 mb-2">
                                <i class="fas fa-comment-alt text-3xl"></i>
                            </div>
                            <div class="text-3xl font-bold mb-2">2.5M+</div>
                            <div class="text-gray-600 dark:text-gray-400">Comments & Feedback</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- What is Section -->
            <section class="py-16 bg-gray-50 dark:bg-dark-800 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">What is Repostchain?</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                            Repostchain is a community of musicians helping each other grow. By connecting with a
                            community of like-minded creators, you can amplify your reach, gain genuine engagement, and
                            receive valuable feedback from fellow musicians who understand your craft.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-16 bg-white dark:bg-dark-900 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Feature 1 -->
                    <div class="flex flex-col md:flex-row items-center mb-24">
                        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-12">
                            <div class="inline-block text-primary-600 mb-4">
                                <span
                                    class="px-4 py-1 rounded-full bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 font-medium">Feature
                                    01</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold mb-4">Boost Your Music Reach by Teaming Up with
                                Fellow Artists</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Get noticed by sharing your music with other talented performers in your genre. Our
                                intelligent matching system connects you with the perfect audience for your sound,
                                ensuring meaningful engagement and growth.
                            </p>
                            <a href="#"
                                class="text-primary-600 font-medium flex items-center hover:text-primary-700 transition-colors duration-200">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        <div class="md:w-1/2">
                            <div class="bg-gray-100 dark:bg-dark-800 p-2 rounded-xl shadow-lg">
                                <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                    alt="Music promotion dashboard" class="rounded-lg w-full">
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col md:flex-row-reverse items-center mb-24">
                        <div class="md:w-1/2 mb-8 md:mb-0 md:pl-12">
                            <div class="inline-block text-primary-600 mb-4">
                                <span
                                    class="px-4 py-1 rounded-full bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 font-medium">Feature
                                    02</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold mb-4">Expand Your Network and Join a Vibrant
                                Community</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Connect with like-minded artists and discover new collaboration opportunities. Our
                                platform makes it easy to find and engage with musicians who share your vision and can
                                help take your sound to the next level.
                            </p>
                            <a href="#"
                                class="text-primary-600 font-medium flex items-center hover:text-primary-700 transition-colors duration-200">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        <div class="md:w-1/2">
                            <div class="bg-gray-100 dark:bg-dark-800 p-2 rounded-xl shadow-lg">
                                <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                    alt="Music community" class="rounded-lg w-full">
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 mb-8 md:mb-0 md:pr-12">
                            <div class="inline-block text-primary-600 mb-4">
                                <span
                                    class="px-4 py-1 rounded-full bg-primary-50 dark:bg-primary-900 text-primary-600 dark:text-primary-400 font-medium">Feature
                                    03</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold mb-4">Receive Valuable Feedback from Fellow
                                Musicians</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Get constructive criticism and praise from people who understand music. Our feedback
                                system ensures you receive quality insights that can help you refine your sound and grow
                                as an artist.
                            </p>
                            <a href="#"
                                class="text-primary-600 font-medium flex items-center hover:text-primary-700 transition-colors duration-200">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        <div class="md:w-1/2">
                            <div class="bg-gray-100 dark:bg-dark-800 p-2 rounded-xl shadow-lg">
                                <img src="https://images.unsplash.com/photo-1516223725307-6f76b9ec8742?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80"
                                    alt="Feedback interface" class="rounded-lg w-full">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section id="how-it-works" class="py-16 bg-gray-50 dark:bg-dark-800 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">How It Works</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                            Get started in minutes and begin growing your music career today
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Step 1 -->
                        <div
                            class="bg-white dark:bg-dark-900 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xl mb-6">
                                1</div>
                            <h3 class="text-xl font-bold mb-4">Connect your SoundCloud</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                We connect directly to your SoundCloud account with secure OAuth. No passwords stored,
                                just a simple connection to your music.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div
                            class="bg-white dark:bg-dark-900 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xl mb-6">
                                2</div>
                            <h3 class="text-xl font-bold mb-4">Choose your track to promote</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Select which of your tracks you want to promote. Our algorithm will match you with
                                similar artists in your genre.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div
                            class="bg-white dark:bg-dark-900 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div
                                class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xl mb-6">
                                3</div>
                            <h3 class="text-xl font-bold mb-4">See your SoundCloud plays increase</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                As you engage with other artists, they'll engage with you. Watch your plays, likes, and
                                comments grow naturally.
                            </p>
                        </div>
                    </div>

                    <div class="text-center mt-12">
                        <a href="#"
                            class="gradient-bg text-white px-8 py-4 rounded-lg font-semibold inline-block hover:opacity-90 transition-opacity duration-200 shadow-lg">
                            Get Started Now
                        </a>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="py-16 bg-white dark:bg-dark-900 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Simple, Transparent Pricing</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                            Choose the perfect plan to boost your music career. Start free and upgrade when you're ready
                            to accelerate your growth.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                        <!-- Free Plan -->
                        <div
                            class="bg-white dark:bg-dark-800 border-2 border-gray-200 dark:border-dark-700 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold mb-2">Starter</h3>
                                <div class="text-4xl font-bold mb-2">Free</div>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Perfect for getting started</p>
                            </div>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>50 promotion credits/month</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Basic genre matching</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Community access</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Basic analytics</span>
                                </li>
                                <li class="flex items-center text-gray-400">
                                    <i class="fas fa-times mr-3"></i>
                                    <span>Priority support</span>
                                </li>
                                <li class="flex items-center text-gray-400">
                                    <i class="fas fa-times mr-3"></i>
                                    <span>Advanced targeting</span>
                                </li>
                            </ul>

                            <button
                                class="w-full bg-gray-100 dark:bg-dark-700 text-gray-800 dark:text-gray-200 py-3 rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-dark-600 transition-colors duration-200">
                                Get Started Free
                            </button>
                        </div>

                        <!-- Pro Plan -->
                        <div
                            class="bg-white dark:bg-dark-800 border-2 border-primary-500 rounded-2xl p-8 relative hover:shadow-lg transition-all duration-300">
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-primary-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most
                                    Popular</span>
                            </div>

                            <div class="text-center">
                                <h3 class="text-2xl font-bold mb-2">Pro</h3>
                                <div class="text-4xl font-bold mb-2">$19<span
                                        class="text-lg text-gray-600 dark:text-gray-400">/month</span></div>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">For serious artists</p>
                            </div>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>500 promotion credits/month</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Advanced genre matching</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Priority community access</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Detailed analytics</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Priority support</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Geographic targeting</span>
                                </li>
                            </ul>

                            <button
                                class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity duration-200">
                                Start Pro Plan
                            </button>
                        </div>

                        <!-- Premium Plan -->
                        <div
                            class="bg-white dark:bg-dark-800 border-2 border-gray-200 dark:border-dark-700 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold mb-2">Premium</h3>
                                <div class="text-4xl font-bold mb-2">$49<span
                                        class="text-lg text-gray-600 dark:text-gray-400">/month</span></div>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">For professional artists</p>
                            </div>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Unlimited promotion credits</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>AI-powered matching</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>VIP community access</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Advanced analytics & insights</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>24/7 priority support</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span>Custom targeting options</span>
                                </li>
                            </ul>

                            <button
                                class="w-full bg-dark-800 dark:bg-gray-200 text-white dark:text-dark-800 py-3 rounded-lg font-semibold hover:bg-dark-900 dark:hover:bg-gray-300 transition-colors duration-200">
                                Start Premium
                            </button>
                        </div>
                    </div>

                    <!-- Credit Packages -->
                    <div class="mt-16">
                        <div class="text-center mb-12">
                            <h3 class="text-2xl font-bold mb-4">Need More Credits?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Purchase additional promotion credits anytime
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                            <!-- Credit Package 1 -->
                            <div
                                class="bg-gray-50 dark:bg-dark-800 rounded-xl p-6 text-center hover:shadow-md transition-all duration-300">
                                <div class="text-primary-600 mb-3">
                                    <i class="fas fa-coins text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-bold mb-2">100 Credits</h4>
                                <div class="text-2xl font-bold text-primary-600 mb-4">$9</div>
                                <button
                                    class="w-full bg-primary-600 text-white py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors duration-200">
                                    Buy Now
                                </button>
                            </div>

                            <!-- Credit Package 2 -->
                            <div
                                class="bg-gray-50 dark:bg-dark-800 rounded-xl p-6 text-center hover:shadow-md transition-all duration-300 relative">
                                <div class="absolute -top-2 left-1/2 transform -translate-x-1/2">
                                    <span
                                        class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">Best
                                        Value</span>
                                </div>
                                <div class="text-primary-600 mb-3">
                                    <i class="fas fa-coins text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-bold mb-2">250 Credits</h4>
                                <div class="text-2xl font-bold text-primary-600 mb-1">$19</div>
                                <div class="text-sm text-gray-500 mb-3">Save 24%</div>
                                <button
                                    class="w-full bg-primary-600 text-white py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors duration-200">
                                    Buy Now
                                </button>
                            </div>

                            <!-- Credit Package 3 -->
                            <div
                                class="bg-gray-50 dark:bg-dark-800 rounded-xl p-6 text-center hover:shadow-md transition-all duration-300">
                                <div class="text-primary-600 mb-3">
                                    <i class="fas fa-coins text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-bold mb-2">500 Credits</h4>
                                <div class="text-2xl font-bold text-primary-600 mb-1">$35</div>
                                <div class="text-sm text-gray-500 mb-3">Save 30%</div>
                                <button
                                    class="w-full bg-primary-600 text-white py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors duration-200">
                                    Buy Now
                                </button>
                            </div>

                            <!-- Credit Package 4 -->
                            <div
                                class="bg-gray-50 dark:bg-dark-800 rounded-xl p-6 text-center hover:shadow-md transition-all duration-300">
                                <div class="text-primary-600 mb-3">
                                    <i class="fas fa-coins text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-bold mb-2">1000 Credits</h4>
                                <div class="text-2xl font-bold text-primary-600 mb-1">$59</div>
                                <div class="text-sm text-gray-500 mb-3">Save 41%</div>
                                <button
                                    class="w-full bg-primary-600 text-white py-2 rounded-lg font-medium hover:bg-primary-700 transition-colors duration-200">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Money Back Guarantee -->
                    <div class="mt-12 text-center">
                        <div
                            class="inline-flex items-center bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 px-4 py-2 rounded-full">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span class="font-medium">30-day money-back guarantee</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Us Section -->
            <section id="about" class="py-16 bg-gray-50 dark:bg-dark-800 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">About Repostchain</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                            We're passionate about empowering independent artists and building a thriving music
                            community
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-16">
                        <div>
                            <h3 class="text-2xl md:text-3xl font-bold mb-6">Our Mission</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg leading-relaxed">
                                At Repostchain, we believe every artist deserves to be heard. We're on a mission to
                                democratize music promotion by creating a platform where musicians can support each
                                other's growth through genuine engagement and meaningful connections.
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg leading-relaxed">
                                Founded by musicians for musicians, we understand the challenges of breaking through the
                                noise in today's saturated music landscape. That's why we built a community-driven
                                platform that prioritizes authentic relationships over artificial metrics.
                            </p>
                            <div class="flex items-center space-x-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-primary-600">2019</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Founded</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-primary-600">400K+</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Artists</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-primary-600">50M+</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Plays Generated</div>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <div
                                class="absolute -top-10 -left-10 w-40 h-40 bg-primary-500 rounded-full opacity-10 blur-3xl">
                            </div>
                            <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                alt="Team working" class="rounded-xl shadow-2xl w-full object-cover h-[400px]">
                        </div>
                    </div>

                    <!-- Values Section -->
                    <div class="mb-16">
                        <h3 class="text-2xl md:text-3xl font-bold text-center mb-12">Our Values</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-heart text-primary-600 dark:text-primary-400 text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-bold mb-3">Authenticity</h4>
                                <p class="text-gray-600 dark:text-gray-400">
                                    We believe in real connections between real artists. No bots, no fake engagement -
                                    just genuine music lovers supporting each other.
                                </p>
                            </div>
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-primary-600 dark:text-primary-400 text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-bold mb-3">Community</h4>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Music is better together. We foster a supportive environment where artists can
                                    learn, grow, and succeed alongside their peers.
                                </p>
                            </div>
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-rocket text-primary-600 dark:text-primary-400 text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-bold mb-3">Innovation</h4>
                                <p class="text-gray-600 dark:text-gray-400">
                                    We continuously evolve our platform with cutting-edge features that help artists
                                    reach new audiences and achieve their goals.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Team Section -->
                    <div>
                        <h3 class="text-2xl md:text-3xl font-bold text-center mb-12">Meet Our Team</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                    alt="CEO" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                                <h4 class="text-xl font-bold mb-2">Alex Rodriguez</h4>
                                <p class="text-primary-600 font-medium mb-2">CEO & Co-Founder</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">
                                    Former music producer with 10+ years in the industry. Passionate about helping
                                    independent artists succeed.
                                </p>
                            </div>
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80"
                                    alt="CTO" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                                <h4 class="text-xl font-bold mb-2">Sarah Chen</h4>
                                <p class="text-primary-600 font-medium mb-2">CTO & Co-Founder</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">
                                    Tech visionary and musician who combines her love for music with cutting-edge
                                    technology solutions.
                                </p>
                            </div>
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80"
                                    alt="Head of Community" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                                <h4 class="text-xl font-bold mb-2">Marcus Johnson</h4>
                                <p class="text-primary-600 font-medium mb-2">Head of Community</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">
                                    DJ and community builder who ensures our platform remains a welcoming space for all
                                    artists.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="py-16 bg-white dark:bg-dark-900 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">What Our Artists Say</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                            Join thousands of musicians who are growing their careers with Repostchain
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Testimonial 1 -->
                        <div class="testimonial-card bg-gray-50 dark:bg-dark-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full object-cover"
                                        src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=880&q=80"
                                        alt="User">
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold">Alex Johnson</h4>
                                    <div class="text-yellow-400 flex">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                "Super cool app and team! Before I got people around the country listening, I got maybe
                                5 plays per track. Now I'm getting hundreds!"
                            </p>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="testimonial-card bg-gray-50 dark:bg-dark-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full object-cover"
                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80"
                                        alt="User">
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold">Sarah Miller</h4>
                                    <div class="text-yellow-400 flex">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                "They have connected me to the coolest artists. I got feedback that actually helped me
                                improve my sound. The community here is amazing!"
                            </p>
                        </div>

                        <!-- Testimonial 3 -->
                        <div class="testimonial-card bg-gray-50 dark:bg-dark-800 p-6 rounded-xl shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full object-cover"
                                        src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=880&q=80"
                                        alt="User">
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold">Michael Chen</h4>
                                    <div class="text-yellow-400 flex">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                "This platform has been instrumental in my growth as an artist. The genuine engagement
                                and feedback have helped me refine my music."
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Why Trust Us Section -->
            <section class="py-16 bg-gray-50 dark:bg-dark-800 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Why 400,000+ Artists Trust Repostchain</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Reason 1 -->
                        <div
                            class="bg-white dark:bg-dark-900 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="text-primary-600 mb-4">
                                <i class="fas fa-check-circle text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-4">No fake accounts, No bots, 100% organic</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Our platform verifies all users and ensures that all engagement is from real musicians
                                who are genuinely interested in your music.
                            </p>
                        </div>

                        <!-- Reason 2 -->
                        <div
                            class="bg-white dark:bg-dark-900 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="text-primary-600 mb-4">
                                <i class="fas fa-shield-alt text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-4">We don't over-promise or under-deliver</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                We're honest and transparent about what our platform can do for you. No false promises,
                                just real results from real engagement.
                            </p>
                        </div>

                        <!-- Reason 3 -->
                        <div
                            class="bg-white dark:bg-dark-900 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="text-primary-600 mb-4">
                                <i class="fas fa-dollar-sign text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Free to use - Powerful Utility</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Our core features are completely free. We believe in helping artists grow without
                                breaking the bank. Premium features available for serious growth.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recent Activity Section -->
            <section class="py-16 bg-white dark:bg-dark-900 transition-colors duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Happening in the past 24h on Repostchain</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Activity Items -->
                        <div
                            class="flex items-center p-4 bg-gray-50 dark:bg-dark-800 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-200">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80"
                                alt="User">
                            <div class="ml-3">
                                <p class="text-sm font-medium">DJ Maximus promoted "Summer Vibes"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center p-4 bg-gray-50 dark:bg-dark-800 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-200">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=764&q=80"
                                alt="User">
                            <div class="ml-3">
                                <p class="text-sm font-medium">Eliza Beats received 12 comments on "Night Drive"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">3 hours ago</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center p-4 bg-gray-50 dark:bg-dark-800 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-200">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80"
                                alt="User">
                            <div class="ml-3">
                                <p class="text-sm font-medium">Marcus Tone gained 45 new followers</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center p-4 bg-gray-50 dark:bg-dark-800 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-200">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=764&q=80"
                                alt="User">
                            <div class="ml-3">
                                <p class="text-sm font-medium">Luna Echo promoted "Midnight Dreams"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">6 hours ago</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center p-4 bg-gray-50 dark:bg-dark-800 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-200">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&ixid=  src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80"
                                alt="User">
                            <div class="ml-3">
                                <p class="text-sm font-medium">Jack Harmony received 8 comments on "Ocean Waves"</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">8 hours ago</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center p-4 bg-gray-50 dark:bg-dark-800 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors duration-200">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=761&q=80"
                                alt="User">
                            <div class="ml-3">
                                <p class="text-sm font-medium">Sophia Beats gained 32 new followers</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">10 hours ago</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-10">
                        <a href="#"
                            class="text-primary-600 font-medium hover:text-primary-700 transition-colors duration-200">
                            View all activity
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-16 bg-gray-50 dark:bg-dark-800 transition-colors duration-300">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Frequently Asked Questions</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            Everything you need to know about Repostchain
                        </p>
                    </div>

                    <div class="space-y-6">
                        <!-- FAQ Item 1 -->
                        <div class="bg-white dark:bg-dark-900 rounded-xl shadow-sm overflow-hidden">
                            <button
                                class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center"
                                onclick="toggleFAQ(this)">
                                <span class="font-semibold text-lg">How does Repostchain work?</span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 hidden">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Repostchain connects you with other musicians who are looking to grow their
                                    audience. You listen to and engage with their music, and they do the same for you.
                                    It's a community of mutual support and growth.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="bg-white dark:bg-dark-900 rounded-xl shadow-sm overflow-hidden">
                            <button
                                class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center"
                                onclick="toggleFAQ(this)">
                                <span class="font-semibold text-lg">Is Repostchain free to use?</span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 hidden">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Yes, Repostchain offers a free tier that gives you access to all the core features.
                                    We also offer premium plans for artists who want additional features and faster
                                    growth.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="bg-white dark:bg-dark-900 rounded-xl shadow-sm overflow-hidden">
                            <button
                                class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center"
                                onclick="toggleFAQ(this)">
                                <span class="font-semibold text-lg">Will this get my account flagged by
                                    SoundCloud?</span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 hidden">
                                <p class="text-gray-600 dark:text-gray-400">
                                    No, Repostchain only facilitates organic engagement between real users. We don't use
                                    bots or fake accounts, so all the engagement you receive is genuine and complies
                                    with SoundCloud's terms of service.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="bg-white dark:bg-dark-900 rounded-xl shadow-sm overflow-hidden">
                            <button
                                class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center"
                                onclick="toggleFAQ(this)">
                                <span class="font-semibold text-lg">How long does it take to see results?</span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 hidden">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Most users start seeing increased engagement within the first week. However, the
                                    more active you are on the platform, the faster you'll see results. Consistency is
                                    key to building a strong presence.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ Item 5 -->
                        <div class="bg-white dark:bg-dark-900 rounded-xl shadow-sm overflow-hidden">
                            <button
                                class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center"
                                onclick="toggleFAQ(this)">
                                <span class="font-semibold text-lg">Can I promote any genre of music?</span>
                                <svg class="w-5 h-5 transform transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 hidden">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Yes, Repostchain supports all genres of music. Our matching algorithm will connect
                                    you with artists in similar genres to ensure the most relevant engagement for your
                                    music.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-16 bg-gradient-to-r from-dark-900 to-dark-800 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Boost Your Music Career?</h2>
                    <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
                        Join 400,000+ artists who are growing their audience, getting real feedback, and building their
                        network with Repostchain.
                    </p>
                    <a href="#"
                        class="gradient-bg text-white px-8 py-4 rounded-lg font-semibold inline-block hover:opacity-90 transition-opacity duration-200 shadow-lg">
                        Start For Free
                    </a>
                    <p class="text-sm text-gray-400 mt-4">No credit card required. Cancel anytime.</p>
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-dark-900 text-gray-400 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                        <div class="col-span-2">
                            <div class="text-2xl font-bold text-white mb-4">Repostchain</div>
                            <p class="mb-4">
                                The platform for musicians to grow together. Promote your music, build your network, and
                                get real feedback.
                            </p>
                            <div class="flex space-x-4">
                                <a href="#"
                                    class="text-gray-400 hover:text-white transition-colors duration-200">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#"
                                    class="text-gray-400 hover:text-white transition-colors duration-200">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#"
                                    class="text-gray-400 hover:text-white transition-colors duration-200">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#"
                                    class="text-gray-400 hover:text-white transition-colors duration-200">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-white font-semibold mb-4">Product</h3>
                            <ul class="space-y-2">
                                <li><a href="#"
                                        class="hover:text-white transition-colors duration-200">Features</a></li>
                                <li><a href="#"
                                        class="hover:text-white transition-colors duration-200">Pricing</a></li>
                                <li><a href="#"
                                        class="hover:text-white transition-colors duration-200">Testimonials</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-200">FAQ</a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-white font-semibold mb-4">Company</h3>
                            <ul class="space-y-2">
                                <li><a href="#"
                                        class="hover:text-white transition-colors duration-200">About</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-200">Blog</a>
                                </li>
                                <li><a href="#"
                                        class="hover:text-white transition-colors duration-200">Careers</a></li>
                                <li><a href="#"
                                        class="hover:text-white transition-colors duration-200">Contact</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-white font-semibold mb-4">Legal</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="hover:text-white transition-colors duration-200">Privacy
                                        Policy</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-200">Terms of
                                        Service</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-200">Cookie
                                        Policy</a></li>
                            </ul>
                        </div>
                    </div>

                    <div
                        class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                        <p>&copy; 2025 Repostchain. All rights reserved.</p>
                        <div class="mt-4 md:mt-0">
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200 mr-4">Privacy</a>
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200">Terms</a>
                        </div>
                    </div>
                </div>
            </footer>
        </body>

    </main>

    @push('js')
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#fff1f1',
                                100: '#ffe1e1',
                                200: '#ffc7c7',
                                300: '#ffa0a0',
                                400: '#ff6b6b',
                                500: '#ff3e3e',
                                600: '#ff1f1f',
                                700: '#e60000',
                                800: '#bd0000',
                                900: '#9b0000',
                                950: '#560000',
                            },
                            dark: {
                                50: '#f8fafc',
                                100: '#f1f5f9',
                                200: '#e2e8f0',
                                300: '#cbd5e1',
                                400: '#94a3b8',
                                500: '#64748b',
                                600: '#475569',
                                700: '#334155',
                                800: '#1e293b',
                                900: '#0f172a',
                            }
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <script>
            // Theme toggle functionality
            function toggleTheme() {
                const html = document.documentElement;
                const currentTheme = localStorage.getItem('theme');

                if (currentTheme === 'dark') {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

            // Initialize theme
            function initTheme() {
                const savedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }

            // Initialize theme on page load
            initTheme();

            // Theme toggle event listeners
            document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
            document.getElementById('mobile-theme-toggle').addEventListener('click', toggleTheme);

            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
            });

            // FAQ toggle
            function toggleFAQ(element) {
                const content = element.nextElementSibling;
                const icon = element.querySelector('svg');

                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            }

            // Active navigation highlighting
            function updateActiveNavigation() {
                const sections = ['features', 'how-it-works', 'pricing', 'about', 'testimonials', 'faq'];
                const navLinks = document.querySelectorAll('.nav-link');

                // Remove active class from all links
                navLinks.forEach(link => link.classList.remove('active'));

                // Find current section
                let currentSection = '';
                const scrollPosition = window.scrollY + 100;

                sections.forEach(sectionId => {
                    const section = document.getElementById(sectionId);
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;

                        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                            currentSection = sectionId;
                        }
                    }
                });

                // Add active class to current section link
                if (currentSection) {
                    const activeLink = document.querySelector(`a[href="#${currentSection}"]`);
                    if (activeLink && activeLink.classList.contains('nav-link')) {
                        activeLink.classList.add('active');
                    }
                }
            }

            // Smooth scrolling for navigation links
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

            // Update active navigation on scroll
            window.addEventListener('scroll', updateActiveNavigation);

            // Initial call to set active navigation
            updateActiveNavigation();
        </script>
    @endpush

</x-frontend::layout>
