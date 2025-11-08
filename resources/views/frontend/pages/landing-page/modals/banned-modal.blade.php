<div x-show="showBannedModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

    <div
        class="w-full max-w-2xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">

        <!-- Header -->
        <div
            class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
            <div class="flex items-center gap-3">
                <div>
                    @if (app_setting('favicon') && app_setting('favicon_dark'))
                        <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                            class="w-10 dark:hidden" />
                        <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                            class="w-10 hidden dark:block" />
                    @else
                        <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                            class="w-10 dark:hidden" />
                        <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}" alt="{{ config('app.name') }}"
                            class="w-10 hidden dark:block" />
                    @endif
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Your account is Suspended') }}
                </h2>
            </div>
            <button x-on:click="showBannedModal = false"
                class="cursor-pointer w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto text-gray-700 dark:text-gray-300">
            <p class="mb-4">
                We’ve temporarily suspended your RepostChain account
                <strong>{{ session('name') }}</strong> for a potential violation of our
                <a href="{{ route('f.terms-and-conditions') }}"
                    class="text-orange-600 dark:text-orange-400 hover:underline">Community
                    Guidelines</a>.
            </p>

            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg mb-5">
                <p class="font-semibold text-red-700 dark:text-red-400">
                    Reason: {{ session('ban_reason') }}
                </p>
            </div>

            <p class="mb-6">
                If you believe this was a mistake, you can submit an appeal for review.
            </p>
        </div>

        <!-- Footer Buttons -->
        <div
            class="flex justify-end gap-3 p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <x-gabutton variant="secondary" wire:navigate
                href="{{ route('f.terms-and-conditions') }}">{{ __('Read Guidelines') }}</x-gabutton>
            <x-gabutton variant="primary" wire:navigate
                href="{{ route('f.contact-us') }}">{{ __('Request Review') }}</x-gabutton>
        </div>
    </div>
</div>
