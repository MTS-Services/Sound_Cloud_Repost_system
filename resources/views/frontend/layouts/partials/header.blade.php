<header>
    <div class="container">
        <nav class="navbar">
            <div class="navbar-start">
                <a href="{{ url('/') }}">{{ __('Logo') }}</a>
            </div>
            <div class="navbar-end">
                <div class="flex items-center gap-3">
                    <x-nav-link href="{{ url('/') }}" :active="$slug === 'home'">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link href="" :active="$slug === 'about'">
                        {{ __('About') }}
                    </x-nav-link>
                </div>
            </div>
        </nav>
    </div>
</header>
