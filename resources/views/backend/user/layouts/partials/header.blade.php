<header class="bg-white shadow-lg px-6 py-4 sticky top-0 z-30">
    <div class="flex justify-between items-center">
        <div>
            {{-- <h1 id="page-title" class="text-2xl font-bold text-gray-900">My Profile</h1> --}}
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                <span class="font-semibold">75</span> {{ __('Credits') }}
            </div>
            <div class="relative">
                <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">1</span>
            </div>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="flex items-center space-x-2 avatar">
                    <div class="w-10 h-10 rounded-full">
                        <img alt="Tailwind CSS Navbar component" src="{{ asset('frontend/user/user.jpg') }}" />
                    </div>
                    <div class="text-sm">
                        <span class="font-semibold text-black">{{ auth()->user()->name }}</span>
                        <div class="text-green-500 text-xs">● Online</div>
                    </div>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-gray-50 rounded-box z-1 mt-3 w-52 shadow py-2">
                    <li>
                        <div class="flex items-center space-x-2 border-b border-gray-100 px-6 py-4 w-full">
                            <img src="{{ asset('frontend/user/user.jpg') }}" alt="Alex Rodriguez"
                                class="w-8 h-8 rounded-full">
                            <div class="text-sm">
                                <span class="font-semibold text-black">{{ auth()->user()->name }}</span>
                                <div class="text-green-500 text-xs">● Online</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <!-- Authentication -->
                        <x-responsive-nav-link :href="javascript:void(0)"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
