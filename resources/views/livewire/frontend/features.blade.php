@push('cs')
    <style>
        .platform-features .title {
            font-size: 48px;
            line-height: 56px;
        }
    </style>
@endpush
<div class="bg-black">
    {{-- <nav class="top-0 w-full bg-black/95 backdrop-blur-md border-b border-zinc-800/50 z-50">
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
    </nav> --}}

    <div class="container mx-auto px-6 pt-32 pb-20 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-16 platform-features">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-[#ff5500]/10 to-[#ff8833]/10 border border-[#ff5500]/20 mb-6">
                <svg class="w-4 h-4 text-[rgb(255,85,0)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="text-sm font-medium text-[rgb(255,85,0)]">Platform Features</span>
            </div>

            <h1 class="text-6xl title font-bold text-white mb-4">
                RepostChain Features
            </h1>
            <p class="text-xl text-zinc-400 max-w-3xl mx-auto">
                Everything you need to grow your music career through authentic artist collaboration and organic
                promotion
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            <!-- Feature 1 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Grow Organically with Real Artists
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    RepostChain connects independent musicians and producers to exchange reposts within a verified
                    artist network. Every repost comes from real, active users who genuinely support music promotion —
                    no bots, no fake engagement.
                </p>
            </div>

            <!-- Feature 2 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Direct Artist-to-Artist Reposts
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Send and receive reposts directly from other artists in your genre. Build lasting connections, grow
                    your fan base, and increase your visibility naturally through community-driven collaboration.
                </p>
            </div>

            <!-- Feature 3 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Smart Matching System
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Our intelligent matching system helps you find artists with similar genres, follower counts, and
                    engagement levels, ensuring your repost exchanges bring real results and relevant audiences.
                </p>
            </div>

            <!-- Feature 4 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Direct Requests
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Reach out directly to artists you admire or collaborate with using our Direct Request feature. Pitch
                    your track for reposts, collaborations, or feedback — all from within the platform.
                </p>
            </div>

            <!-- Feature 5 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Reputation & Trust System
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Earn trust and visibility through our Reputation Score. Artists with consistent engagement,
                    successful reposts, and positive feedback rank higher — helping you stand out in the community.
                </p>
            </div>

            <!-- Feature 6 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Track Your Growth
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Monitor every repost, view, and engagement metric in your personal dashboard. See exactly how your
                    promotion efforts are performing in real-time.
                </p>
            </div>

            <!-- Feature 7 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Fair Credit System
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Use Repost Credits to request reposts from other artists. Earn credits by reposting others or
                    purchase extra credits when you want faster results. Every credit transaction is transparent and
                    secure.
                </p>
            </div>

            <!-- Feature 8 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    100% Secure Platform
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    All exchanges are managed safely through RepostChain. We value artist privacy, protect your data,
                    and ensure fair play across the platform — powered by Tunexa Limited.
                </p>
            </div>

            <!-- Feature 9 -->
            <div
                class="group rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-6 hover:border-zinc-700/50 transition-all duration-300 hover:scale-105">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-orange-500 transition-colors">
                    Worldwide Artist Community
                </h3>
                <p class="text-zinc-400 leading-relaxed">
                    Join a fast-growing global network of artists, producers, and labels who collaborate to support each
                    other's music careers. Whether you're a beginner or a pro, RepostChain helps you reach your next
                    milestone.
                </p>
            </div>
        </div>

        <!-- Why Choose Section -->
        <div
            class="rounded-3xl bg-gradient-to-br from-orange-500/10 via-orange-400/5 to-orange-500/10 border border-orange-500/20 p-12 mb-16">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h2 class="text-4xl font-bold text-white mb-4">
                    Why Choose RepostChain?
                </h2>
                <p class="text-xl text-zinc-300 mb-8">
                    The trusted platform for independent artists who want real results
                </p>

                <div class="grid md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-black/30 border border-orange-500/20">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-white font-medium text-left">Real, organic engagement only</span>
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-xl bg-black/30 border border-orange-500/20">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-white font-medium text-left">No bots or automation</span>
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-xl bg-black/30 border border-orange-500/20">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-white font-medium text-left">Transparent and fair system</span>
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-xl bg-black/30 border border-orange-500/20">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-white font-medium text-left">Designed for independent artists</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <div
                class="rounded-2xl bg-gradient-to-br from-zinc-900/80 to-zinc-800/50 border border-zinc-800/50 p-8 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">5,000+</h3>
                <p class="text-zinc-400">Active Artists</p>
            </div>

            <div
                class="rounded-2xl bg-gradient-to-br from-zinc-900/80 to-zinc-800/50 border border-zinc-800/50 p-8 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">10,000+</h3>
                <p class="text-zinc-400">Reposts Exchanged</p>
            </div>

            <div
                class="rounded-2xl bg-gradient-to-br from-zinc-900/80 to-zinc-800/50 border border-zinc-800/50 p-8 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-br from-[#ff5500] to-[#ff8833] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">150+</h3>
                <p class="text-zinc-400">Countries Worldwide</p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="rounded-3xl bg-gradient-to-r from-[#ff5500] to-[#ff8833] p-12 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">
                Ready to grow your music career?
            </h2>
            <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
                Join thousands of artists who are already using RepostChain to expand their reach and build their
                fanbase organically
            </p>
            <a href="https://invite.soundcloud.com/pjoy4"
                class="px-8 py-4 rounded-xl bg-black text-white font-semibold hover:bg-zinc-900 transition-all duration-300 hover:scale-105 inline-block">
                Get Started Today
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-sm text-zinc-500">
            <p>Powered by Tunexa Limited</p>
        </div>
    </div>
</div>
