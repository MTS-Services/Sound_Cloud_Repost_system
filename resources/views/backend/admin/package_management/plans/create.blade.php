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

                        <div class="glass-card p-8 rounded-3xl shadow-2xl col-span-2 bg-white w-full dark:bg-gray-800">
                            <h3
                                class="text-2xl font-bold text-gray-900 dark:text-white mb-8 border-b-2 border-gray-200 dark:border-gray-700 pb-4">
                                {{ __('Feature Categories & Features') }}
                            </h3>

                            @forelse($feature_categories as $category)
                                <div class="grid grid-cols-1 lg:grid-cols-6 gap-8 py-8 border-b last:border-b-0">
                                    <div class="lg:col-span-1">
                                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                            {{ $category->name }}
                                        </p>
                                        @if ($category->description)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                                {{ $category->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="lg:col-span-5">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                                            @forelse($category->features as $feature)
                                                <label for="feature_{{ $feature->id }}"
                                                    class="relative flex items-center p-4 border rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow duration-200 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                                    <input type="checkbox" name="features[]"
                                                        value="{{ $feature->id }}" id="feature_{{ $feature->id }}"
                                                        class="absolute opacity-0 pointer-events-none peer" />

                                                    <div
                                                        class="h-5 w-5 rounded-md border-2 border-gray-400 dark:border-gray-500 flex items-center justify-center transition-colors duration-200 peer-checked:bg-blue-600 peer-checked:border-blue-600">
                                                        <svg class="w-4 h-4 text-white opacity-0 transition-opacity duration-200 peer-checked:opacity-100"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>

                                                    <span
                                                        class="ml-3 text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $feature->name }}
                                                    </span>
                                                </label>
                                            @empty
                                                <p class="text-sm text-gray-400 italic">
                                                    {{ __('No features found') }}
                                                </p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-base text-gray-500 text-center py-4">
                                    {{ __('No feature categories found.') }}
                                </p>
                            @endforelse
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter Plan Name"
                                value="{{ old('name') }}" :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="slug" label="{{ __('Slug') }}" placeholder="Enter plan slug"
                                value="{{ old('slug') }}" :messages="$errors->get('slug')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly" label="{{ __('Monthly Price') }}"
                                placeholder="Enter Price Monthly" value="{{ old('price_monthly') }}"
                                :messages="$errors->get('price_monthly')" />
                        </div>
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
