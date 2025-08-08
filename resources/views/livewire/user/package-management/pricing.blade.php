<x-slot name="page_slug">pricing</x-slot>

<section class="min-h-screen  duration-500">

    <section class="container mx-auto px-4 py-16">
        <div class="text-center mb-12" x-intersect="$el.classList.add('fade-in-up')">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                Choose Your Perfect Plan
            </h2>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Pro Plans unlock the full potential of RepostExchange with exclusive features designed to
                supercharge your music promotion.
            </p>

            <div class="mt-8 flex items-center justify-center">
                <span class="mr-4 font-medium text-slate-700 dark:text-slate-300">Billed Monthly</span>
                <button wire:click='switchPlan()' type="button"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                    :class="{{ $yearly_plan }} ? 'bg-orange-500' : 'bg-slate-300 dark:bg-slate-700'" role="switch"
                    aria-checked="false">
                    <span class="sr-only">Toggle billing</span>
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                        :class="{{ $yearly_plan }} ? 'translate-x-6' : 'translate-x-1'"></span>
                </button>
                <span class="ml-4 font-medium text-slate-700 dark:text-slate-300">Billed Annually</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-16">
            @foreach ($plans as $plan)
                <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2 hover:border-orange-500 hover:border-1"
                    x-intersect="$el.classList.add('fade-in-up ')"
                    x-intersect:leave="$el.classList.remove('fade-in-up')"
                    :style="`transition-delay: {{ $loop->index * 100 }}ms`"
                    @mouseenter="hoveredCard = '{{ $plan->name }}'" @mouseleave="hoveredCard = null">
                    <div class="text-center p-6 pb-4">
                        @if ($plan->tag !== null)
                            <div
                                class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold ">
                                {{ $plan->tag_label }}
                            </div>
                        @endif

                        <div
                            class="mx-auto mb-4 p-3 bg-slate-100 dark:bg-slate-700 rounded-full w-fit transition-all duration-300">
                            @if ($plan->monthly_price != 0)
                                <x-lucide-crown class="w-5 h-5 text-warning" />
                            @else
                                <x-heroicon-o-star class="w-5 h-5 text-slate-900 dark:text-white" />
                            @endif
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-slate-900 dark:text-white">{{ $plan->name }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ $plan->description }}</p>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white">
                                @if ($yearly_plan && $plan->monthly_price != 0)
                                    <span>${{ $plan->yearly_price }}</span><sup
                                        class="text-sm text-red-500"><del>${{ $plan->monthly_price * 12 }}</del></sup>
                                @else
                                    ${{ $plan->monthly_price }}
                                @endif
                            </span>
                            <span
                                class="text-slate-600 dark:text-slate-400">/{{ $plan->monthly_price == 0 ? 'Forever' : ($yearly_plan ? 'Year' : 'Month') }}</span>
                            @if ($yearly_plan)
                                <div
                                    class="card bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white p-2 rounded-lg text-xs mt-2">
                                    {{ __("Save {$plan->yearly_save_percentage}% yearly") }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 pt-0 w-full ">
                        <button
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm md:text-base py-1.5 md:py-2 px-3 md:px-4 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                            {{ $plan->monthly_price == 0 ? 'Get Started' : 'Choose Plan' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden mb-16 p-4 transition-all duration-300 hover:shadow-2xl"
            x-intersect="$el.classList.add('fade-in-up')">
            <div class="bg-slate-50 dark:bg-slate-700 px-6 py-4 border-b dark:border-slate-600">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white">Feature Comparison</h3>
                <p class="text-slate-600 dark:text-slate-400">Compare all features across our plans</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-slate-50 dark:bg-slate-700 dark:border-slate-600">
                            <th
                                class="p-4 text-left font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wide sticky left-0 z-10">
                                Features</th>
                            @foreach ($plans as $plan)
                                <th class="text-center p-4 font-semibold text-slate-900 dark:text-white min-w-[120px]">
                                    {{ $plan->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="last:border-b-0">
                        @foreach ($featureCategories as $category)
                            {{-- Category Header Row --}}
                            <tr class="bg-slate-100 dark:bg-slate-900">
                                <td colspan="{{ count($plans) + 1 }}"
                                    class="p-4 font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wide sticky left-0 z-10">
                                    {{ $category->name }}
                                </td>
                            </tr>
                            @foreach ($category->features as $feature)
                                @php
                                    $isLastCategory = $loop->parent->last;
                                    $isLastFeature = $loop->last;
                                @endphp
                                <tr
                                    class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors {{ $isLastCategory && $isLastFeature ? 'border-b-0' : '' }}">
                                    <td class="p-4 font-medium text-slate-700 dark:text-slate-300  ">
                                        {{ $feature->name }}
                                    </td>

                                    @foreach ($plans as $plan)
                                        @php
                                            $relation = $plan->featureRelations->firstWhere('feature_id', $feature->id);
                                            $value = $relation?->value ?? null;
                                        @endphp

                                        <td class="p-4 text-center">
                                            @if ($value === 'true' || $value === true)
                                                <x-heroicon-o-check class="h-5 w-5 text-green-500 mx-auto" />
                                            @elseif ($value === 'false' || $value === false || is_null($value) || $value === '0' || $value === 0)
                                                <x-heroicon-o-x-mark class="h-5 w-5 text-red-500 mx-auto" />
                                            @else
                                                <span
                                                    class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $value }}</span>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="text-center p-6 bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                x-intersect="$el.classList.add('fade-in-up')" :style="`transition-delay: ${100}ms`">
                <div
                    class="mx-auto mb-4 p-3 bg-blue-100 dark:bg-blue-900 rounded-full w-fit transition-all duration-300 hover:scale-110">
                    <x-lucide-users class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Free Boosts</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Boost your campaigns to get more exposure from the community. The more you engage with the
                    community, the more boosts you'll receive.
                </p>
            </div>

            <div class="text-center p-6 bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                x-intersect="$el.classList.add('fade-in-up')" :style="`transition-delay: ${200}ms`">
                <div
                    class="mx-auto mb-4 p-3 bg-green-100 dark:bg-green-900 rounded-full w-fit transition-all duration-300 hover:scale-110">
                    <x-lucide-zap class="h-8 w-8 text-green-600 dark:text-green-400" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Simultaneous Campaigns</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Run multiple campaigns at once to maximize your reach. Each campaign can target different
                    audiences and goals.
                </p>
            </div>

            <div class="text-center p-6 bg-white dark:bg-slate-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                x-intersect="$el.classList.add('fade-in-up')" :style="`transition-delay: ${300}ms`">
                <div
                    class="mx-auto mb-4 p-3 bg-purple-100 dark:bg-purple-900 rounded-full w-fit transition-all duration-300 hover:scale-110">
                    <x-lucide-crown class="h-8 w-8 text-purple-600 dark:text-purple-400" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Priority Direct Requests</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Get direct requests and special treatment from our network. Increase visibility and build
                    relationships with other artists.
                </p>
            </div>
        </div>

        {{-- <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 transition-all duration-500 hover:shadow-2xl"
            x-intersect="$el.classList.add('fade-in-up')">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 text-center">Frequently Asked Questions
            </h3>

        </div> --}}
    </section>

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
</section>
