<x-user::layout>
    <x-slot name="page_slug">dashboard</x-slot>
    <!-- Dashboard Content (Default) -->
    <div id="content-dashboard" class="page-content ">
        <div class="p-6 flex justify-between items-center">
            <div class="">
                <h2 class="text-2xl text-black font-semibold  mb-2">Dashboard</h2>
                <p class="text-gray-600">Welcome back, {{ auth()->user()->name ?? '' }}</p>
            </div>
            <div class="flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700">
                <span class=""><i data-lucide="plus"
                        class="w-4 h-4 inline-block border border-gray-300  text-center rounded-full h-6 w-6 p-1 tex-white"></i></span>
                <a href="#">{{ __('Submit Track') }}</a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 py-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-lg mr-4">
                        <i data-lucide="music" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <div>
                        <div class="text-gray-600 dark:text-gray-400">Track Submissions</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">2</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mt-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                    <span class="text-green-600">+ 2.5%{{ __('from last week') }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i data-lucide="refresh-cw" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <div class="text-gray-600 dark:text-gray-400">{{ __('Total Reposts') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">42</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mt-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                    <span class="text-green-600">+ 12%{{ __('from last month') }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <div class="text-gray-600 dark:text-gray-400">{{ __('Credibility Score') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">87%</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mt-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                    <span class="text-green-600">{{ __('High credibility') }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:-translate-y-2 transition-all duration-500 ease-in-out">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                        <i data-lucide="trending-up" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <div>
                        <div class="text-gray-600 dark:text-gray-400">{{ __('Available Credits') }}</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">85</div>
                    </div>
                </div>
                <div class="flex items-center space-x-1 mt-2">
                    <span class="text-orange-600">{{ __('Earn more credits') }}</span>
                    <i data-lucide="chevrons-right" class="w-4 h-4 text-orange-600 mt-1"></i>
                </div>
            </div>
        </div>
</x-user::layout>
