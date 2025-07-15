<x-frontend::layout>
    <x-slot name="title">
        {{ __('Verify Email Address') }}
    </x-slot>
    <x-slot name="breadcrumb">
        {{ __('Verify Email Address') }}
    </x-slot>
    <x-slot name="page_slug">
        admin-verify-email
    </x-slot>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-xl w-full bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 space-y-8 transform transition-all duration-300 hover:scale-[1.01]">
            <div class="text-justify">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    {{ __('Verify Your Email') }}
                </h2>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            @if (session('success') == 'verification-link-sent')
                <div class="p-4 rounded-md bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-300 font-medium text-sm border border-green-200 dark:border-green-700 animate-fade-in">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
                <form method="POST" action="{{ route('admin.verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <x-primary-button class="w-full justify-center">
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('admin.logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full text-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-frontend::layout>