<x-admin::layout>
    <x-slot name="title">{{ __('Edit plan') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit plan') }}</x-slot>
    <x-slot name="page_slug">plan</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit plan') }}</h2>
                <x-button href="{{ route('pm.plan.index') }}" icon="undo-2" type='info' permission="plan-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-7">
            <div class="glass-card rounded-2xl p-6 md:col-span-7">
                <form action="{{ route('pm.plan.update', encrypt($plan->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                        {{-- Plan Details --}}
                        <div class="space-y-2">
                            <x-inputs.input name="name" id="title" label="{{ __('Name') }}"
                                placeholder="Enter Plan Name" value="{{ old('name', $plan->name) }}"
                                :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="monthly_price" label="{{ __('Monthly Price') }}"
                                placeholder="Enter Price Monthly"
                                value="{{ old('monthly_price', $plan->monthly_price) }}" :messages="$errors->get('monthly_price')" />
                        </div>

                        <div class="space-y-2 cpy-3" x-data="{ selectedTag: '{{ old('tag', $plan->tag) }}' }">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Plan Tag') }}</label>
                            <div class="flex gap-6 mt-2 flex-wrap">
                                @foreach (\App\Models\Plan::getTagList() as $tagValue => $tagLabel)
                                    <label class="inline-flex items-center cursor-pointer space-x-2"
                                        @click="selectedTag = '{{ $tagValue }}'">
                                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center transition-all duration-150"
                                            :class="selectedTag == '{{ $tagValue }}' ? 'border-blue-600' :
                                                'border-gray-400 dark:border-gray-600'">
                                            <div class="w-2.5 h-2.5 rounded-full"
                                                :class="selectedTag == '{{ $tagValue }}' ? 'bg-blue-600' : 'bg-transparent'">
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm text-gray-800 dark:text-gray-200">{{ $tagLabel }}</span>
                                        <input type="radio" name="tag" value="{{ $tagValue }}" class="hidden"
                                            x-model="selectedTag">
                                    </label>
                                @endforeach
                            </div>
                            @error('tag')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Feature Categories & Features --}}
                    <div class="bg-white dark:bg-gray-900 col-span-2 overflow-hidden ">
                        <div
                            class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-800">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                {{ __('Plan Features') }}
                            </h3>
                        </div>

                        <div class="px-3 py-6">


                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                                @forelse ($features as $feature)
                                    <div>
                                        <div x-data="{ checked: true }" x-init="checked = true">
                                            <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                                                id="feature_{{ $feature->id }}" x-model="checked" class="hidden">
                                            <div :class="checked ?
                                                'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-900/50 bg-blue-100 dark:bg-blue-900/30' :
                                                'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700'"
                                                class="flex flex-col items-center p-3 h-full border rounded-lg transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-sm">

                                                <label for="feature_{{ $feature->id }}"
                                                    class="flex items-center w-full mb-1 cursor-pointer">
                                                    <div class="mt-0.5 mr-3 flex-shrink-0">
                                                        <div :class="checked ? 'bg-blue-700 border-blue-600' :
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
                                                        {{ $feature->features_name }}
                                                    </p>
                                                </label>
                                                <template x-if="checked">
                                                    <div class="w-full mt-2">
                                                        <select name="feature_values[{{ $feature->id }}]"
                                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-800 dark:text-white"
                                                            @click.stop>
                                                            @foreach ($feature->feature_values as $value)
                                                                <option value="{{ $value }}"
                                                                    {{ $feature->relationValue($plan->id)->value == $value ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error class="mt-2" :messages="$errors->get('feature_values.' . $feature->id)" />
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No features in this category') }}
                                    </div>
                                @endforelse
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('features')" />
                            <x-input-error class="mt-2" :messages="$errors->get('feature_values')" />
                        </div>
                    </div>
                    <div class="space-y-2 pt-3 sm:col-span-2">
                        <x-inputs.textarea name="notes" label="{{ __('Notes') }}" placeholder="Enter notes"
                            value="{{ old('notes', $plan->notes) }}" :messages="$errors->get('notes')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-admin::layout>
