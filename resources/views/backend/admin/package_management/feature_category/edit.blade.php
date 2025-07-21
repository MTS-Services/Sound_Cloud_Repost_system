<x-admin::layout>
    <x-slot name="title">{{ __('Update Feature Category') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Update Feature Category') }}</x-slot>
    <x-slot name="page_slug">feature_category</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Update Feature Category') }}</h2>
                <x-button href="{{ route('pm.feature-category.index') }}" icon="undo-2" type='info' permission="feature_category-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">

            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.feature-category.update', encrypt($feature_category->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter Credit Name"
                                value="{{ $feature_category->name }}" :messages="$errors->get('name')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>


    </section>
</x-admin::layout>
