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

    <!-- Custom CSS Variables -->
    @push('cs')
        <style>
            .gradient-bg {
                background: radial-gradient(ellipse at top, rgba(255, 85, 0, 0.05) 0%, #121212 50%);
            }

            .input-glow:focus {
                box-shadow: 0 0 0 2px rgba(255, 85, 0, 0.5);
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }
            }

            .animate-pulse-slow {
                animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            .shadow-glow {
                box-shadow: 0 0 10px rgba(255, 85, 0, 0.4), 0 0 25px rgba(255, 85, 0, 0.2);
            }
        </style>
    @endpush

    <section
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


        {{-- Login Card --}}
        <div class="relative w-full max-w-xl">
            <div
                class="bg-[#1E1E1E] border border-[#dacdcd67] rounded-2xl shadow-xl backdrop-blur-md p-14 relative z-10 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#FF5500]/5 to-transparent rounded-2xl"></div>

                <div class="relative z-10 text-white">
                    {{-- Logo --}}
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-[#FF5500] to-[#FF7730] rounded-xl flex items-center justify-center shadow-glow">
                                <a href="{{ route('f.landing') }}" class="animate-float p-3 z-50">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v6.114A4.369 4.369 0 005 11c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="absolute inset-0 rounded-xl border-2 border-[#FF5500]/30 animate-ping"></div>
                            <div class="absolute inset-0 rounded-xl border border-[#FF5500]/20 animate-pulse"></div>
                        </div>
                    </div>

                    {{-- Heading --}}
                    <div class="text-center mb-6">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-[#FF5500] to-[#FF7730] bg-clip-text text-transparent">
                            Admin Panel
                        </h1>
                        <p class="text-sm text-gray-400">Secure login for administrators</p>
                    </div>

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300">Email Address</label>
                            <input type="email" name="email" id="email" required
                                class="mt-2 w-full px-4 py-3 rounded-lg bg-[#2A2A2A] border border-[#333] text-white placeholder-gray-500 focus:outline-none input-glow focus:border-[#FF5500] transition"
                                placeholder="admin@example.com" value="{{ old('email') }}">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                            <input type="password" name="password" id="password" required
                                class="mt-2 w-full px-4 py-3 rounded-lg bg-[#2A2A2A] border border-[#333] text-white placeholder-gray-500 focus:outline-none input-glow focus:border-[#FF5500] transition"
                                placeholder="••••••••">
                        </div>

                        {{-- Remember + Forgot --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center text-sm text-gray-300 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-[#FF5500] bg-[#1E1E1E] border-[#333] rounded focus:ring-[#FF5500] focus:ring-2">
                                <span class="ml-2">Remember me</span>
                            </label>
                            <a href="{{ route('admin.password.request') }}"
                                class="text-sm text-[#FF5500] hover:text-[#FF7730] font-medium">
                                Forgot password?
                            </a>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full py-3 px-6 font-semibold rounded-xl bg-gradient-to-r from-[#FF5500] to-[#FF7730] hover:from-[#FF7730] hover:to-[#FF5500] transition-all duration-300 transform hover:scale-[1.02] shadow-glow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF5500] focus:ring-offset-[#1E1E1E]">
                            <span class="flex items-center justify-center space-x-2">
                                <span>Login</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    </form>

                    {{-- Optional: Bottom Text --}}
                    <p class="text-center text-xs text-gray-500 mt-6">© {{ now()->year }} RepostChain Admin</p>
                </div>
            </div>

            {{-- Bottom Accent --}}
            <div
                class="absolute -bottom-2 left-4 right-4 h-1 bg-gradient-to-r from-transparent via-[#FF5500] to-transparent rounded-full opacity-60">
            </div>
        </div>
    </section>


    </x-frontend-layout>
