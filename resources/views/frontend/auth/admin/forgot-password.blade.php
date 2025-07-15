<x-frontend::layout>
    <x-slot name="title">
        {{ __('Forgot Password') }}
    </x-slot>
    <x-slot name="breadcrumb">
        {{ __('Forgot Password') }}
    </x-slot>
    <x-slot name="page_slug">
        admin-forgot-password
    </x-slot>

    <div
        class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top,rgba(255,85,0,0.1)_0%,#121212_50%)] relative overflow-hidden">

        <!-- Background Blobs -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-32 h-32 opacity-10 rounded-full blur-3xl animate-pulse bg-orange-500">
            </div>
            <div class="absolute bottom-20 right-10 w-40 h-40 opacity-10 rounded-full blur-3xl animate-pulse bg-orange-400"
                style="animation-delay: 1.5s;"></div>
            <div
                class="absolute top-1/2 left-1/4 w-24 h-24 opacity-5 rounded-full blur-2xl animate-bounce bg-orange-500">
            </div>
        </div>

        <!-- Card -->
        <div class="relative max-w-xl w-full">
            <div
                class="bg-zinc-900 border border-zinc-800 p-8 rounded-2xl shadow-2xl space-y-8 relative overflow-hidden">

                <!-- Glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent rounded-2xl"></div>

                <!-- Card Content -->
                <div class="relative z-10">
                    <!-- Back Button - Moved to top right -->
                    <div class="absolute top-0 right-0 mt-4 mr-4">
                        <a href="{{ route('admin.login') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium btn-soft text-white border rounded-md border-zinc-700 hover:bg-orange-500 transition-all duration-300">
                            <svg class="w-5 h-5 pr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7 7-7M3 12h18"></path>
                            </svg>
                            Back to Login
                        </a>
                    </div>

                    <!-- Logo -->
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <div
                                class="w-16 h-16 flex items-center justify-center rounded-2xl shadow-lg bg-gradient-to-br from-orange-500 to-orange-400">
                                <a href="{{ route('f.landing') }}" class="animate-float p-3 z-50">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H11V21H5V3H13V9H21ZM14 19V17H22V19H14ZM14 15V13H22V15H14ZM14 11V9H22V11H14Z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="absolute inset-0 rounded-2xl border-2 border-orange-500/30 animate-ping"></div>
                            <div class="absolute inset-0 rounded-2xl border border-orange-500/20 animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Brand Name -->
                    <div class="text-center">
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-orange-500 to-orange-400 bg-clip-text text-transparent">
                            RepostChain
                        </h1>
                    </div>

                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-extrabold text-white mb-3">Forgot Your Password?</h2>
                        <p class="text-gray-400">
                            Enter your email below and we'll send you a password reset link.
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Form -->
                    <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label
                                class="input px-0 pl-2 flex items-center border border-zinc-700 rounded-md bg-zinc-800">
                                <svg class="h-5 w-5 opacity-50 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <path d="M22 7L14 13 10 10 2 7"></path>
                                </svg>
                                <input type="email" name="email" placeholder="Your Email"
                                    value="{{ old('email') }}" required autofocus
                                    class="w-full bg-transparent focus:outline-none px-3 py-2 rounded-r-md text-white" />
                            </label>
                            <x-input-error class="mt-2 text-red-400" :messages="$errors->get('email')" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center space-x-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-br from-orange-500 to-orange-400 hover:from-orange-400 hover:to-orange-500 transition-all shadow-md hover:-translate-y-1 hover:shadow-lg">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>

                    <!-- Help -->
                    <div class="text-center mt-8 pt-6 border-t border-zinc-800">
                        <p class="text-sm text-gray-400">
                            Having trouble?
                            <a href="#"
                                class="text-orange-500 hover:text-orange-400 font-medium transition-colors">
                                Contact support
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Accent Divider -->
            <div
                class="absolute -bottom-2 left-4 right-4 h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent rounded-full opacity-60">
            </div>
        </div>
    </div>
</x-frontend::layout>
