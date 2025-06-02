<x-admin::layout>
    <x-slot name="title">{{ __('Create Permission') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Permission') }}</x-slot>
    <x-slot name="page_slug">permission</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Permission') }}</h2>
                <x-admin.primary-link href="{{ route('am.permission.index') }}">{{ __('Back') }}
                </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.permission.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <p class="label">{{ __('Prefix') }}</p>
                            <input type="text" class="input" placeholder="Prefix" value="{{ old('prefix') }}"
                                name="prefix" class="flex-1" />
                            <x-input-error class="mt-2" :messages="$errors->get('prefix')" />
                        </div>

                        <div class="space-y-2">
                            <p class="label">{{ __('Permission Name') }}</p>
                            <input type="text" class="input" placeholder="Name" value="{{ old('name') }}"
                                name="name" class="flex-1" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
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
