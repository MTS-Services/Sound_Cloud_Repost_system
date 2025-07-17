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
                        <h2 class="text-3xl font-extrabold text-white mb-3">Verify Your Email</h2>
                        <p class="text-gray-400">
                            Thanks for signing up! Please verify your email address by clicking the link we sent you. Didn’t get it? We’ll send another.
                        </p>
                    </div>

                    <!-- Success Message -->
                    @if (session('success') == 'verification-link-sent')
                    <div class="p-4 rounded-xl border border-green-400/30 text-green-400 bg-green-400/10 animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">
                                A new verification link has been sent to your email.
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-8 justify-between">
                        <!-- Resend Email -->
                        <form method="POST" action="{{ route('admin.verification.send') }}" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center space-x-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-br from-orange-500 to-orange-400 hover:from-orange-400 hover:to-orange-500 transition-all shadow-md hover:-translate-y-1 hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Resend Verification Email</span>
                            </button>
                        </form>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center space-x-2 px-6 py-2 rounded-xl font-semibold border-2 border-zinc-800 text-gray-400 hover:border-orange-500 hover:text-orange-500 hover:bg-orange-500/10 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>

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
