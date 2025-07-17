<x-admin::layout>
    <x-slot name="title">{{ __('Create Admin') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Admin') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    @push('css')
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/filepond.css') }}"> --}}
    @endpush

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Admin') }}</h2>
                <x-button href="{{ route('am.admin.index') }}" icon="undo-2" type='info' permission="admin-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" icon="user"
                                placeholder="Enter Name" value="{{ old('name') }}"
                                :messages="$errors->get('name')"></x-admin.inputs.input>
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="email" label="{{ __('Email') }}" icon="mail"
                                placeholder="Enter Email" value="{{ old('email') }}"
                                :messages="$errors->get('email')"></x-admin.inputs.input>
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="password" label="{{ __('Password') }}" type="password"
                                icon="key-round" placeholder="Enter Password" :messages="$errors->get('password')"></x-admin.inputs.input>
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="password_confirmation" label="{{ __('Confirm Password') }}"
                                type="password" icon="key-round" placeholder="Enter Password"
                                :messages="$errors->get('password_confirmation')"></x-admin.inputs.input>
                        </div>

                        <div class="space-y-2">
                            <x-inputs.select name="role" label="{{ __('Role') }}" icon="shield"
                                placeholder="{{ __('Select a Role') }}" :options="$roles->pluck('name', 'id')->toArray()" :selected="old('role')"
                                :messages="$errors->get('role')" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.file name="image" label="{{ __('Image') }}"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg" :messages="$errors->get('image')" />
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
    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"]);
            });
        </script>
    @endpush
</x-admin::layout>
