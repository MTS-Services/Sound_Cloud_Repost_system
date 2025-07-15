<x-frontend::layout>
    <x-slot name="title">
        {{ __('Admin Reset Password') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Reset Password') }}
    </x-slot>

    <x-slot name="page_slug">
        admin-reset-password
    </x-slot>

    <section>
        <div
            class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center ">
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
                class="flex login-card flex-col md:flex-row bg-white dark:bg-gray-800 shadow-xl border-gray-50 border shadow-top rounded-2xl overflow-hidden w-[1550px] relative">
                <a href="{{ url('/') }}"
                    class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                    <i data-lucide="arrow-left"></i>
                    <span>Back To Home</span>
                </a>
                @auth('admin')
                    <x-admin.profile-navlink route="{{ route('admin.logout') }}" logout='true'
                        name="{{ __('Sign Out') }}" />
                @endauth

                <!-- Left Side: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        {{ __('Reset Your Password') }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-4">
                        {{ __('Please enter your email and new password to reset your account access.') }}
                    </p>

                    <form method="POST" action="{{ route('admin.password.store') }}" class="space-y-5">
                        @csrf

                        <!-- Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div>
                            <label class="input px-0">
                                <input type="email" name="email" placeholder="Enter Your Email"
                                    value="{{ old('email', request()->email) }}"
                                    class="w-full bg-transparent focus:outline-none " required autofocus
                                    autocomplete="username">
                            </label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="input px-0">

                                <input type="password" name="password" placeholder="New Password"
                                    class="w-full bg-transparent focus:outline-none " required
                                    autocomplete="new-password">
                            </label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="input px-0">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                    class="w-full bg-transparent focus:outline-none " required
                                    autocomplete="new-password">
                            </label>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="mt-5">
                            <x-primary-button class=" !w-full py-6">
                                {{ __('Reset Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Right Side: Image -->
                <div class="hidden md:block md:w-1/2">
                    <img src="{{ asset('/frontend/images/admin_password.jpg') }}" alt="Reset Password Image"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
</x-frontend::layout>
