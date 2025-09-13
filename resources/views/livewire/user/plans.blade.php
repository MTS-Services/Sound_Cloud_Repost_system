<x-slot name="page_slug">pricing</x-slot>

<section x-data="{ isYearly: @entangle('isYearly') }">
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-slate-900 dark:text-white mb-4">Choose Your Perfect Plan</h1>
        <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">Pro Plans unlock the full potential of
            Repostxchange
            with exclusive features designed to supercharge your music promotion.</p>
    </div>
    <div class="flex items-center justify-center mb-12">
        <div
            class="bg-slate-200 dark:bg-slate-700 p-1 rounded-xl shadow-lg border border-slate-300 dark:border-slate-600">
            <div class="flex items-center">
                <button @click="isYearly = false"
                    :class="!isYearly ? 'bg-slate-300 dark:bg-slate-600 shadow-md text-slate-900 dark:text-white' :
                        'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                    class="px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-300">Billed
                    Monthly</button>
                <button @click="isYearly = true"
                    :class="isYearly ? 'bg-slate-300 dark:bg-slate-600 shadow-md text-slate-900 dark:text-white' :
                        'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
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
                class="relative bg-white dark:bg-slate-700 rounded-3xl shadow-2xl border transition-all duration-300 hover:shadow-3xl hover:scale-105 border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 {{ Auth::check() && (user()->activePlan()?->plan?->id == $plan->id || (!user()->activePlan()->plan && $plan->monthly_price == 0)) ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">{{ $plan->name }}</h3>
                        <p class="text-slate-600 dark:text-slate-300 mb-6">
                            {{ $plan->notes }}
                        </p>
                        <div class="mb-6">
                            <div class="flex items-baseline justify-center">
                                <span class="text-6xl font-bold text-slate-900 dark:text-white">
                                    $<span
                                        x-text="isYearly ? {{ number_format($plan->yearly_price, 2) }} : {{ number_format($plan->monthly_price, 2) }}"></span>
                                </span>
                                <span class="text-slate-500 dark:text-slate-400 ml-2">
                                    /<span
                                        x-text="{{ $plan->monthly_price > 0 ? 'isYearly ? \'year\' : \'month\'' : '\'Forever\'' }}"></span>
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
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-x w-4 h-4">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20 text-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-check w-4 h-4">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2"><span
                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ $featureRelation?->feature?->name }}</span><span
                                            class="text-sm font-bold text-orange-400">{{ $featureRelation?->feature?->type == App\Models\Feature::TYPE_STRING ? $featureRelation->value : '' }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $featureRelation?->feature?->note }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($plan->monthly_price > 0)
                        <x-gbutton variant="primary" wire:click="subscribe('{{ encrypt($plan->id) }}')"
                            class="w-full py-4 px-6">Choose Plan</x-gbutton>
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
</section>
