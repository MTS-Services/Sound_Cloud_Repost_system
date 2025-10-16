<section class="bg-gradient-to-b from-dark-darker to-dark-bg" x-data="{ isYearly: @entangle('isYearly') }">
    <div class="container mx-auto px-4 pt-24 pb-8" id="plans">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4">Choose Your Perfect Plan</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">Pro Plans unlock the full potential of
                Repostxchange
                with exclusive features designed to supercharge your music promotion.</p>
        </div>
        <div class="flex items-center justify-center mb-12">
            <div class="bg-slate-700 p-1 rounded-xl shadow-lg border border-slate-600">
                <div class="flex items-center">
                    <button @click="isYearly = false"
                        :class="!isYearly ? 'bg-slate-600 shadow-md text-white ' : 'text-slate-300 hover:text-white'"
                        class="px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-300">Billed
                        Monthly</button>
                    <button @click="isYearly = true"
                        :class="isYearly ? 'bg-slate-600 shadow-md text-white ' : 'text-slate-300 hover:text-white'"
                        class="px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-300 relative">Billed
                        Annually<span
                            class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Save
                            {{ App\Models\Plan::getYearlySavePercentage() }}%</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto mb-16">
            @forelse ($plans as $plan)
                <div
                    class="relative bg-slate-700 rounded-3xl shadow-2xl border transition-all duration-300 hover:shadow-3xl hover:scale-105 border-slate-600 hover:border-slate-500 {{ Auth::check() && user()->activePlan()?->plan?->id == $plan->id ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-bold text-white mb-3">{{ $plan->name }}</h3>
                            <p class="text-slate-300 mb-6">
                                {{ $plan->notes }}
                            </p>
                            <div class="mb-6">
                                <div class="flex items-baseline justify-center">
                                    <span class="text-6xl font-bold text-white">
                                        ${{ $plan->monthly_price }}
                                    </span>
                                    <span class="text-slate-400 ml-2">
                                        /<span>{{ $plan->monthly_price > 0 ?  'month' : 'Forever' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @foreach ($plan->featureRelations as $featureRelation)
                            <div class="space-y-4 mb-8">
                                <div class="flex items-start space-x-3">

                                    @if ($featureRelation?->feature?->type == App\Models\Feature::TYPE_BOOLEAN && $featureRelation->value == 'False')
                                        <div
                                            class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-red-500/20 text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-x w-4 h-4">
                                                <path d="M18 6 6 18"></path>
                                                <path d="m6 6 12 12"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div
                                            class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20 text-green-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-check w-4 h-4">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2"><span
                                                class="text-sm font-medium text-white">{{ $featureRelation?->feature?->name }}</span><span
                                                class="text-sm font-bold text-orange-400">{{ $featureRelation?->feature?->type == App\Models\Feature::TYPE_STRING ? $featureRelation->value : '' }}</span>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1">{{ $featureRelation?->feature?->note }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($plan->monthly_price > 0)
                            <x-gabutton variant="primary"
                                href="{{ Auth::guard('web')->check() ? 'javascript:void(0)' : route('soundcloud.redirect') }}"
                                wire:click="subscribe('{{ encrypt($plan->id) }}')" class="w-full py-4 px-6">Choose
                                Plan</x-gabutton>
                        @else
                            <x-gabutton variant="secondary" href="{{ route('user.dashboard') }}"
                                class="w-full py-4 px-6">Get
                                Started</x-gabutton>
                        @endif

                    </div>
                </div>
            @empty
            @endforelse
        </div>
        <div class="max-w-6xl mx-auto mb-16">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-4">Join <span class="text-orange-400">10,000+</span>
                    Artists Already Growing</h2>
                <p class="text-xl text-slate-300">See how artists are transforming their careers with
                    RepostChain
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-700 rounded-2xl p-6 border border-slate-600">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400 mb-2">500%</div>
                        <div class="text-white font-semibold mb-2">Average Growth</div>
                        <div class="text-slate-300 text-sm">in followers within 30 days</div>
                    </div>
                </div>
                <div class="bg-slate-700 rounded-2xl p-6 border border-slate-600">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400 mb-2">2.5M+</div>
                        <div class="text-white font-semibold mb-2">Total Plays</div>
                        <div class="text-slate-300 text-sm">generated for our artists</div>
                    </div>
                </div>
                <div class="bg-slate-700 rounded-2xl p-6 border border-slate-600">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400 mb-2">48hrs</div>
                        <div class="text-white font-semibold mb-2">Average Response</div>
                        <div class="text-slate-300 text-sm">time for campaigns</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-4xl mx-auto mb-16">
            <div
                class="bg-gradient-to-r from-orange-500/10 to-orange-600/10 rounded-3xl p-8 border border-orange-500/20">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-4">Why Artists Choose <span class="text-orange-400">Pro
                            Plans</span></h2>
                </div>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-orange-500 rounded-lg p-2 flex-shrink-0"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5 text-white">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg></div>
                            <div>
                                <h3 class="text-white font-semibold mb-2">5x More Exposure</h3>
                                <p class="text-slate-300 text-sm">Get 100 direct requests vs 20 on free
                                    plan.
                                    Reach
                                    more curators and grow faster.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-orange-500 rounded-lg p-2 flex-shrink-0"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-users w-5 h-5 text-white">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg></div>
                            <div>
                                <h3 class="text-white font-semibold mb-2">Multi-Account Power</h3>
                                <p class="text-slate-300 text-sm">Promote unlimited accounts
                                    simultaneously.
                                    Perfect for labels and serious artists.</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-orange-500 rounded-lg p-2 flex-shrink-0"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-bar-chart3 w-5 h-5 text-white">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M18 17V9"></path>
                                    <path d="M13 17V5"></path>
                                    <path d="M8 17v-3"></path>
                                </svg></div>
                            <div>
                                <h3 class="text-white font-semibold mb-2">Advanced Analytics</h3>
                                <p class="text-slate-300 text-sm">Track reach, followers, and reposts
                                    with
                                    detailed
                                    insights to optimize your strategy.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-orange-500 rounded-lg p-2 flex-shrink-0"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-crown w-5 h-5 text-white">
                                    <path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"></path>
                                </svg></div>
                            <div>
                                <h3 class="text-white font-semibold mb-2">Priority Support</h3>
                                <p class="text-slate-300 text-sm">Get faster responses and dedicated
                                    support to
                                    maximize your promotion success.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">What Artists Are Saying</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-700 rounded-2xl p-6 border border-slate-600">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                            M</div>
                        <div class="ml-3">
                            <div class="text-white font-semibold">Marcus Chen</div>
                            <div class="text-slate-400 text-sm">Electronic Producer</div>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">"RepostChain Pro helped me get my track featured
                        on
                        15
                        major
                        playlists. My monthly listeners went from 500 to 25,000!"</p>
                </div>
                <div class="bg-slate-700 rounded-2xl p-6 border border-slate-600">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                            S</div>
                        <div class="ml-3">
                            <div class="text-white font-semibold">Sarah Williams</div>
                            <div class="text-slate-400 text-sm">Indie Artist</div>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">"The analytics dashboard is incredible. I can see
                        exactly
                        which campaigns work and optimize my promotion strategy."</p>
                </div>
                <div class="bg-slate-700 rounded-2xl p-6 border border-slate-600">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                            D</div>
                        <div class="ml-3">
                            <div class="text-white font-semibold">DJ Rodriguez</div>
                            <div class="text-slate-400 text-sm">Hip-Hop Artist</div>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm">"Managing 10 campaigns at once with Pro saved me
                        hours.
                        The
                        priority support team is amazing too!"</p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <div class="bg-slate-700 rounded-2xl p-8 border border-slate-600 max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold text-white mb-4">Ready to Grow Your Music Career?</h2>
                <p class="text-slate-300 mb-6">Join thousands of artists who've already transformed
                    their
                    reach
                    with RepostChain Pro.</p>
                <x-gabutton variant="primary" href="#plans" class="py-4 px-6">Start
                    Your Pro Journey Today</x-gabutton>
                <div class="mt-6">
                    <p class="text-slate-300 mb-4">Need help choosing the right plan?</p><button
                        class="text-orange-400 hover:text-orange-300 font-semibold underline transition-colors duration-200">Contact
                        our team</button>
                </div>
            </div>
        </div>
    </div>
</section>
