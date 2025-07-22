<nav class="bg-white dark:bg-dark-secondary shadow-sm sticky top-0 z-50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-bold text-primary-tartiary">Repostchain</span>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#about"
                    class="nav-link text-gray-secondary dark:text-gray-primary hover:text-primary-tartiary transition-colors duration-200 font-medium">About</a>
                <a href="#features"
                    class="nav-link text-gray-secondary dark:text-gray-primary hover:text-primary-tartiary transition-colors duration-200 font-medium">Features</a>
                <a href="#how-it-works"
                    class="nav-link text-gray-secondary dark:text-gray-primary hover:text-primary-tartiary transition-colors duration-200 font-medium">How
                    It Works</a>
                <a href="#pricing"
                    class="nav-link text-gray-secondary dark:text-gray-primary hover:text-primary-tartiary transition-colors duration-200 font-medium">Pricing</a>
                <a href="#testimonials"
                    class="nav-link text-gray-secondary dark:text-gray-primary hover:text-primary-tartiary transition-colors duration-200 font-medium">Testimonials</a>
                <a href="#faq"
                    class="nav-link text-gray-secondary dark:text-gray-primary hover:text-primary-tartiary transition-colors duration-200 font-medium">FAQ</a>

                <!-- Theme Toggle -->
                <button id="theme-toggle"
                    class="theme-toggle p-2 rounded-lg bg-gray-100 dark:bg-dark-tartiary text-gray-600 dark:text-gray-primary hover:bg-gray-200 dark:hover:bg-dark-600 transition-colors duration-200">
                    <i class="fas fa-sun dark:hidden"></i>
                    <i class="fas fa-moon hidden dark:block"></i>
                </button>

                <a href="{{ route('soundcloud.redirect') }}"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M7 12.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm1.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm2.5-.5c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zm1.5.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm-12-2c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2 0c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5z" />
                    </svg>
                    Continue with SoundCloud
                </a>
            </div>
            <div class="flex md:hidden items-center space-x-2">
                <!-- Mobile Theme Toggle -->
                <button id="mobile-theme-toggle"
                    class="theme-toggle p-2 rounded-lg bg-gray-100 dark:bg-dark-tartiary text-gray-600 dark:text-gray-primary">
                    <i class="fas fa-sun dark:hidden"></i>
                    <i class="fas fa-moon hidden dark:block"></i>
                </button>
                <button type="button" class="text-gray-secondary dark:text-gray-primary" id="mobile-menu-button">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="hidden md:hidden bg-white dark:bg-dark-secondary shadow-md transition-colors duration-300"
        id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#about"
                class="nav-link block px-3 py-2 text-gray-secondary dark:text-gray-primary hover:bg-primary-50 dark:hover:bg-dark-tartiary hover:text-primary-tartiary rounded-md">About</a>
            <a href="#features"
                class="nav-link block px-3 py-2 text-gray-secondary dark:text-gray-primary hover:bg-primary-50 dark:hover:bg-dark-tartiary hover:text-primary-tartiary rounded-md">Features</a>
            <a href="#how-it-works"
                class="nav-link block px-3 py-2 text-gray-secondary dark:text-gray-primary hover:bg-primary-50 dark:hover:bg-dark-tartiary hover:text-primary-tartiary rounded-md">How
                It Works</a>
            <a href="#pricing"
                class="nav-link block px-3 py-2 text-gray-secondary dark:text-gray-primary hover:bg-primary-50 dark:hover:bg-dark-tartiary hover:text-primary-tartiary rounded-md">Pricing</a>
            <a href="#testimonials"
                class="nav-link block px-3 py-2 text-gray-secondary dark:text-gray-primary hover:bg-primary-50 dark:hover:bg-dark-tartiary hover:text-primary-tartiary rounded-md">Testimonials</a>
            <a href="#faq"
                class="nav-link block px-3 py-2 text-gray-secondary dark:text-gray-primary hover:bg-primary-50 dark:hover:bg-dark-tartiary hover:text-primary-tartiary rounded-md">FAQ</a>
            <a href="{{ route('soundcloud.redirect') }}"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M7 12.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm1.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm2.5-.5c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zm1.5.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm-12-2c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2 0c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5z" />
                </svg>
                Continue with SoundCloud
            </a>
        </div>
    </div>
</nav>
