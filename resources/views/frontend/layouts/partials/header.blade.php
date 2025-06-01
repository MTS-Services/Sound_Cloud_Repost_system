<header class="bg-bg-dark-primary sticky top-0 z-50">
    <div class="container">
        <nav class="navbar">
            <div class="navbar-start">
                <a href="{{ url('/') }}"
                    class="text-xl font-bold tracking-widest text-text-light-primary">RepostChain</a>
            </div>
            <div class="navbar-end">
                <div class="flex items-center justify-center gap-5">
                    <x-frontend.nav-link href="#home">{{ __('Home') }} </x-frontend.nav-link>
                    <x-frontend.nav-link href="#home">{{ __('About') }} </x-frontend.nav-link>
                    <x-frontend.nav-link href="#home">{{ __('Pricing') }} </x-frontend.nav-link>
                    <x-frontend.nav-link href="#home">{{ __('Contact') }} </x-frontend.nav-link>
                    <x-frontend.nav-link href="#home">{{ __('Login') }} </x-frontend.nav-link>
                    <x-frontend.primary-link>{{ __('Get Started for free') }} </x-frontend.primary-link>
                </div>
            </div>
        </nav>
    </div>
</header>
