<header class="bg-white shadow-sm border-b px-6 py-4 sticky top-0 z-30">
    <div class="flex justify-between items-center">
        <div>
            {{-- <h1 id="page-title" class="text-2xl font-bold text-gray-900">My Profile</h1> --}}
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                <span class="font-semibold">75</span> Credits
            </div>
            <div class="relative">
                <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">1</span>
            </div>
            <div class="flex items-center space-x-2">
                <img src="{{ asset('frontend/user/user.jpg') }}" alt="Alex Rodriguez" class="w-8 h-8 rounded-full">
                <div class="text-sm">
                    <span class="font-semibold text-black">Alex Rodriguez</span>
                    <div class="text-green-500 text-xs">● Online</div>
                </div>
            </div>
        </div>
    </div>
</header>
