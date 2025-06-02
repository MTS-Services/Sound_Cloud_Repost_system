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

            .text-gray-primary {
                color: #D1D5DB !important;
            }

            .text-gray-secondary {
                color: #374151;
            }

            .primary-tartiary {
                color: #2563EB;
            }

            .dark-primary {
                background-color: #111827;
            }

            .dark-secondary {
                background-color: #1F2937;
            }

            .dark-tartiary {
                background: #374151;
            }
        </style>
    @endpush

    <main>

        <body class="bg-white dark:bg-dark-primary text-gray-800 dark:text-gray-100 transition-colors duration-300">
            <!-- Navigation -->
            @include('frontend.pages.sections.header')

            <!-- Hero Section -->
            @include('frontend.pages.sections.hero')

            <!-- About Us Section -->
            @include('frontend.pages.sections.about')

            <!-- Stats Section -->
            @include('frontend.pages.sections.stats')

            <!-- What is Section -->
           @include('frontend.pages.sections.what_is')
            <!-- Features Section -->
            @include('frontend.pages.sections.features')

            <!-- How It Works Section -->
            @include('frontend.pages.sections.how_it_work')

            <!-- Pricing Section -->
            @include('frontend.pages.sections.pricing')

            <!-- Testimonials Section -->
            @include('frontend.pages.sections.testimonials')
            <!-- FAQ Section -->
            @include('frontend.pages.sections.faq')

            <!-- Why Trust Us Section -->
            @include('frontend.pages.sections.why_trust_us')
            <!-- Recent Activity Section -->
            @include('frontend.pages.sections.recent_activity')
            <!-- CTA Section -->
            @include('frontend.pages.sections.cta')
            <!-- Footer -->
            @include('frontend.pages.sections.footer')
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
