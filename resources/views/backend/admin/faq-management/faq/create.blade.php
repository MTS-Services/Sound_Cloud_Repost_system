<x-admin::layout>
    <x-slot name="title">{{ __('Faq ') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Faq ') }}</x-slot>
    <x-slot name="page_slug">faq</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Faq') }}</h2>
                <x-button href="{{ route('fm.faq.index') }}" icon="undo-2" type='info' permission="faq-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('fm.faq.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-2">
                        <x-inputs.select name="faq_category_id" label="{{ __('Faq Category') }}" icon="shield"
                            placeholder="{{ __('Select a Faq Category') }}" :options="$faq_categories->pluck('name', 'id')->toArray()" :selected="old('faq_category_id')"
                            :messages="$errors->get('faq_category_id')" />
                    </div>



                    <div class="space-y-2">
                        <x-inputs.input name="question" label="{{ __('Question') }}" placeholder="Enter Question"
                            value="{{ old('question') }}" :messages="$errors->get('question')" />
                    </div>


                    <div class="space-y-2">
                        <x-inputs.input name="description" label="{{ __('Description') }}"
                            placeholder="Enter Description" value="{{ old('description') }}" :messages="$errors->get('description')" />
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
