<x-frontend::layout>

    <x-slot name="title">
        {{ __('Admin Login') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Admin Login') }}
    </x-slot>

    <x-slot name="page_slug">
        admin-login
    </x-slot>
    <section
        class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center">

        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-40 dark:opacity-20">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
                <div class="shape shape-6"></div>
            </div>
        </div>
        <div
            class="flex login-card flex-col md:flex-row bg-white dark:bg-gray-800 border-gray-50 border dark:border-black shadow-xl rounded-2xl overflow-hidden w-[1550px] relative py-20">

            <a href="{{ url('/') }}"
                class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                <i data-lucide="arrow-left"></i>
                <span>Back To Home</span>
            </a>
            <!-- Left: Image -->
            <div class="hidden md:block md:w-7/12">
                <img src="{{ asset('/frontend/images/admin.jpg') }}" alt="Admin Login Image"
                    class="w-full h-full object-cover">
            </div>
            <!-- Right: Login Form -->
            <div class="w-full md:w-2/5 p-8 md:p-12">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ __('Admin Login') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Login to manage the dashboard') }}
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-sm text-green-600 dark:text-green-400 text-center"
                    :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('admin.login') }}" class="mt-6 space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Email') }}
                        </label>
                        <input type="text" name="email" placeholder="Enter your Email"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error class="mt-2" :messages="$errors->get('login')" />
                    </div>


                    <!-- Password -->
                    <div>
                        <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Password') }}
                        </label>
                        <div class="relative">
                            <input type="password" name="password" placeholder="Password"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 text-black focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                            <button type="button"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                                <i class="fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>


                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me"
                            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:focus:ring-offset-gray-800">
                            <span class="ml-2">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('admin.password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 underline"
                                href="{{ route('admin.password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <x-primary-button class="w-full py-6 justify-center">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>


        </div>
    </section>
    </x-frontend-layout>
