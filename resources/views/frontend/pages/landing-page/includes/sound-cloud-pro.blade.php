@push('cs')
    <style>
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            animation: scroll 30s linear infinite;
        }

        .animate-scroll:hover {
            animation-play-state: paused;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }

        @keyframes flip {

            0%,
            45% {
                transform: rotateY(0deg);
            }

            50%,
            95% {
                transform: rotateY(180deg);
            }

            100% {
                transform: rotateY(0deg);
            }
        }

        .animate-flip {
            animation: flip 8s infinite;
            transform-style: preserve-3d;
        }

        .backface-hidden {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        @media (max-width: 575px) {
            #plan {
                padding-top: 0px;
            }

            #plan .soundCloud-pro {
                padding: 5px 8px;
                font-size: 18px;
            }
        }
    </style>
@endpush
<section id="plan" class="py-24 px-8 relative overflow-hidden bg-gradient-to-b from-black via-[#ff5500]/5 to-black">
    <div class="max-w-[1400px] mx-auto relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-left">
                <div
                    class="inline-flex items-center gap-2 bg-[#ff5500]/10 border border-[#ff5500]/30 px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4 text-[#ff5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                    <span class="text-[#ff5500] text-sm font-semibold">Partner Spotlight</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Supercharge Your Music
                    with<br><span class="text-[#ff5500]">SoundCloud Pro</span></h2>
                <p class="text-lg text-zinc-300 mb-8 leading-relaxed">SoundCloud Pro gives you the tools to take
                    your music career to the next level. Get advanced stats, unlimited uploads, and more control
                    over your content.</p>
                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div
                            class="bg-[#ff5500]/10 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-[#ff5500]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-1">Advanced Analytics</h4>
                            <p class="text-zinc-400 text-sm">Track your audience with detailed insights and
                                performance metrics</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="bg-[#ff5500]/10 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-[#ff5500]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-1">Unlimited Uploads</h4>
                            <p class="text-zinc-400 text-sm">Upload as much music as you want with no storage limits
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="bg-[#ff5500]/10 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-[#ff5500]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-1">Spotlight Features</h4>
                            <p class="text-zinc-400 text-sm">Pin your best tracks and get featured placement on your
                                profile</p>
                        </div>
                    </div>
                </div>
                <a href="https://checkout.soundcloud.com/pro" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ff5500] to-orange-600 hover:from-[#ff6a1a] hover:to-orange-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-[#ff5500]/30 soundCloud-pro">
                    Upgrade to SoundCloud Pro
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <p class="text-sm text-zinc-500 mt-4">Special offer for RepostChain users • Start your free trial
                    today</p>
            </div>
            <div class="relative hidden lg:block cursor-pointer">
                <div class="w-full max-w-lg mx-auto h-[420px]" style="perspective: 1000px;">
                    <div class="relative w-full h-full animate-flip">
                        <div class="absolute inset-0 backface-hidden">
                            <div
                                class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-6 rounded-3xl border border-[#ff5500]/30 shadow-2xl shadow-[#ff5500]/20 h-full relative">
                                <div
                                    class="absolute top-3 right-3 bg-[#ff5500] text-white px-3 py-1 rounded-full text-xs font-bold">
                                    MOST POPULAR</div>
                                <div class="bg-[#ff5500]/5 rounded-2xl p-5 mb-3 border border-[#ff5500]/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="text-2xl font-bold text-white mb-1">Artist Pro</h3>
                                            <p class="text-zinc-400 text-xs">Unlimited access to all tools</p>
                                        </div>
                                        <div class="bg-[#ff5500] text-white px-3 py-1.5 rounded-lg font-bold text-xl">
                                            $8.25<span class="text-xs font-normal">/mo</span></div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-[#ff5500]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span>Unlimited uploads</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-[#ff5500]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <span>Boost tracks unlimited</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-[#ff5500]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span>Distribute & monetize tracks</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-[#ff5500]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span>Replace tracks anytime</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mb-2">
                                    <a href="https://checkout.soundcloud.com/pro" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-block bg-gradient-to-r from-[#ff5500] to-orange-600 hover:from-[#ff6a1a] hover:to-orange-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all hover:scale-105 w-full text-center">Get
                                        Started</a>
                                </div>
                                <div class="text-center">
                                    <div class="inline-flex items-center gap-2 text-[#ff5500] text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Click to see other plan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-0 backface-hidden" style="transform: rotateY(180deg);">
                            <div
                                class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-6 rounded-3xl border border-blue-500/30 shadow-2xl shadow-blue-500/20 h-full">
                                <div class="bg-blue-500/5 rounded-2xl p-5 mb-3 border border-blue-500/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="text-2xl font-bold text-white mb-1">Artist</h3>
                                            <p class="text-zinc-400 text-xs">Essential artist tools</p>
                                        </div>
                                        <div class="bg-blue-500 text-white px-3 py-1.5 rounded-lg font-bold text-xl">
                                            $3.25<span class="text-xs font-normal">/mo</span></div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span>3 hours of uploads</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <span>Boost tracks (2x per month)</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span>Distribute & monetize tracks</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-300 text-xs">
                                            <div
                                                class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span>Replace tracks (3x per month)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mb-2">
                                    <a href="https://checkout.soundcloud.com/artist" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all hover:scale-105 w-full text-center">Get
                                        Started</a>
                                </div>
                                <div class="text-center">
                                    <div class="inline-flex items-center gap-2 text-blue-500 text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Click to see other plan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-6 -right-6 w-64 h-64 bg-[#ff5500]/10 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <div
                        class="absolute -top-6 -left-6 w-48 h-48 bg-orange-600/10 rounded-full blur-2xl pointer-events-none">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
