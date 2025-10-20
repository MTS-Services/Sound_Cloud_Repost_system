<div>
    @push('cs')
        <link rel="stylesheet" href="{{ asset('assets/landing/css/landing.css') }}">
    @endpush
    {{-- <slot name="title">Landing Page</slot> --}}

    {{-- <!-- Hero Section -->
    @include('frontend.pages.landing-page.includes.hero')

    <!-- About Section -->
    @include('frontend.pages.landing-page.includes.about')
    <!-- How It Works Section -->
    @include('frontend.pages.landing-page.includes.how-it-works')
    <!-- Features Section -->
    @include('frontend.pages.landing-page.includes.features')

    <!-- Statistics Section -->
    @include('frontend.pages.landing-page.includes.statistics')

    <!-- Testimonials Section -->
    @include('frontend.pages.landing-page.includes.testimonials') --}}

    <!-- FAQ Section -->
    {{-- @include('frontend.pages.landing-page.includes.faq') --}}
    <!-- CTA Section -->
    {{-- @include('frontend.pages.landing-page.includes.cta') --}}




    <div class="min-h-screen bg-black">
        <section class="pt-32 pb-20 px-8">
            <div class="max-w-[1400px] mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="text-left animate-fade-in">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                            Grow Your Music Reach</h1>
                        <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-8 leading-tight"><span
                                class="text-[#ff5500]">Build Fans, Share Feedback</span></h2>
                        <p class="text-lg text-zinc-300 mb-4 max-w-xl">Join More than thousands of music
                            creators and grow audience Through our collaborative platform.</p>
                        <p class="text-lg font-semibold text-white mb-8">100% Free to use</p>
                        <div class="flex flex-col sm:flex-row gap-4"><button
                                class="bg-[#ff5500] hover:bg-[#ff6a1a] text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all hover:scale-105 flex items-center gap-2 justify-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-music2 w-5 h-5">
                                    <circle cx="8" cy="18" r="4"></circle>
                                    <path d="M12 18V2l7 4"></path>
                                </svg>Connect SoundCloud</button><a href="#how-repostchain-works"
                                class="bg-transparent hover:bg-zinc-900 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all border border-zinc-700 flex items-center gap-2 justify-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-play w-5 h-5">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>See How It Works</a></div>
                    </div>
                    <div class="relative lg:flex hidden justify-end">
                        <div class="relative w-full max-w-2xl"><img src="/Untitled design (19).png"
                                alt="Featured Artist" class="w-full h-auto relative z-10">
                            <div
                                class="absolute -bottom-4 -right-4 w-72 h-72 bg-[#ff5500]/20 rounded-full blur-3xl -z-10">
                            </div>
                            <div
                                class="absolute -top-4 -right-4 w-48 h-48 bg-orange-600/20 rounded-full blur-2xl -z-10">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="what-is-repostchain" class="py-24 px-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#ff5500]/5 to-transparent">
            </div>
            <div class="max-w-[1400px] mx-auto relative">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">What is RepostChain?</h2>
                    <p class="text-lg md:text-xl text-zinc-300 max-w-4xl mx-auto leading-relaxed">RepostChain is
                        a revolutionary platform that connects musicians and content creators, enabling organic
                        growth through collaborative promotion and authentic engagement.</p>
                </div>
                <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/50 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.02]">
                        <div
                            class="bg-[#ff5500]/10 w-14 h-14 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-music2 w-7 h-7 text-[#ff5500]">
                                <circle cx="8" cy="18" r="4"></circle>
                                <path d="M12 18V2l7 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Collaborative Growth</h3>
                        <p class="text-zinc-400 leading-relaxed">Join a community of artists who support each
                            other through strategic reposts, creating a network effect that amplifies everyone's
                            reach.</p>
                    </div>
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/50 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.02]">
                        <div
                            class="bg-[#ff5500]/10 w-14 h-14 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-users w-7 h-7 text-[#ff5500]">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Real Engagement</h3>
                        <p class="text-zinc-400 leading-relaxed">Our platform ensures all interactions are
                            genuine, helping you build a real fanbase that truly appreciates your music.</p>
                    </div>
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/50 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.02]">
                        <div
                            class="bg-[#ff5500]/10 w-14 h-14 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-shield w-7 h-7 text-[#ff5500]">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Fair System</h3>
                        <p class="text-zinc-400 leading-relaxed">Our system ensures fair Collabration between
                            artists, preventing spam and promoting quality content.</p>
                    </div>
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/50 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.02]">
                        <div
                            class="bg-[#ff5500]/10 w-14 h-14 rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trending-up w-7 h-7 text-[#ff5500]">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 7 22 7 22 13"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Analytics &amp; Growth</h3>
                        <p class="text-zinc-400 leading-relaxed">Track your growth with detailed analytics and
                            insights, helping you optimize your promotion strategy.</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="how-repostchain-works" class="py-24 px-8 relative overflow-hidden">
            <div class="max-w-[1400px] mx-auto relative">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">How RepostChain Works</h2>
                    <p class="text-lg md:text-xl text-zinc-300 max-w-4xl mx-auto leading-relaxed">Our platform
                        makes it easy to grow your audience through a fair exchange system that benefits
                        everyone involved.</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.05] relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#ff5500] to-orange-600">
                        </div>
                        <div class="flex items-center gap-4 mb-6"><span
                                class="text-4xl font-bold text-[#ff5500]">1</span></div>
                        <div
                            class="bg-[#ff5500]/10 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-music2 w-8 h-8 text-[#ff5500]">
                                <circle cx="8" cy="18" r="4"></circle>
                                <path d="M12 18V2l7 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Connect SoundCloud</h3>
                        <p class="text-zinc-400 leading-relaxed">Link your SoundCloud account to get started.
                            It only takes a few seconds.</p>
                    </div>
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.05] relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#ff5500] to-orange-600">
                        </div>
                        <div class="flex items-center gap-4 mb-6"><span
                                class="text-4xl font-bold text-[#ff5500]">2</span></div>
                        <div
                            class="bg-[#ff5500]/10 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-refresh-cw w-8 h-8 text-[#ff5500]">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                <path d="M21 3v5h-5"></path>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                <path d="M8 16H3v5"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Earn Points</h3>
                        <p class="text-zinc-400 leading-relaxed">Listen to and repost tracks from other artists
                            to earn promotion points.</p>
                    </div>
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.05] relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#ff5500] to-orange-600">
                        </div>
                        <div class="flex items-center gap-4 mb-6"><span
                                class="text-4xl font-bold text-[#ff5500]">3</span></div>
                        <div
                            class="bg-[#ff5500]/10 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-share2 w-8 h-8 text-[#ff5500]">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                                <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Repost Tracks</h3>
                        <p class="text-zinc-400 leading-relaxed">Use your points to get your tracks reposted by
                            other artists.</p>
                    </div>
                    <div
                        class="group bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 hover:transform hover:scale-[1.05] relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#ff5500] to-orange-600">
                        </div>
                        <div class="flex items-center gap-4 mb-6"><span
                                class="text-4xl font-bold text-[#ff5500]">4</span></div>
                        <div
                            class="bg-[#ff5500]/10 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:bg-[#ff5500]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trending-up w-8 h-8 text-[#ff5500]">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 7 22 7 22 13"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Grow Followers</h3>
                        <p class="text-zinc-400 leading-relaxed">Watch your fan base grow as your music reaches
                            new audiences.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-24 px-8 relative overflow-hidden bg-gradient-to-b from-black via-[#ff5500]/5 to-black">
            <div class="max-w-[1400px] mx-auto relative">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="text-left">
                        <div
                            class="inline-flex items-center gap-2 bg-[#ff5500]/10 border border-[#ff5500]/30 px-4 py-2 rounded-full mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-sparkles w-4 h-4 text-[#ff5500]">
                                <path
                                    d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z">
                                </path>
                                <path d="M5 3v4"></path>
                                <path d="M19 17v4"></path>
                                <path d="M3 5h4"></path>
                                <path d="M17 19h4"></path>
                            </svg><span class="text-[#ff5500] text-sm font-semibold">Partner Spotlight</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Supercharge
                            Your Music with<br><span class="text-[#ff5500]">SoundCloud Pro</span></h2>
                        <p class="text-lg text-zinc-300 mb-8 leading-relaxed">SoundCloud Pro gives you the
                            tools to take your music career to the next level. Get advanced stats, unlimited
                            uploads, and more control over your content.</p>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start gap-3">
                                <div
                                    class="bg-[#ff5500]/10 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-bar-chart3 w-5 h-5 text-[#ff5500]">
                                        <path d="M3 3v18h18"></path>
                                        <path d="M18 17V9"></path>
                                        <path d="M13 17V5"></path>
                                        <path d="M8 17v-3"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Advanced Analytics</h4>
                                    <p class="text-zinc-400 text-sm">Track your audience with detailed insights
                                        and performance metrics</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="bg-[#ff5500]/10 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-music2 w-5 h-5 text-[#ff5500]">
                                        <circle cx="8" cy="18" r="4"></circle>
                                        <path d="M12 18V2l7 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Unlimited Uploads</h4>
                                    <p class="text-zinc-400 text-sm">Upload as much music as you want with no
                                        storage limits</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="bg-[#ff5500]/10 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-sparkles w-5 h-5 text-[#ff5500]">
                                        <path
                                            d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z">
                                        </path>
                                        <path d="M5 3v4"></path>
                                        <path d="M19 17v4"></path>
                                        <path d="M3 5h4"></path>
                                        <path d="M17 19h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Spotlight Features</h4>
                                    <p class="text-zinc-400 text-sm">Pin your best tracks and get featured
                                        placement on your profile</p>
                                </div>
                            </div>
                        </div><a href="https://checkout.soundcloud.com/pro" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ff5500] to-orange-600 hover:from-[#ff6a1a] hover:to-orange-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-[#ff5500]/30">Upgrade
                            to SoundCloud Pro<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-right w-5 h-5">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                        <p class="text-sm text-zinc-500 mt-4">Special offer for RepostChain users • Start your
                            free trial today</p>
                    </div>
                    <div class="relative lg:flex hidden justify-end">
                        <div class="relative w-full max-w-lg h-[420px] perspective-1000">
                            <div class="relative w-full h-full cursor-pointer"
                                style="transform-style: preserve-3d; transition: transform 0.8s ease-in-out; transform: rotateY(180deg);">
                                <div class="absolute inset-0" style="backface-visibility: hidden;">
                                    <div
                                        class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-6 rounded-3xl border border-[#ff5500]/30 shadow-2xl shadow-[#ff5500]/20 h-full relative">
                                        <div
                                            class="absolute top-3 right-3 bg-[#ff5500] text-white px-3 py-1 rounded-full text-[10px] font-bold">
                                            MOST POPULAR</div>
                                        <div class="bg-[#ff5500]/5 rounded-2xl p-5 mb-3 border border-[#ff5500]/10">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h3 class="text-2xl font-bold text-white mb-1">Artist Pro
                                                    </h3>
                                                    <p class="text-zinc-400 text-xs">Unlimited access to all
                                                        tools</p>
                                                </div>
                                                <div
                                                    class="bg-[#ff5500] text-white px-3 py-1.5 rounded-lg font-bold text-xl">
                                                    $8.25<span class="text-xs font-normal">/mo</span></div>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-upload w-2.5 h-2.5 text-[#ff5500]">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4">
                                                            </path>
                                                            <polyline points="17 8 12 3 7 8"></polyline>
                                                            <line x1="12" x2="12" y1="3"
                                                                y2="15"></line>
                                                        </svg>
                                                    </div><span class="text-xs">Unlimited
                                                        uploads</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-zap w-2.5 h-2.5 text-[#ff5500]">
                                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2">
                                                            </polygon>
                                                        </svg>
                                                    </div><span class="text-xs">Boost tracks
                                                        unlimited</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-bar-chart3 w-2.5 h-2.5 text-[#ff5500]">
                                                            <path d="M3 3v18h18"></path>
                                                            <path d="M18 17V9"></path>
                                                            <path d="M13 17V5"></path>
                                                            <path d="M8 17v-3"></path>
                                                        </svg>
                                                    </div><span class="text-xs">Distribute &amp;
                                                        monetize tracks</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-[#ff5500]/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-repeat w-2.5 h-2.5 text-[#ff5500]">
                                                            <path d="m17 2 4 4-4 4"></path>
                                                            <path d="M3 11v-1a4 4 0 0 1 4-4h14"></path>
                                                            <path d="m7 22-4-4 4-4"></path>
                                                            <path d="M21 13v1a4 4 0 0 1-4 4H3"></path>
                                                        </svg>
                                                    </div><span class="text-xs">Replace tracks
                                                        anytime</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mb-2"><a href="https://checkout.soundcloud.com/pro"
                                                target="_blank" rel="noopener noreferrer"
                                                class="inline-block bg-gradient-to-r from-[#ff5500] to-orange-600 hover:from-[#ff6a1a] hover:to-orange-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all hover:scale-105 w-full text-center">Get
                                                Started</a></div>
                                        <div class="text-center">
                                            <div
                                                class="inline-flex items-center gap-2 text-[#ff5500] text-[10px] font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-shield w-3 h-3">
                                                    <path
                                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                                    </path>
                                                </svg>Click to see other plan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute inset-0"
                                    style="backface-visibility: hidden; transform: rotateY(180deg);">
                                    <div
                                        class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-6 rounded-3xl border border-blue-500/30 shadow-2xl shadow-blue-500/20 h-full">
                                        <div class="bg-blue-500/5 rounded-2xl p-5 mb-3 border border-blue-500/10">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h3 class="text-2xl font-bold text-white mb-1">Artist</h3>
                                                    <p class="text-zinc-400 text-xs">Essential artist tools</p>
                                                </div>
                                                <div
                                                    class="bg-blue-500 text-white px-3 py-1.5 rounded-lg font-bold text-xl">
                                                    $3.25<span class="text-xs font-normal">/mo</span></div>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-upload w-2.5 h-2.5 text-blue-500">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4">
                                                            </path>
                                                            <polyline points="17 8 12 3 7 8"></polyline>
                                                            <line x1="12" x2="12" y1="3"
                                                                y2="15"></line>
                                                        </svg>
                                                    </div><span class="text-xs">3 hours of
                                                        uploads</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-zap w-2.5 h-2.5 text-blue-500">
                                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2">
                                                            </polygon>
                                                        </svg>
                                                    </div><span class="text-xs">Boost tracks (2x per
                                                        month)</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-bar-chart3 w-2.5 h-2.5 text-blue-500">
                                                            <path d="M3 3v18h18"></path>
                                                            <path d="M18 17V9"></path>
                                                            <path d="M13 17V5"></path>
                                                            <path d="M8 17v-3"></path>
                                                        </svg>
                                                    </div><span class="text-xs">Distribute &amp;
                                                        monetize tracks</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-zinc-300">
                                                    <div
                                                        class="w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-repeat w-2.5 h-2.5 text-blue-500">
                                                            <path d="m17 2 4 4-4 4"></path>
                                                            <path d="M3 11v-1a4 4 0 0 1 4-4h14"></path>
                                                            <path d="m7 22-4-4 4-4"></path>
                                                            <path d="M21 13v1a4 4 0 0 1-4 4H3"></path>
                                                        </svg>
                                                    </div><span class="text-xs">Replace tracks (3x
                                                        per month)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mb-2"><a href="https://checkout.soundcloud.com/artist"
                                                target="_blank" rel="noopener noreferrer"
                                                class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all hover:scale-105 w-full text-center">Get
                                                Started</a></div>
                                        <div class="text-center">
                                            <div
                                                class="inline-flex items-center gap-2 text-blue-500 text-[10px] font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-shield w-3 h-3">
                                                    <path
                                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                                    </path>
                                                </svg>Click to see other plan
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
        <section class="py-24 px-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-[#ff5500]/5 via-transparent to-[#ff5500]/5">
            </div>
            <div class="max-w-[1400px] mx-auto relative">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">RepostChain in Numbers</h2>
                    <p class="text-lg md:text-xl text-zinc-300 max-w-3xl mx-auto leading-relaxed">Join
                        thousands of artists already growing their audience with our platform.</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-10 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 text-center group hover:transform hover:scale-[1.05]">
                        <span id="counter-10000" class="text-5xl md:text-6xl font-bold text-[#ff5500]">0+</span>
                        <p class="text-zinc-300 text-lg mt-4 font-medium">Tracks Reposted</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-10 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 text-center group hover:transform hover:scale-[1.05]">
                        <span id="counter-2000" class="text-5xl md:text-6xl font-bold text-[#ff5500]">0+</span>
                        <p class="text-zinc-300 text-lg mt-4 font-medium">Feedback Circulated</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-10 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 text-center group hover:transform hover:scale-[1.05]">
                        <span id="counter-5000" class="text-5xl md:text-6xl font-bold text-[#ff5500]">0+</span>
                        <p class="text-zinc-300 text-lg mt-4 font-medium">Artists Registered</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-10 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300 text-center group hover:transform hover:scale-[1.05]">
                        <span id="counter-2000000" class="text-5xl md:text-6xl font-bold text-[#ff5500]">0+</span>
                        <p class="text-zinc-300 text-lg mt-4 font-medium">Total Followers Reached</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="success-stories" class="py-24 px-8 relative overflow-hidden bg-gradient-to-b from-black via-zinc-950 to-black">
            <div class="max-w-[1400px] mx-auto relative">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Success Stories</h2>
                    <p class="text-lg md:text-xl text-zinc-300 max-w-3xl mx-auto leading-relaxed">See how
                        artists like you are growing their audience with RepostChain.</p>
                </div>
                <div class="relative overflow-hidden">
                    <div class="flex gap-6 animate-scroll hover:pause-animation">
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"RepostChain helped
                                me gain over 2,500 genuine followers in just 3 months. The engagement is real,
                                and my plays have increased dramatically."</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    VE</div>
                                <div>
                                    <p class="text-white font-semibold">The Velvet Echoes</p>
                                    <p class="text-[#ff5500] text-sm">Band</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"I was struggling to
                                break through until I found RepostChain. Now my tracks are being discovered by
                                the right audience. Incredible platform!"</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    MS</div>
                                <div>
                                    <p class="text-white font-semibold">Midnight Skyline</p>
                                    <p class="text-[#ff5500] text-sm">Record Label</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"The credibility
                                system ensures I'm connecting with real artists and fans. This platform has
                                become an essential part of my growth strategy."</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    AL</div>
                                <div>
                                    <p class="text-white font-semibold">Ava Lennox</p>
                                    <p class="text-[#ff5500] text-sm">Pop Artist</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"Finally, a platform
                                that actually works! I've seen a 300% increase in plays and connected with
                                amazing artists who genuinely support my work."</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    JC</div>
                                <div>
                                    <p class="text-white font-semibold">Jaden Cruz</p>
                                    <p class="text-[#ff5500] text-sm">DJ Artist</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"RepostChain's fair
                                exchange system is brilliant. I've built real relationships with other artists
                                and my fanbase has grown organically."</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    RM</div>
                                <div>
                                    <p class="text-white font-semibold">Riley Monroe</p>
                                    <p class="text-[#ff5500] text-sm">Song Writer</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"Best decision I
                                made for my music career. The analytics help me understand my audience, and the
                                community is incredibly supportive."</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    KR</div>
                                <div>
                                    <p class="text-white font-semibold">Kai Rivers</p>
                                    <p class="text-[#ff5500] text-sm">Hip Hop Artist</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"RepostChain helped
                                me gain over 2,500 genuine followers in just 3 months. The engagement is real,
                                and my plays have increased dramatically."</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    VE</div>
                                <div>
                                    <p class="text-white font-semibold">The Velvet Echoes</p>
                                    <p class="text-[#ff5500] text-sm">Band</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-shrink-0 w-[400px] bg-gradient-to-br from-zinc-900/90 to-zinc-800/70 backdrop-blur-sm p-8 rounded-2xl border border-zinc-800/50 hover:border-[#ff5500]/50 transition-all duration-300">
                            <div class="flex items-center gap-0.5 mb-4">
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                                <div class="bg-[#00b67a] p-1"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-star w-4 h-4 text-white fill-white">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg></div>
                            </div>
                            <p class="text-zinc-300 text-base leading-relaxed mb-6 italic">"I was struggling
                                to break through until I found RepostChain. Now my tracks are being discovered
                                by the right audience. Incredible platform!"</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-[#ff5500] to-orange-600 flex items-center justify-center text-white font-bold">
                                    MS</div>
                                <div>
                                    <p class="text-white font-semibold">Midnight Skyline</p>
                                    <p class="text-[#ff5500] text-sm">Record Label</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-20 px-6">
            <div class="max-w-5xl mx-auto">
                <div
                    class="bg-gradient-to-br from-[#ff5500]/20 via-orange-600/10 to-transparent backdrop-blur-sm border border-[#ff5500]/30 rounded-3xl p-12 text-center relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-[#ff5500]/5 via-transparent to-[#ff5500]/5 animate-pulse">
                    </div>
                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Take Your Music
                            Career<br><span class="text-[#ff5500]">To The Next Level?</span></h2>
                        <p class="text-xl text-zinc-300 mb-8">Join 5,000+ artists already growing their
                            fanbase with Repostchain</p><button
                            class="bg-gradient-to-r from-[#ff5500] to-orange-600 hover:from-[#ff6a1a] hover:to-orange-700 text-white px-12 py-5 rounded-lg text-xl font-semibold transition-all hover:scale-105 shadow-lg shadow-[#ff5500]/30">Start
                            Your Music Growth</button>
                        <p class="text-sm text-zinc-500 mt-4">No credit card required • 100% Free for Artists
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
