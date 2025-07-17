<x-frontend::layout>
    <x-slot name="title">{{ __('Login') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Login') }}</x-slot>
    <x-slot name="page_slug">login</x-slot>

    <section
        class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top,rgba(255,85,0,0.08)_0%,#121212_60%)] relative overflow-hidden bg-fixed">

        <!-- Blobs -->
        <div class="absolute inset-0 pointer-events-none">
            <div
                class="absolute top-20 left-10 w-36 h-36 bg-orange-500 rounded-full blur-3xl opacity-10 animate-[float_5s_ease-in-out_infinite]">
            </div>
            <div
                class="absolute bottom-24 right-14 w-44 h-44 bg-orange-400 rounded-full blur-3xl opacity-10 animate-[float_5s_ease-in-out_infinite] [animation-delay:1.5s]">
            </div>
            <div
                class="absolute top-1/2 left-1/3 w-28 h-28 bg-orange-600 rounded-full blur-3xl opacity-10 animate-[float_5s_ease-in-out_infinite] [animation-delay:2.2s]">
            </div>
        </div>

        <!-- Card -->
        <div class="relative w-full max-w-xl group">
            <div
                class="bg-[#1B1B1B] border border-orange-500/20 rounded-2xl shadow-2xl backdrop-blur-xl p-8 sm:p-14 relative z-10 overflow-hidden transition-all duration-500 ease-in-out group-hover:shadow-3xl group-hover:scale-[1.01] group-hover:border-orange-500/40">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent rounded-2xl pointer-events-none">
                </div>

                <div class="relative z-10 text-white">

                    <!-- Logo -->
                    <div class="flex justify-center mb-8 transition-transform duration-300 group-hover:scale-105">
                        <div class="relative">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-orange-600 to-orange-400 rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(255,85,0,0.4),_0_0_40px_rgba(255,85,0,0.15)]">
                                <a href="{{ route('f.landing') }}" class="p-3 animate-[float_6s_ease-in-out_infinite] z-50">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v6.114A4.369 4.369 0 005 11c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="absolute inset-0 rounded-xl border-2 border-orange-500/30 animate-ping"></div>
                            <div class="absolute inset-0 rounded-xl border border-orange-500/20 animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Heading -->
                    <div class="text-center mb-8">
                        <h1
                            class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-orange-500 to-orange-400 bg-clip-text text-transparent">
                            Welcome Back</h1>
                        <p class="text-base sm:text-lg text-gray-400 mt-2">Login to your account</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg relative mb-6"
                                role="alert">
                                <strong class="font-bold">Whoops!</strong>
                                <span class="block sm:inline">There were some problems with your input.</span>
                                <ul class="mt-3 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Email -->
                        <div class="group">
                            <label for="email"
                                class="block text-sm font-medium text-gray-300 group-focus-within:text-orange-400 transition-colors duration-200">Email
                                Address</label>
                            <input type="email" name="email" id="email" required placeholder="you@example.com"
                                value="{{ old('email') }}" autocomplete="username"
                                class="mt-2 w-full px-4 py-3 rounded-lg bg-[#2A2A2A] border border-[#333] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-[0_0_0_2px_rgba(255,85,0,0.3)] focus:ring-offset-2 focus:ring-offset-[#1B1B1B] hover:border-orange-400">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="group">
                            <label for="password"
                                class="block text-sm font-medium text-gray-300 group-focus-within:text-orange-400 transition-colors duration-200">Password</label>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                autocomplete="current-password"
                                class="mt-2 w-full px-4 py-3 rounded-lg bg-[#2A2A2A] border border-[#333] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-[0_0_0_2px_rgba(255,85,0,0.3)] focus:ring-offset-2 focus:ring-offset-[#1B1B1B] hover:border-orange-400">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <label class="flex items-center text-sm text-gray-300 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-orange-500 bg-[#1E1E1E] border-[#a09191] rounded focus:ring-2 focus:ring-orange-500">
                                <span class="ml-2">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-orange-500 hover:text-orange-400 font-medium transition-colors duration-200">Forgot
                                    password?</a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full py-3 px-6 font-semibold rounded-xl bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-400 hover:to-orange-500 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-[0_0_20px_rgba(255,85,0,0.4),_0_0_40px_rgba(255,85,0,0.15)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 focus:ring-offset-[#1E1E1E]">
                            <span class="flex items-center justify-center space-x-2">
                                <span>{{ __('Log in') }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>

                        <!-- Divider -->
                        <div class="flex items-center my-6">
                            <hr class="flex-grow border-gray-600">
                            <span class="mx-4 text-gray-400">OR</span>
                            <hr class="flex-grow border-gray-600">
                        </div>

                        <!-- SoundCloud Login -->
                        <a href="{{ route('soundcloud.redirect') }}"
                            class="w-full py-3 px-6 font-semibold rounded-xl bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-500 hover:to-orange-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-[0_0_20px_rgba(255,102,0,0.4),_0_0_40px_rgba(255,102,0,0.15)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 focus:ring-offset-[#1E1E1E] flex items-center justify-center space-x-2 text-white">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M7 12.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm1.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm2.5-.5c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zm1.5.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm-12-2c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2 0c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5z" />
                            </svg>
                            <span>Continue with SoundCloud</span>
                        </a>

                    </form>

                    <p class="text-center text-xs text-gray-500 mt-8">© {{ now()->year }} RepostChain</p>
                </div>
            </div>

            <!-- Bottom accent -->
            <div
                class="absolute -bottom-2 left-4 right-4 h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent rounded-full opacity-60">
            </div>
        </div>
    </section>
</x-frontend::layout>