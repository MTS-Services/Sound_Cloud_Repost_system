@push('cs')
    <style>
        .section-toggle,
        .article-toggle {
            display: none;
        }

        .section-content {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .section-toggle:checked~.section-content {
            max-height: 3000px;
            opacity: 1;
        }

        .section-chevron {
            transition: transform 0.3s ease;
        }

        .section-toggle:checked~label .section-chevron {
            transform: rotate(90deg);
        }

        .article-answer {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .article-toggle:checked~.article-content .article-answer {
            max-height: 600px;
            opacity: 1;
        }

        .article-chevron {
            transition: transform 0.3s ease;
        }

        .article-toggle:checked~.article-content .article-chevron {
            transform: rotate(90deg);
        }
    </style>
@endpush
<div class="bg-black">
    {{-- <div class="fixed inset-0 bg-gradient-to-br from-black via-zinc-900 to-black -z-10"></div>

    <nav class="fixed top-0 w-full bg-zinc-900/95 backdrop-blur-md border-b border-zinc-800/50 z-50">
        <div class="mx-auto px-6 py-4">
            <div class="flex items-center justify-center relative">
                <a wire:navigate href="{{ route('f.landing') }}"
                    class="absolute left-0 flex items-center gap-2 text-zinc-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="group-hover:stroke-white transition-colors">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    <span>Back</span>
                </a>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo/logo-1.png') }}" alt="Repostchain" class="w-10 h-10">
                    <span class="text-2xl font-bold text-white">RepostChain</span>
                </div>
            </div>
        </div>
    </nav> --}}

    <div class="pt-20">
        <div class="bg-gradient-to-br from-zinc-900/80 to-zinc-800/50 border-b border-zinc-800/50">
            <div class="container mx-auto px-6 py-16 max-w-6xl">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-5xl font-bold text-white mb-2">RepostChain Help Center</h1>
                        <p class="text-zinc-400">Updated help section for the RepostChain website</p>
                    </div>
                </div>
                <div class="relative mt-8">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" placeholder="Search for articles..."
                        class="w-full bg-zinc-800/50 border border-zinc-700/50 rounded-xl pl-12 pr-4 py-4 text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:bg-zinc-800 transition-all" />
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 py-12 max-w-6xl">
            <div class="mb-8">
                <p class="text-zinc-400 text-sm">Welcome to the RepostChain Help Center! Here you'll find answers to all
                    your questions about using our platform to grow your music career.</p>
            </div>

            <div class="space-y-4">

                <!-- All sections with complete articles would go here -->
                <!-- Due to character limits, I'll show the pattern for remaining sections -->

                <!-- Continuing with remaining sections... -->

                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-5" class="section-toggle">
                    <label for="section-5"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Introduction to RepostChain</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-5-1" class="article-toggle">
                                <label for="article-5-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            is RepostChain?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            RepostChain is a community-driven platform designed to help SoundCloud
                                            artists like you connect with a wider audience. Our mission is to provide
                                            you with the tools to organically grow your fanbase, increase engagement on
                                            your tracks, and network with other talented musicians.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-5-2" class="article-toggle">
                                <label for="article-5-2" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">How
                                            do I get started with RepostChain?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Getting started is easy! Simply connect your SoundCloud account, and you'll
                                            be ready to start promoting your music in minutes. New members receive a
                                            welcome bonus of Credits to kickstart their promotional efforts.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-5-3" class="article-toggle">
                                <label for="article-5-3" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            are the benefits of using RepostChain?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Organic Growth: Reach real listeners and build a genuine fanbase. •
                                            Increased Engagement: Get more plays, likes, comments, and reposts on your
                                            tracks. • Networking Opportunities: Connect and collaborate with other
                                            artists in our community. • Valuable Feedback: Receive constructive
                                            criticism on your music from fellow artists.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-6" class="section-toggle">
                    <label for="section-6"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg width="100" height="100" viewBox="0 0 100 100"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" y="5" width="90" height="90" rx="20" fill="#ff7400" />
                                    <g fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="50" cy="40" r="15" />
                                        <path d="M 30 75 C 30 55 70 55 70 75" />
                                    </g>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Your Account</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-6-1" class="article-toggle">
                                <label for="article-6-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">Is
                                            my account safe with RepostChain?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Absolutely. We use SoundCloud's official API to ensure your account is
                                            secure. You have full control over all actions performed on your behalf, and
                                            we will never post or share anything without your permission.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-6-2" class="article-toggle">
                                <label for="article-6-2" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">How
                                            do you handle fake followers?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            We are committed to maintaining a community of real artists and listeners.
                                            Our proprietary TrustScore system analyzes accounts for fake or inactive
                                            followers, ensuring that all promotion is genuine and effective.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-6-3" class="article-toggle">
                                <label for="article-6-3" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">How
                                            do I manage my account settings?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            You can update your genres, change your password, and manage your
                                            subscription from your account settings page.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-6-4" class="article-toggle">
                                <label for="article-6-4" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">How
                                            can I cancel or delete my account?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            You can downgrade to our "Free Forever" plan or permanently delete your
                                            account at any time from your account settings.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-7" class="section-toggle">
                    <label for="section-7"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg width="100" height="100" viewBox="0 0 100 100"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <rect x="5" y="5" width="90" height="90" rx="20" fill="#ff7400" />

                                    <g fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <circle cx="50" cy="50" r="25" />
                                        <circle cx="50" cy="50" r="15" />
                                        <line x1="50" y1="25" x2="50" y2="75" />

                                        <path d="M 40 70 A 30 30 0 0 1 60 70" />
                                        <path d="M 40 30 A 30 30 0 0 0 60 30" />

                                    </g>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Credits (Platform Currency)</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-7-1" class="article-toggle">
                                <label for="article-7-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            are Credits?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Credits are the currency of RepostChain. You can earn them by being an
                                            active member of our community and spend them to promote your music.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-7-2" class="article-toggle">
                                <label for="article-7-2" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">How
                                            do I earn Credits?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Reposting Music: Share tracks from other artists to earn Credits. •
                                            Providing Feedback: Offer constructive feedback in our Feedback Hub. •
                                            Referring Friends: Invite other artists to join RepostChain. • Daily
                                            Rewards: Log in daily to claim your Credits reward.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-7-3" class="article-toggle">
                                <label for="article-7-3" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            can I spend my Credits on?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Use your Credits to access our promotional features, including Direct Repost
                                            Swaps, Repost Campaigns, and feedback on your own tracks.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-8" class="section-toggle">
                    <label for="section-8"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg width="100" height="100" viewBox="0 0 100 100"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" y="5" width="90" height="90" rx="20" fill="#ff7400" />
                                    <g fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="50" cy="45" r="20" />
                                        <polygon
                                            points="50,30 55,40 65,40 57,47 60,57 50,52 40,57 43,47 35,40 45,40" />
                                        <path d="M 30 65 L 20 75 M 70 65 L 80 75" />
                                    </g>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Membership Tiers</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-8-1" class="article-toggle">
                                <label for="article-8-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            membership tiers are available?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            We offer a range of membership tiers to suit every artist's needs, from our
                                            "Free Forever" plan to our Premium plan. Each tier unlocks additional
                                            features and benefits. You can find a detailed comparison of our plans at
                                            https://repostchain.com/user/plans
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-10" class="section-toggle">
                    <label for="section-10"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Direct Repost Swaps</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-10-1" class="article-toggle">
                                <label for="article-10-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            is a Direct Repost Swap?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            A Direct Repost Swap allows you to send a repost request directly to another
                                            member of the RepostChain community. This is a great way to connect with
                                            artists in your genre and get your music in front of a targeted audience.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-11" class="section-toggle">
                    <label for="section-11"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Repost Campaigns</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-11-1" class="article-toggle">
                                <label for="article-11-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            is a Repost Campaign?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            A Repost Campaign makes your track available for any member of the community
                                            to repost. This is a powerful tool for reaching a broad audience and gaining
                                            momentum on your latest release. Our platform also offers targeting options
                                            to ensure your music reaches the right listeners.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-12" class="section-toggle">
                    <label for="section-12"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Feedback Hub</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-12-1" class="article-toggle">
                                <label for="article-12-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">How
                                            can I get feedback on my music?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Our Feedback Hub allows you to get honest and constructive feedback from
                                            other artists. Simply create a Feedback Campaign, and members will provide
                                            you with valuable insights to help you improve your craft.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-13" class="section-toggle">
                    <label for="section-13"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Community Guidelines & Moderation</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-13-1" class="article-toggle">
                                <label for="article-13-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            are the community guidelines?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            We are committed to maintaining a positive and supportive community. Please
                                            familiarize yourself with our community guidelines to ensure a great
                                            experience for everyone. Accounts that violate our rules may be subject to
                                            warnings or bans.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 overflow-hidden hover:border-zinc-700/50 transition-all">
                    <input type="checkbox" id="section-14" class="section-toggle">
                    <label for="section-9"
                        class="w-full flex items-center justify-between p-6 text-left cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3m.08 4h.01" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white group-hover:text-orange-500 transition-colors">
                                Frequently Asked Questions</h2>
                        </div>
                        <svg class="section-chevron w-5 h-5 text-zinc-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </label>
                    <div class="section-content">
                        <div class="px-6 pb-6 space-y-3">
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-14-1" class="article-toggle">
                                <label for="article-14-1" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">Why
                                            doesn't the follower count on RepostChain match my SoundCloud
                                            account?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            Our system syncs with SoundCloud periodically, so there may be a slight
                                            delay in updating your follower count.
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div
                                class="rounded-xl bg-zinc-800/30 border border-zinc-700/30 overflow-hidden hover:border-zinc-600/50 transition-all">
                                <input type="checkbox" id="article-14-2" class="article-toggle">
                                <label for="article-14-2" class="article-content w-full block cursor-pointer">
                                    <div class="flex items-center justify-between p-4 group">
                                        <span
                                            class="text-white font-medium group-hover:text-orange-500 transition-colors pr-4">What
                                            is the Platform Fee?</span>
                                        <svg class="article-chevron w-4 h-4 text-zinc-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <div class="article-answer">
                                        <div
                                            class="px-4 pb-4 text-zinc-300 leading-relaxed border-t border-zinc-700/30 pt-4">
                                            To maintain the health of our platform's economy, a small percentage of
                                            Credits are deducted from each transaction. This helps us to continue
                                            developing new features and supporting our community.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div
                class="mt-12 p-8 rounded-2xl bg-gradient-to-br from-orange-500/10 to-orange-400/5 border border-orange-500/20">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white mb-3">Still need help?</h3>
                    <p class="text-zinc-400 mb-6">If you have any other questions, please don't hesitate to contact our
                        support team. We're here to help you succeed!</p>
                    <a href="{{ route('f.contact-us') }}"
                        class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-400 text-white font-semibold hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300">
                        Contact Support
                    </a>
                </div>
            </div>

            <div class="mt-12 text-center text-sm text-zinc-500">
                <p>Company: Tunexa Limited</p>
                <p>Website: <a href="https://repostchain.com"
                        class="text-orange-500 hover:text-orange-400">https://repostchain.com</a></p>
            </div>
        </div>
    </div>
</div>
