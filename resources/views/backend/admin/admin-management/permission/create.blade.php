<x-admin::layout>
    <x-slot name="title">{{ __('Create Permission') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Permission') }}</x-slot>
    <x-slot name="page_slug">permission</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Permission') }}</h2>
                <x-button href="{{ route('am.permission.index') }}" icon="undo-2" type='info'
                    permission="permission-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.permission.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <x-inputs.input name="prefix" label="{{ __('Prefix') }}" placeholder="Enter prefix"
                                value="{{ old('prefix') }}" :messages="$errors->get('prefix')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter name"
                                value="{{ old('name') }}" :messages="$errors->get('name')" />
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
