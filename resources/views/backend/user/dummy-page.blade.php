<x-user::layout>
    <x-slot name="page_slug"></x-slot>
    <div class="text-center py-12 px-4 sm:px-6 lg:px-8 h-screen w-full items-center dark:bg-gray-800">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 mb-4">
            We're Almost There!
        </h1>
        <p class="text-lg text-gray-300 mb-8">
            Our page is under construction, but we’ll be ready to serve you soon! Stay tuned for something amazing.
        </p>
        <div class="flex justify-center items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6v6h6" />
            </svg>
            <span class="text-xl text-gray-400">Please check back shortly!</span>
        </div>
    </div>
</x-user::layout>
