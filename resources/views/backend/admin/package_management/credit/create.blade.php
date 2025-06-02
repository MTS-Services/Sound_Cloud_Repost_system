<x-admin::layout>
    <x-slot name="title">{{ __('Create Credit') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Credit') }}</x-slot>
    <x-slot name="page_slug">credit</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Credit') }}</h2>
                <x-admin.primary-link href="{{ route('pm.credit.index') }}">{{ __('Back') }} </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.credit.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Name') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </g>
                                </svg>
                                <input type="text" placeholder="Name" value="{{ old('name') }}" name="name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Price -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Price') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="number" name="price" value="{{ old('price') }}"
                                    placeholder="Enter price" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>
                        <!-- Credits -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Credits') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="credits" value="{{ old('credits') }}"
                                    placeholder="Enter credits" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('credits')" />
                        </div>
                    </div>
                    <!-- notes -->
                    <div class="space-y-2">
                       <p class="label py-2">{{ __('Notes') }}</p>
                        <textarea name="notes" id="notes" rows="4" placeholder="Enter notes"
                            class="w-full border-gray-300 dark:border-gray-600">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('notes')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Create') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
</x-admin::layout>
