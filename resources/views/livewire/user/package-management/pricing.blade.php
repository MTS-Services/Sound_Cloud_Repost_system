<div>
    <x-slot name="page_slug">pricing</x-slot>

    <section class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100" x-data="pricing()"
        x-init="init()">

        <!-- Main Content -->
        <section class="container mx-auto px-4 py-16">
            <!-- Pricing Section Header -->
            <div class="text-center mb-12" x-intersect="$el.classList.add('fade-in-up')">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Choose Your Perfect Plan
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Pro Plans unlock the full potential of RepostExchange with exclusive features designed to
                    supercharge your music promotion.
                </p>

                <!-- Toggle for annual/monthly -->
                <div class="mt-8 flex items-center justify-center">
                    <span class="mr-4 font-medium text-slate-700">Billed Monthly</span>
                    <button @click="toggleBilling" type="button"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                        :class="annualBilling ? 'bg-orange-500' : 'bg-slate-300'" role="switch" aria-checked="false">
                        <span class="sr-only">Toggle billing</span>
                        <span
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                            :class="annualBilling ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                    <span class="ml-4 font-medium text-slate-700">Billed Annually <span class="text-orange-500">(Save
                            20%)</span></span>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-16">
                <!-- Free Forever Plan -->
                <div class="relative bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2"
                    x-intersect="$el.classList.add('fade-in-up')" x-intersect:leave="$el.classList.remove('fade-in-up')"
                    :style="`transition-delay: ${100}ms`" @mouseenter="hoveredCard = 'free'"
                    @mouseleave="hoveredCard = null">
                    <div class="text-center p-6 pb-4">
                        <div class="mx-auto mb-4 p-3 bg-slate-100 rounded-full w-fit transition-all duration-300"
                            :class="{ 'scale-110': hoveredCard === 'free' }">
                            <i data-lucide="star" class="h-6 w-6 text-slate-700"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Free Forever</h3>
                        <p class="text-sm text-slate-600 mb-4">Perfect for getting started</p>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-slate-900">$0</span>
                            <span class="text-slate-600">/forever</span>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-[1.02]"
                            @click="showPlanDetails('free')">
                            Get Started
                        </button>
                    </div>
                </div>

                <!-- Artist Plan -->
                <div class="relative bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2"
                    x-intersect="$el.classList.add('fade-in-up')" x-intersect:leave="$el.classList.remove('fade-in-up')"
                    :style="`transition-delay: ${200}ms`" @mouseenter="hoveredCard = 'artist'"
                    @mouseleave="hoveredCard = null">
                    <div class="text-center p-6 pb-4">
                        <div class="mx-auto mb-4 p-3 bg-slate-100 rounded-full w-fit transition-all duration-300"
                            :class="{ 'scale-110': hoveredCard === 'artist' }">
                            <i data-lucide="users" class="h-6 w-6 text-slate-700"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Artist</h3>
                        <p class="text-sm text-slate-600 mb-4">For individual artists</p>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-slate-900"
                                x-text="annualBilling ? '$12' : '$15'"></span>
                            <span class="text-slate-600">/month</span>
                            <div x-show="annualBilling" class="text-xs text-slate-500 mt-1">billed annually</div>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-[1.02]"
                            @click="showPlanDetails('artist')">
                            Choose Plan
                        </button>
                    </div>
                </div>

                <!-- Network Plan (Most Popular) -->
                <div class="relative bg-white rounded-xl shadow-xl ring-2 ring-orange-500 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl"
                    x-intersect="$el.classList.add('fade-in-up')" x-intersect:leave="$el.classList.remove('fade-in-up')"
                    :style="`transition-delay: ${300}ms`" @mouseenter="hoveredCard = 'network'"
                    @mouseleave="hoveredCard = null">
                    <div
                        class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold animate-pulse">
                        Most Popular
                    </div>
                    <div class="text-center p-6 pb-4">
                        <div class="mx-auto mb-4 p-3 bg-orange-100 rounded-full w-fit transition-all duration-300"
                            :class="{ 'scale-110': hoveredCard === 'network' }">
                            <i data-lucide="zap" class="h-6 w-6 text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Network</h3>
                        <p class="text-sm text-slate-600 mb-4">For growing networks</p>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-slate-900"
                                x-text="annualBilling ? '$24' : '$30'"></span>
                            <span class="text-slate-600">/month</span>
                            <div x-show="annualBilling" class="text-xs text-slate-500 mt-1">billed annually</div>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-[1.02] shadow-lg shadow-orange-500/20"
                            @click="showPlanDetails('network')">
                            Choose Plan
                        </button>
                    </div>
                </div>

                <!-- Promoter Plan -->
                <div class="relative bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2"
                    x-intersect="$el.classList.add('fade-in-up')" x-intersect:leave="$el.classList.remove('fade-in-up')"
                    :style="`transition-delay: ${400}ms`" @mouseenter="hoveredCard = 'promoter'"
                    @mouseleave="hoveredCard = null">
                    <div class="text-center p-6 pb-4">
                        <div class="mx-auto mb-4 p-3 bg-slate-100 rounded-full w-fit transition-all duration-300"
                            :class="{ 'scale-110': hoveredCard === 'promoter' }">
                            <i data-lucide="crown" class="h-6 w-6 text-slate-700"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Promoter</h3>
                        <p class="text-sm text-slate-600 mb-4">For professional promoters</p>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-slate-900"
                                x-text="annualBilling ? '$100' : '$125'"></span>
                            <span class="text-slate-600">/month</span>
                            <div x-show="annualBilling" class="text-xs text-slate-500 mt-1">billed annually</div>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-[1.02]"
                            @click="showPlanDetails('promoter')">
                            Choose Plan
                        </button>
                    </div>
                </div>

                <!-- Ultimate Plan -->
                <div class="relative bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2"
                    x-intersect="$el.classList.add('fade-in-up')"
                    x-intersect:leave="$el.classList.remove('fade-in-up')" :style="`transition-delay: ${500}ms`"
                    @mouseenter="hoveredCard = 'ultimate'" @mouseleave="hoveredCard = null">
                    <div class="text-center p-6 pb-4">
                        <div class="mx-auto mb-4 p-3 bg-slate-100 rounded-full w-fit transition-all duration-300"
                            :class="{ 'scale-110': hoveredCard === 'ultimate' }">
                            <i data-lucide="infinity" class="h-6 w-6 text-slate-700"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Ultimate</h3>
                        <p class="text-sm text-slate-600 mb-4">For enterprise solutions</p>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-slate-900"
                                x-text="annualBilling ? '$240' : '$300'"></span>
                            <span class="text-slate-600">/month</span>
                            <div x-show="annualBilling" class="text-xs text-slate-500 mt-1">billed annually</div>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 px-4 rounded-lg font-semibold transition-all duration-300 hover:scale-[1.02]"
                            @click="showPlanDetails('ultimate')">
                            Choose Plan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Feature Comparison Table -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-16 transition-all duration-300 hover:shadow-2xl"
                x-intersect="$el.classList.add('fade-in-up')">
                <div class="bg-slate-50 px-6 py-4 border-b">
                    <h3 class="text-2xl font-bold text-slate-900">Feature Comparison</h3>
                    <p class="text-slate-600">Compare all features across our plans</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b bg-slate-50">
                                <th
                                    class="text-left p-4 font-semibold text-slate-900 min-w-[200px] sticky left-0 bg-white z-10">
                                    Features</th>
                                @foreach ($plans as $plan)
                                    <th class="text-center p-4 font-semibold text-slate-900 min-w-[120px]">
                                        {{ $plan->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($featureCategories as $category)
                                {{-- Category Header Row --}}
                                <tr class="bg-slate-100">
                                    <td colspan="{{ count($plans) + 1 }}"
                                        class="p-4 font-semibold text-slate-800 text-sm uppercase tracking-wide sticky left-0 z-10">
                                        {{ $category->name }}
                                    </td>
                                </tr>
                                @foreach ($category->features as $feature)
                                    <tr class="border-b hover:bg-slate-50 transition-colors">
                                        <td class="p-4 font-medium text-slate-700">
                                            {{ $feature->name }}
                                        </td>

                                        @foreach ($plans as $plan)
                                            @php
                                                $relation = $plan->featureRelations->firstWhere(
                                                    'feature_id',
                                                    $feature->id,
                                                );
                                                $value = $relation?->value ?? null;
                                            @endphp

                                            <td class="p-4 text-center">
                                                @if ($value === 'true' || $value === true)
                                                    <i data-lucide="check" class="h-5 w-5 text-green-500 mx-auto"></i>
                                                @elseif ($value === 'false' || $value === false || is_null($value) || $value === '0' || $value === 0)
                                                    <i data-lucide="x" class="h-5 w-5 text-slate-400 mx-auto"></i>
                                                @else
                                                    <span class="text-sm font-medium">{{ $value }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                    x-intersect="$el.classList.add('fade-in-up')" :style="`transition-delay: ${100}ms`">
                    <div
                        class="mx-auto mb-4 p-3 bg-blue-100 rounded-full w-fit transition-all duration-300 hover:scale-110">
                        <i data-lucide="users" class="h-8 w-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Free Boosts</h3>
                    <p class="text-slate-600">
                        Boost your campaigns to get more exposure from the community. The more you engage with the
                        community, the more boosts you'll receive.
                    </p>
                </div>

                <div class="text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                    x-intersect="$el.classList.add('fade-in-up')" :style="`transition-delay: ${200}ms`">
                    <div
                        class="mx-auto mb-4 p-3 bg-green-100 rounded-full w-fit transition-all duration-300 hover:scale-110">
                        <i data-lucide="zap" class="h-8 w-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Simultaneous Campaigns</h3>
                    <p class="text-slate-600">
                        Run multiple campaigns at once to maximize your reach. Each campaign can target different
                        audiences and goals.
                    </p>
                </div>

                <div class="text-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                    x-intersect="$el.classList.add('fade-in-up')" :style="`transition-delay: ${300}ms`">
                    <div
                        class="mx-auto mb-4 p-3 bg-purple-100 rounded-full w-fit transition-all duration-300 hover:scale-110">
                        <i data-lucide="crown" class="h-8 w-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Priority Direct Requests</h3>
                    <p class="text-slate-600">
                        Get direct requests and special treatment from our network. Increase visibility and build
                        relationships with other artists.
                    </p>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 transition-all duration-500 hover:shadow-2xl"
                x-intersect="$el.classList.add('fade-in-up')">
                <h3 class="text-2xl font-bold text-slate-900 mb-8 text-center">Frequently Asked Questions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-slate-300 transition-colors duration-200"
                            x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <h4 class="font-semibold text-slate-900 mb-2 cursor-pointer flex justify-between items-center"
                                @click="open = !open">
                                <span x-text="faq.question"></span>
                                <i :class="open ? 'rotate-180' : ''"
                                    class="h-5 w-5 text-slate-500 transition-transform duration-200"
                                    data-lucide="chevron-down"></i>
                            </h4>
                            <p class="text-slate-600 text-sm transition-all duration-300 overflow-hidden"
                                :class="open ? 'max-h-40 mt-2' : 'max-h-0'" x-text="faq.answer">
                            </p>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <!-- Plan Details Modal -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" x-show="showModal"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="showModal = false"
            @keydown.window.escape="showModal = false" x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                x-show="showModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-2xl font-bold text-slate-900" x-text="selectedPlan.name + ' Plan Details'">
                        </h3>
                        <button @click="showModal = false" class="text-slate-500 hover:text-slate-700">
                            <i data-lucide="x" class="h-6 w-6"></i>
                        </button>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <i :data-lucide="selectedPlan.icon" class="h-5 w-5 mr-2"
                                :class="selectedPlan.iconColor"></i>
                            <span class="text-lg font-semibold" x-text="selectedPlan.price"></span>
                            <span class="text-slate-600 ml-1" x-text="selectedPlan.billing"></span>
                        </div>
                        <p class="text-slate-600" x-text="selectedPlan.description"></p>
                    </div>

                    <h4 class="font-semibold text-slate-900 mb-3">Key Features:</h4>
                    <ul class="space-y-2 mb-6">
                        <template x-for="feature in selectedPlan.features">
                            <li class="flex items-start">
                                <i data-lucide="check" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span class="text-slate-700" x-text="feature"></span>
                            </li>
                        </template>
                    </ul>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-200">
                        <button @click="showModal = false"
                            class="px-4 py-2 border border-slate-300 rounded-lg font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            Compare Plans
                        </button>
                        <button
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-medium transition-colors flex-1"
                            :class="selectedPlan.highlight ? 'bg-orange-500 hover:bg-orange-600' : ''">
                            Select Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .fade-in-up {
                animation: fadeInUp 0.5s ease-out forwards;
                opacity: 0;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            [x-cloak] {
                display: none !important;
            }
        </style>

        <script>
            function pricing() {
                return {
                    hoveredCard: null,
                    annualBilling: false,
                    showModal: false,
                    selectedPlan: {},
                    faqs: [{
                            question: "Do I need credits or a plan to get started?",
                            answer: "No! You can start with our Free Forever plan and begin promoting your music immediately. Credits and paid plans unlock additional features and higher limits."
                        },
                        {
                            question: "Can I change my plan anytime?",
                            answer: "Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately and you'll be charged or credited accordingly."
                        },
                        {
                            question: "What happens if I exceed my monthly limits?",
                            answer: "You can purchase additional credits or upgrade to a higher plan. Your campaigns will continue running without interruption."
                        },
                        {
                            question: "Do credits expire?",
                            answer: "Credits from paid plans never expire. Free credits may have expiration dates, but we'll always notify you in advance."
                        }
                    ],
                    plans: {
                        free: {
                            name: "Free Forever",
                            icon: "star",
                            iconColor: "text-slate-700",
                            price: "$0",
                            billing: "/forever",
                            description: "Perfect for getting started with basic promotion features.",
                            features: [
                                "5 monthly reposts",
                                "1 simultaneous campaign",
                                "Free boost on campaign",
                                "Basic analytics"
                            ],
                            highlight: false
                        },
                        artist: {
                            name: "Artist",
                            icon: "users",
                            iconColor: "text-slate-700",
                            price: this.annualBilling ? "$12" : "$15",
                            billing: "/month",
                            description: "For individual artists looking to grow their audience.",
                            features: [
                                "25 monthly reposts",
                                "2 simultaneous campaigns",
                                "Repost likes/reposts/follows",
                                "Campaign analytics",
                                "Campaign targeting"
                            ],
                            highlight: false
                        },
                        network: {
                            name: "Network",
                            icon: "zap",
                            iconColor: "text-orange-500",
                            price: this.annualBilling ? "$24" : "$30",
                            billing: "/month",
                            description: "For growing networks and more serious promotion needs.",
                            features: [
                                "50 monthly reposts",
                                "3 simultaneous campaigns",
                                "SoundCloud chat invite",
                                "Standard campaign manager",
                                "Monetize artwork"
                            ],
                            highlight: true
                        },
                        promoter: {
                            name: "Promoter",
                            icon: "crown",
                            iconColor: "text-slate-700",
                            price: this.annualBilling ? "$100" : "$125",
                            billing: "/month",
                            description: "For professional promoters managing multiple artists.",
                            features: [
                                "100 monthly reposts",
                                "5 simultaneous campaigns",
                                "Premium campaign manager",
                                "Managed campaigns",
                                "3 featured campaigns"
                            ],
                            highlight: false
                        },
                        ultimate: {
                            name: "Ultimate",
                            icon: "infinity",
                            iconColor: "text-slate-700",
                            price: this.annualBilling ? "$240" : "$300",
                            billing: "/month",
                            description: "For enterprise solutions with maximum promotion power.",
                            features: [
                                "500 monthly reposts",
                                "10 simultaneous campaigns",
                                "Dedicated campaign manager",
                                "Unlimited campaign budget",
                                "5 featured campaigns"
                            ],
                            highlight: false
                        }
                    },
                    init() {
                        // Initialize any data or event listeners
                    },
                    toggleBilling() {
                        this.annualBilling = !this.annualBilling;
                        // Update prices in plans
                        this.plans.artist.price = this.annualBilling ? "$12" : "$15";
                        this.plans.network.price = this.annualBilling ? "$24" : "$30";
                        this.plans.promoter.price = this.annualBilling ? "$100" : "$125";
                        this.plans.ultimate.price = this.annualBilling ? "$240" : "$300";
                    },
                    showPlanDetails(plan) {
                        this.selectedPlan = this.plans[plan];
                        this.showModal = true;
                    }
                }
            }
        </script>
    </section>
</div>
