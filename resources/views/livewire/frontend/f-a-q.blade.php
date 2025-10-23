@push('cs')
    <style>
        .faq-item input[type="checkbox"] {
            display: none;
        }

        .faq-answer {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item input[type="checkbox"]:checked~.faq-content .faq-answer {
            max-height: 400px;
            opacity: 1;
        }

        .chevron {
            transition: transform 0.3s ease;
        }

        .faq-item input[type="checkbox"]:checked~.faq-content .chevron {
            transform: rotate(180deg);
        }
    </style>
@endpush
<div class="bg-dark-darker">
    {{-- <div class="fixed inset-0 bg-gradient-to-br from-black via-zinc-900 to-black -z-10"></div>
    <nav class="fixed top-0 w-full bg-black/95 backdrop-blur-md border-b border-zinc-800/50 z-50">
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

    <!-- Main Content -->
    <div class="container mx-auto px-6 pt-32 pb-20 max-w-5xl">
        <!-- Header -->
        <div class="text-center mb-16">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-orange-500/10 to-orange-400/10 border border-orange-500/20 mb-6">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3m.08 4h.01" />
                </svg>
                <span class="text-sm font-medium text-orange-500">Help Center</span>
            </div>

            <h1 class="text-6xl font-bold text-white mb-4">
                Frequently Asked Questions
            </h1>
            <p class="text-xl text-zinc-400 mb-2">Find answers to common questions about RepostChain</p>
            <p class="text-sm text-zinc-500">Last Updated: 10/09/2025</p>
        </div>

        <!-- Help Box -->
        <div class="mb-12 p-6 rounded-2xl bg-gradient-to-br from-zinc-900/50 to-zinc-800/30 border border-zinc-800/50">
            <div class="flex items-start gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Need More Help?</h3>
                    <p class="text-zinc-400 mb-3">Can't find what you're looking for? Our support team is here to help.
                    </p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <a href="mailto:support@repostchain.com"
                            class="text-orange-500 hover:text-orange-400 transition-colors">
                            📧 support@repostchain.com
                        </a>
                        <span class="text-zinc-600">|</span>
                        <span class="text-zinc-400">Response time: 24-48 hours</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="space-y-8">

            <!-- Category 1: About RepostChain -->
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">About RepostChain</h2>
                </div>

                <div class="space-y-3">
                    <div class="faq-item">
                        <input type="checkbox" id="faq-0-0">
                        <label for="faq-0-0"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    What is RepostChain?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    RepostChain is a platform created for music artists to grow their audience through
                                    organic reposts, networking, and collaboration. Artists can exchange reposts, build
                                    genuine engagement, and discover other musicians within their genre — all in one
                                    community-driven space.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-0-1">
                        <label for="faq-0-1"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Who can join RepostChain?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    RepostChain is for artists only — singers, producers, DJs, or music creators who
                                    share original content. If you're not an artist or share non-musical content, your
                                    account may be restricted.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-0-2">
                        <label for="faq-0-2"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Is RepostChain affiliated with SoundCloud or Spotify?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    No. RepostChain is not affiliated with or endorsed by SoundCloud, Spotify, or any
                                    other streaming platform. We operate independently to help artists promote their
                                    music organically and responsibly.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Category 2: Using the Platform -->
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3m.08 4h.01" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Using the Platform</h2>
                </div>

                <div class="space-y-3">
                    <div class="faq-item">
                        <input type="checkbox" id="faq-1-0">
                        <label for="faq-1-0"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    How does RepostChain work?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    You can: Create your artist profile, Connect with other musicians, Earn or use
                                    repost credits, Launch repost campaigns for your tracks. When others accept your
                                    campaign, they repost your music — and you can do the same to build visibility in
                                    return.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-1-1">
                        <label for="faq-1-1"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    What are "credits"?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Credits are the internal currency used on RepostChain to run campaigns. You earn
                                    credits by reposting other artists' tracks or purchase them to promote your own
                                    music faster.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-1-2">
                        <label for="faq-1-2"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Can I choose who reposts my track?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Yes! You have full control over your campaigns. You can target specific genres,
                                    artists, or follower levels to ensure your reposts come from the right audience.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-1-3">
                        <label for="faq-1-3"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Can I remove my repost after sharing?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Yes, but removing a repost after a campaign agreement may affect your reputation
                                    score or credit balance. We encourage all users to follow through on repost
                                    commitments.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Category 3: Payments and Refunds -->
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"
                                stroke-width="2" />
                            <line x1="1" y1="10" x2="23" y2="10" stroke-width="2" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Payments and Refunds</h2>
                </div>

                <div class="space-y-3">
                    <div class="faq-item">
                        <input type="checkbox" id="faq-2-0">
                        <label for="faq-2-0"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    What payment methods do you accept?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    We accept major credit/debit cards and other secure online payment options through
                                    our third-party processors. All payments are encrypted and handled securely.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-2-1">
                        <label for="faq-2-1"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Can I get a refund if I change my mind?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Because RepostChain provides digital services, refunds are only available in limited
                                    situations — such as duplicate payments or system errors. Please read our full
                                    Refund & Cancellation Policy for details.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-2-2">
                        <label for="faq-2-2"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    What happens if my campaign doesn't perform well?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    RepostChain guarantees organic exposure, not specific engagement numbers. Success
                                    depends on the audience, genre, and content quality — but our system ensures real
                                    artists and reposts, never bots.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Category 4: Account and Security -->
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"
                                stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Account and Security</h2>
                </div>

                <div class="space-y-3">
                    <div class="faq-item">
                        <input type="checkbox" id="faq-3-0">
                        <label for="faq-3-0"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    How do I reset my password?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Go to the login page, click "Forgot Password", and follow the instructions. If you
                                    still can't access your account, contact support@repostchain.com for assistance.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-3-1">
                        <label for="faq-3-1"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Can I delete my account?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Yes. You can request account deletion anytime by emailing support@repostchain.com.
                                    Once deleted, your data and credits will be permanently removed.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-3-2">
                        <label for="faq-3-2"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Why was my account suspended?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Accounts may be suspended for: Using bots or fake accounts, Spamming or violating
                                    our Terms, Reposting copyrighted or explicit material. Please contact our support
                                    team if you believe your suspension was an error.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-3-3">
                        <label for="faq-3-3"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    What should I do if someone reposts my copyrighted content?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    If you believe your content has been shared without permission, please file a DMCA
                                    Takedown Notice. Visit our DMCA Policy for full instructions.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-3-4">
                        <label for="faq-3-4"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Do you store my payment information?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    No. RepostChain does not store any credit card or payment details. All payments are
                                    securely processed by verified third-party payment gateways.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Category 5: General Information -->
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2v20M2 12h20" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">General Information</h2>
                </div>

                <div class="space-y-3">
                    <div class="faq-item">
                        <input type="checkbox" id="faq-4-0">
                        <label for="faq-4-0"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Is RepostChain available worldwide?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Yes! Artists from all around the world can join and collaborate on RepostChain.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-4-1">
                        <label for="faq-4-1"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    Can I promote multiple songs at once?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    Yes. You can run multiple campaigns simultaneously — just make sure you have enough
                                    credits for each one.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="faq-item">
                        <input type="checkbox" id="faq-4-2">
                        <label for="faq-4-2"
                            class="faq-content group rounded-xl bg-zinc-900/50 border border-zinc-800/50 hover:border-zinc-700/50 transition-all duration-300 block cursor-pointer">
                            <div class="w-full flex items-center justify-between p-6">
                                <span
                                    class="text-lg font-semibold text-white pr-4 group-hover:text-orange-500 transition-colors">
                                    How can I contact support?
                                </span>
                                <svg class="chevron w-5 h-5 text-zinc-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="faq-answer">
                                <div class="px-6 pb-6 text-zinc-300 leading-relaxed border-t border-zinc-800/50 pt-4">
                                    For any inquiries, email us at support@repostchain.com. Our team typically responds
                                    within 24–48 hours on business days.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <!-- Contact Section -->
        <div
            class="mt-16 p-8 rounded-2xl bg-gradient-to-br from-orange-500/10 to-orange-400/5 border border-orange-500/20">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-white mb-3">Still have questions?</h3>
                <p class="text-zinc-400 mb-6">We're here to help you succeed on your musical journey</p>
                <a href="mailto:support@repostchain.com"
                    class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-400 text-white font-semibold hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300">
                    Contact Support
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-sm text-zinc-500">
            <p>Company: Tunexa Limited</p>
            <p>Website: <a href="https://repostchain.com"
                    class="text-orange-500 hover:text-orange-400">https://repostchain.com</a></p>
        </div>
    </div>
</div>
