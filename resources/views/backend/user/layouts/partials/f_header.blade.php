<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<header x-data="{ open: false, darkMode: localStorage.getItem('theme') === 'dark' }"
        x-init="$watch('darkMode', val => { 
            document.documentElement.classList.toggle('dark', val); 
            localStorage.setItem('theme', val ? 'dark' : 'light'); 
        })"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent backdrop-blur-sm">

    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('f.landing') }}" wire:navigate>
            <div class="w-72">
                <img src="{{ app_setting('app_logo_dark') ? asset('storage/' . app_setting('app_logo_dark')) : asset('assets/logo/rc-logo-white.png') }}"
                     alt="{{ config('app.name') }}" class="w-36 lg:w-48 object-contain">
            </div>
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="{{ route('f.landing') }}#home" class="text-gray-300 hover:text-white transition-colors">Home</a>
            <a href="{{ route('f.plan') }}" wire:navigate class="text-gray-300 hover:text-white transition-colors">Plan</a>
            <a href="{{ route('f.landing') }}#about" class="text-gray-300 hover:text-white transition-colors">About</a>
            <a href="{{ route('f.landing') }}#how-it-works" class="text-gray-300 hover:text-white transition-colors">How it Works</a>
            <a href="{{ route('f.landing') }}#features" class="text-gray-300 hover:text-white transition-colors">Features</a>
            <a href="{{ route('f.landing') }}#testimonials" class="text-gray-300 hover:text-white transition-colors">Testimonials</a>


            @if (Auth::check())
                <x-gabutton variant="primary" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</x-gabutton>
            @else
                <x-gabutton variant="primary" href="{{ route('soundcloud.redirect') }}">
                    Continue with SoundCloud
                </x-gabutton>
            @endif
        </nav>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center space-x-3">
            {{-- <!-- Dark Mode Toggle (Mobile) -->
            <button @click="darkMode = !darkMode" class="text-gray-300 hover:text-white">
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                     <circle cx="12" cy="12" r="4"></circle>
                     <path d="M12 2v2"></path>
                     <path d="M12 20v2"></path>
                     <path d="m4.93 4.93 1.41 1.41"></path>
                     <path d="m17.66 17.66 1.41 1.41"></path>
                     <path d="M2 12h2"></path>
                     <path d="M20 12h2"></path>
                     <path d="m6.34 17.66-1.41 1.41"></path>
                     <path d="m19.07 4.93-1.41 1.41"></path>
                 </svg>
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                     viewBox="0 0 24 24">
                    <path d="M21.64 13.65a1 1 0 0 0-1.05-.14 8 8 0 0 1-10.1-10.1 1 1 0 0 0-1.19-1.28 10 10 0 1 0 12.61 12.61 1 1 0 0 0-.27-1.19Z"/>
                </svg>
            </button> --}}

            <!-- Toggle Button -->
            <button @click="open = !open" class="text-gray-200 hover:text-white">
                <!-- Hamburger -->
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12"></line>
                    <line x1="4" x2="20" y1="6" y2="6"></line>
                    <line x1="4" x2="20" y1="18" y2="18"></line>
                </svg>
                <!-- Close -->
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden bg-dark-card border-t border-dark-border">
        <div class="container mx-auto px-4 py-4 space-y-4">
            <a href="{{ route('f.landing') }}#home" class="block text-gray-300 hover:text-white transition-colors">Home</a>
            <a href="{{ route('f.plan') }}" wire:navigate class="block text-gray-300 hover:text-white transition-colors">Plan</a>
            <a href="{{ route('f.landing') }}#about" class="block text-gray-300 hover:text-white transition-colors">About</a>
            <a href="{{ route('f.landing') }}#how-it-works" class="block text-gray-300 hover:text-white transition-colors">How it Works</a>
            <a href="{{ route('f.landing') }}#features" class="block text-gray-300 hover:text-white transition-colors">Features</a>
            <a href="{{ route('f.landing') }}#testimonials" class="block text-gray-300 hover:text-white transition-colors">Testimonials</a>
            
            @if (Auth::check())
                <a href="{{ route('user.dashboard') }}"
                   class="block text-center w-full px-3 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                   Dashboard
                </a>
            @else
                <a href="{{ route('soundcloud.redirect') }}"
                   class="block text-center w-full px-3 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-700">
                   Continue with SoundCloud
                </a>
            @endif
        </div>
    </div>
</header>
