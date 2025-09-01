<x-admin::layout>
    <x-slot name="title">{{ __('Update Credit') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Update Credit') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Update Credit') }}</h2>
                <x-button href="{{ route('pm.credit.index') }}" icon="undo-2" type='info' permission="credit-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">

            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.credit.update', encrypt($credit->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter Credit Name"
                                value="{{ $credit->name }}" :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="price" label="{{ __('Price') }}" placeholder="Enter Price"
                                value="{{ $credit->price }}" :messages="$errors->get('price')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="credits" label="{{ __('Credits') }}" placeholder="Enter Credits"
                                value="{{ $credit->credits }}" :messages="$errors->get('credits')" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.textarea name="notes" label="{{ __('Notes') }}" placeholder="Enter notes"
                                value="{{ $credit->notes }}" :messages="$errors->get('notes')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
        @push('js')
            <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
        @endpush

    </section>
</x-admin::layout>
