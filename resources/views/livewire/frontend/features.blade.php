<div class="bg-black">
    <nav class="top-0 w-full bg-black/95 backdrop-blur-md border-b border-zinc-800/50 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-center">
                <a wire:navigate href="{{ route('f.landing') }}"
                    class="absolute left-6 flex items-center gap-2 text-zinc-400 hover:text-white transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="group-hover:stroke-white transition-colors">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    <span>Back</span>
                </a>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo/logo-1.png') }}" alt="Repostchain" class="w-10 h-10" />
                    <span class="text-2xl font-bold text-white">RepostChain</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 pt-32 pb-20 max-w-7xl">
        <div class="text-center mb-16">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-[#ff5500]/10 to-[#ff8833]/10 border border-[#ff5500]/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="#ff5500" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
                </svg>
                <span class="text-sm font-medium text-[#ff5500]">Platform Features</span>
            </div>

            <div>
                <h1 class="text-6xl font-bold text-white mb-4">RepostChain Features</h1>
                <p class="text-xl text-zinc-400 max-w-3xl mx-auto">
                    Everything you need to grow your music career through authentic artist collaboration and organic
                    promotion
                </p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300"
                    style="background-color: #ff5500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                        fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18V5l12-2v14" />
                        <path d="M9 18C7.5 18 6 16.5 6 15s1.5-3 3-3 3 1.5 3 3-1.5 3-3 3z" />
                        <path d="M21 17C19.5 17 18 15.5 18 14s1.5-3 3-3 3 1.5 3 3-1.5 3-3 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-[#ff5500] transition-colors">
                    Grow Organically with Real Artists
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    RepostChain provides a platform for independent artists to grow their music career through
                    authentic artist collaboration and organic promotion
                </p>
            </div>

            <!-- Repeat same structure for other feature cards -->
            <!-- ... all other blocks unchanged ... -->
        </div>

        <div
            class="rounded-3xl bg-gradient-to-br from-[#ff5500]/10 via-[#ff8833]/5 to-[#ff5500]/10 border border-[#ff5500]/20 p-12 mb-16">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                        fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 9.5l4.5 12h11L22 9.5z" />
                    </svg>
                </div>
                <h2 class="text-4xl font-bold text-white mb-4">Why Choose RepostChain?</h2>
                <p class="text-xl text-zinc-300 mb-8">
                    The trusted platform for independent artists who want real results
                </p>

                <div class="grid md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                    <!-- Repeated info items -->
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-black/30 border border-[#ff5500]/20">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <path d="M9 11l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="text-white font-medium text-left">Real, organic engagement only</span>
                    </div>

                    <!-- ... other 3 identical items ... -->
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <!-- Stats blocks -->
            <div
                class="rounded-2xl bg-gradient-to-br from-zinc-900/80 to-zinc-800/50 border border-zinc-800/50 p-8 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">5,000+</h3>
                <p class="text-zinc-400">Active Artists</p>
            </div>

            <!-- ... other 2 stat cards unchanged ... -->
        </div>

        <div class="rounded-3xl bg-gradient-to-r from-[#ff5500] to-[#ff8833] p-12 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Ready to grow your music career?</h2>
            <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
                Join thousands of artists who are already using RepostChain to expand their reach and build their
                fanbase organically
            </p>
            <button
                class="px-8 py-4 rounded-xl text-white font-semibold hover:bg-zinc-900 transition-all duration-300 hover:scale-105 hover:bg-black">
                Get Started Today
            </button>
        </div>

        <div class="mt-12 text-center text-sm text-zinc-500">
            <p>Powered by Tunexa Limited</p>
        </div>
    </div>
</div>
