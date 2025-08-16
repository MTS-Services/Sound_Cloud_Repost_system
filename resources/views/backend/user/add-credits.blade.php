<x-user::layout>

    <x-slot name="page_slug">add-credits</x-slot>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 h-full">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-black dark:text-white">Top up credits</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-white">Get your music in front of more people</p>
            {{-- <button class="mt-2 text-sm text-gray-600 dark:text-white "> <span
                    class="font-bold border border-gray-600 rounded px-2">+</span> Apply a coupon</button> --}}
        </div>

        <div class="grid grid-cols-2 gap-x-4 gap-y-6 justify-center md:grid-cols-3 lg:grid-cols-5 gap-6 items-stretch ">
            @foreach ($credits as $credit)
                <!-- 2500 Credits -->
                <div class="bg-white dark:bg-gray-600 rounded border overflow-hidden flex flex-col">
                    <div class="relative">
                        <div class="absolute   w-full text-white dark:text-white text-xs font-bold text-center   flex">
                            <div class="bg-black flex-grow h-6" style="flex-basis: 67%;"></div>
                            <div class="bg-red-600 flex-none h-6 flex items-center justify-end pr-2"
                                style="background-color: #fb3802; flex-basis: 42%;">+42%</div>
                        </div>
                    </div>
                    <div class="pt-10 pb-6 px-6 flex flex-col items-center text-center space-y-4">
                        <div class="bg-red-100 text-red-600 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                                    fill="none" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-black dark:text-white">{{ $credit->credits }}</h2>
                            <p class="text-gray-600 dark:text-white text-sm">Credits</p>
                            <p class="text-gray-400 dark:text-white text-xs mt-1">for the price of 1750</p>
                        </div>
                        <div class="text-2xl font-bold text-gray-800 dark:text-white">
                            ${{ number_format($credit->price, 2) }}</div>
                        <form action="{{ route('user.buy-credits') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="credits" value="{{ $credit->credits }}">
                            <input type="hidden" name="amount" value="{{ $credit->price }}">
                            <input type="hidden" name="credit_id" value="{{ encrypt($credit->id) }}">
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold text-sm py-2 px-4 rounded-md transition duration-200 whitespace-nowrap"
                                style="background-color: #fb3802;">
                                {{ __('Buy Now') }}
                            </button>
                        </form>
                    </div>
                </div>
                <!-- 5000 Credits -->
            @endforeach
        </div>
    </div>

</x-user::layout>
