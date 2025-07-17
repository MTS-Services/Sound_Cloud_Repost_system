<x-frontend::layout>
    <x-slot name="title">
        {{ __('Reset Password') }}
    </x-slot>
    <x-slot name="breadcrumb">
        {{ __('Reset Password') }}
    </x-slot>
    <x-slot name="page_slug">
        admin-reset-password
    </x-slot>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top,rgba(255,85,0,0.1)_0%,#121212_50%)] relative overflow-hidden">

        <!-- Background Blobs -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-32 h-32 opacity-10 rounded-full blur-3xl animate-pulse bg-orange-500"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 opacity-10 rounded-full blur-3xl animate-pulse bg-orange-400" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/2 left-1/4 w-24 h-24 opacity-5 rounded-full blur-2xl animate-bounce bg-orange-500"></div>
        </div>

        <!-- Card -->
        <div class="relative max-w-xl w-full">
            <div class="bg-zinc-900 border border-zinc-800 p-8 rounded-2xl shadow-2xl space-y-8 relative overflow-hidden">
                <!-- Glow -->
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent rounded-2xl"></div>

                <!-- Card Content -->
                <div class="relative z-10">
                    <!-- Logo -->
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <div class="w-16 h-16 flex items-center justify-center rounded-2xl shadow-lg bg-gradient-to-br from-orange-500 to-orange-400">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H11V21H5V3H13V9H21ZM14 19V17H22V19H14ZM14 15V13H22V15H14ZM14 11V9H22V11H14Z"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 rounded-2xl border-2 border-orange-500/30 animate-ping"></div>
                            <div class="absolute inset-0 rounded-2xl border border-orange-500/20 animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Brand Name -->
                    <div class="text-center">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 to-orange-400 bg-clip-text text-transparent">RepostChain</h1>
                    </div>

                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-extrabold text-white mb-3">Reset Your Password</h2>
                        <p class="text-gray-400">
                            Please enter your email and new password below to reset your password.
                        </p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('admin.password.store') }}" class="space-y-6">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                            <x-text-input id="email" class="block mt-1 w-full rounded-xl bg-zinc-800 border border-zinc-700 text-white focus:border-orange-500 focus:ring focus:ring-orange-500/50" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-gray-300" />
                            <x-text-input id="password" class="block mt-1 w-full rounded-xl bg-zinc-800 border border-zinc-700 text-white focus:border-orange-500 focus:ring focus:ring-orange-500/50" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-300" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl bg-zinc-800 border border-zinc-700 text-white focus:border-orange-500 focus:ring focus:ring-orange-500/50" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center space-x-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-br from-orange-500 to-orange-400 hover:from-orange-400 hover:to-orange-500 transition-all shadow-md hover:-translate-y-1 hover:shadow-lg">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>

                    <!-- Help -->
                    <div class="text-center mt-8 pt-6 border-t border-zinc-800">
                        <p class="text-sm text-gray-400">
                            Having trouble?
                            <a href="#" class="text-orange-500 hover:text-orange-400 font-medium transition-colors">
                                Contact support
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Accent Divider -->
            <div class="absolute -bottom-2 left-4 right-4 h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent rounded-full opacity-60"></div>
        </div>
    </div>
</x-frontend::layout>
