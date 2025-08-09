<x-admin::layout>
    <x-slot name="title">{{ __('Create Feature') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Feature') }}</x-slot>
    <x-slot name="page_slug">feature</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Feature') }}</h2>
                <x-button href="{{ route('pm.feature.index') }}" icon="undo-2" type='info' permission="feature-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.feature.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="space-y-2">
                            <x-inputs.select name="feature_category_id" label="{{ __('Feature Category') }}"
                                icon="shield" placeholder="{{ __('Select a Feature Category') }}" :options="$feature_categories->pluck('name', 'id')->toArray()"
                                :selected="old('feature_category_id')" :messages="$errors->get('feature_category_id')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.select name="name" label="{{ __('Feature Name') }}" icon="shield"
                                placeholder="{{ __('Select a Feature Name') }}" :options="App\Models\Feature::getFeaturedNames()" :selected="old('name')"
                                :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.select name="type" label="{{ __('Type') }}" icon="shield"
                                placeholder="{{ __('Select a Type') }}" :options="App\Models\Feature::getTypes()" :selected="old('type')"
                                :messages="$errors->get('type')" />
                        </div>
                        <div class="space-y-2 sm:col-span-3">
                            <x-inputs.textarea name="note" value="{{ old('note') }}" label="{{ __('Note') }}"
                                placeholder="Enter Note" :messages="$errors->get('note')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
</x-admin::layout>
