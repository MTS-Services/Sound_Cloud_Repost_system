<x-frontend::layout>
    <x-slot name="title">{{ __('Admin Login') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin Login') }}</x-slot>
    <x-slot name="page_slug">admin-login</x-slot>

    <section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top,rgba(255,85,0,0.08)_0%,#121212_60%)] relative overflow-hidden">

        <!-- Blobs -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-36 h-36 bg-orange-500 rounded-full blur-3xl opacity-10 animate-[float_5s_ease-in-out_infinite]"></div>
            <div class="absolute bottom-24 right-14 w-44 h-44 bg-orange-400 rounded-full blur-3xl opacity-10 animate-[float_5s_ease-in-out_infinite] [animation-delay:1.5s]"></div>
            <div class="absolute top-1/2 left-1/3 w-28 h-28 bg-orange-600 rounded-full blur-3xl opacity-10 animate-[float_5s_ease-in-out_infinite] [animation-delay:2.2s]"></div>
        </div>

        <!-- Card -->
        <div class="relative w-full max-w-xl">
            <div class="bg-[#1B1B1B] border border-orange-500/10 rounded-2xl shadow-xl backdrop-blur-xl p-14 relative z-10 overflow-hidden transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent rounded-2xl pointer-events-none"></div>

                <div class="relative z-10 text-white">

                    <!-- Logo -->
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-600 to-orange-400 rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(255,85,0,0.4),_0_0_40px_rgba(255,85,0,0.15)]">
                                <a href="{{ route('f.landing') }}" class="p-3 animate-[float_6s_ease-in-out_infinite]">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v6.114A4.369 4.369 0 005 11c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="absolute inset-0 rounded-xl border-2 border-orange-500/30 animate-ping"></div>
                            <div class="absolute inset-0 rounded-xl border border-orange-500/20 animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Heading -->
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-orange-500 to-orange-400 bg-clip-text text-transparent">Admin Panel</h1>
                        <p class="text-sm text-gray-400">Secure login for administrators</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300">Email Address</label>
                            <input type="email" name="email" id="email" required placeholder="admin@example.com"
                                value="{{ old('email') }}"
                                class="mt-2 w-full px-4 py-3 rounded-lg bg-[#2A2A2A] border border-[#333] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-[0_0_0_2px_rgba(255,85,0,0.3)]">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                class="mt-2 w-full px-4 py-3 rounded-lg bg-[#2A2A2A] border border-[#333] text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-[0_0_0_2px_rgba(255,85,0,0.3)]">
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center text-sm text-gray-300 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-orange-500 bg-[#1E1E1E] border-[#333] rounded focus:ring-2 focus:ring-orange-500">
                                <span class="ml-2">Remember me</span>
                            </label>
                            <a href="{{ route('admin.password.request') }}"
                                class="text-sm text-orange-500 hover:text-orange-400 font-medium transition">Forgot password?</a>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full py-3 px-6 font-semibold rounded-xl bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-400 hover:to-orange-500 transition-transform duration-300 transform hover:scale-[1.03] shadow-[0_0_20px_rgba(255,85,0,0.4),_0_0_40px_rgba(255,85,0,0.15)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 focus:ring-offset-[#1E1E1E]">
                            <span class="flex items-center justify-center space-x-2">
                                <span>Login</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </button>
                    </form>

                    <p class="text-center text-xs text-gray-500 mt-6">© {{ now()->year }} RepostChain Admin</p>
                </div>
            </div>

            <!-- Bottom accent -->
            <div class="absolute -bottom-2 left-4 right-4 h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent rounded-full opacity-60"></div>
        </div>
    </section>
</x-frontend::layout>
