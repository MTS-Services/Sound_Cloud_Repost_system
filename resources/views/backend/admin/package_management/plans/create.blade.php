<x-admin::layout>
    <x-slot name="title">{{ __('Create plan') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create plan') }}</x-slot>
    <x-slot name="page_slug">plan</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create plan') }}</h2>
                <x-button href="{{ route('pm.plan.index') }}" icon="undo-2" type='info' permission="plan-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
        {{-- @dd($feature_categories) --}}
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.plan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        {{-- Feature Categories & Features --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg col-span-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                            <!-- Header -->
                            <div
                                class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-800">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    {{ __('Feature Categories & Features') }}
                                </h3>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                @forelse ($feature_categories as $category)
                                    <div class="mb-8 last:mb-0">
                                        <!-- Category Header -->
                                        <div class="flex items-center mb-4">
                                            <div class="w-1 h-8 bg-blue-500 rounded-full mr-3"></div>
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $category->name }}
                                            </h4>
                                        </div>

                                        <!-- Feature Grid -->
                                        <div
                                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                                            @forelse ($category->features as $feature)
                                                <div x-data="{ checked: false }" x-init="checked = false">
                                                    <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                                                        id="feature_{{ $feature->id }}" x-model="checked"
                                                        class="hidden">

                                                    <div :class="checked
                                                        ?
                                                        'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-900/50 bg-blue-100 dark:bg-blue-900/30' :
                                                        'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700'"
                                                        class="flex flex-col items-start p-2.5 h-full border rounded-lg transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-sm">

                                                        <!-- Checkbox Icon & Label -->
                                                        <label for="feature_{{ $feature->id }}"
                                                            class="flex items-center w-full mb-1 cursor-pointer">
                                                            <div class="mt-0.5 mr-3 flex-shrink-0">
                                                                <div :class="checked
                                                                    ?
                                                                    'bg-blue-700 border-blue-600' :
                                                                    'border-gray-300 dark:border-gray-500'"
                                                                    class="h-5 w-5 rounded border-2 flex items-center justify-center transition-all duration-200">
                                                                    <svg x-show="checked" class="w-3 h-3 text-white"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <p
                                                                class="text-sm font-medium text-gray-800 dark:text-gray-200 select-none">
                                                                {{ $feature->name }}
                                                            </p>
                                                        </label>

                                                        <!-- Input field ONLY SHOWS when checked -->
                                                        <template x-if="checked">
                                                            <input type="text"
                                                                name="feature_values[{{ $feature->id }}]"
                                                                placeholder="Enter value for this feature"
                                                                class="mt-2 w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring focus:ring-blue-200 dark:bg-gray-800 dark:text-white"
                                                                @click.stop />
                                                        </template>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                                                    {{ __('No features in this category') }}
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div
                                            class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('No feature categories found') }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Feature categories will appear here once available') }}
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>


                        {{-- name --}}
                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter Plan Name"
                                value="{{ old('name') }}" :messages="$errors->get('name')" />
                        </div>
                        {{-- slug --}}
                        <div class="space-y-2">
                            <x-inputs.input name="slug" label="{{ __('Slug') }}" placeholder="Enter plan slug"
                                value="{{ old('slug') }}" :messages="$errors->get('slug')" />
                        </div>
                        {{-- monthly price --}}
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly" label="{{ __('Monthly Price') }}"
                                placeholder="Enter Price Monthly" value="{{ old('price_monthly') }}"
                                :messages="$errors->get('price_monthly')" />
                        </div>
                        {{-- yearly price --}}
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly_yearly" label="{{ __('Yearly Price') }}"
                                placeholder="Enter Price Monthly Yearly" value="{{ old('price_monthly_yearly') }}"
                                :messages="$errors->get('price_monthly_yearly')" />
                        </div>
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <x-inputs.textarea name="notes" label="{{ __('Notes') }}" placeholder="Enter notes"
                            value="{{ old('notes') }}" :messages="$errors->get('notes')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
    @push('js')
    @endpush
</x-admin::layout>
