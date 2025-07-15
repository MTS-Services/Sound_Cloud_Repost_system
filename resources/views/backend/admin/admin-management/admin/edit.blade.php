<x-admin::layout>
    <x-slot name="title">{{ __('Update Admin') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Update Admin') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Update Admin') }}</h2>
                <x-button href="{{ route('am.admin.index') }}" icon="undo-2" type='info'>
                    {{ __('Back') }}
                </x-admin.button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.admin.update', encrypt($admin->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" icon="user"
                                placeholder="Enter Name" value="{{ $admin->name }}" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <x-inputs.input name="email" label="{{ __('Email') }}" icon="mail"
                                placeholder="Enter Email" value="{{ $admin->email }}" :messages="$errors->get('email')" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <x-inputs.input name="password" label="{{ __('Password') }}" type="password"
                                icon="key-round" placeholder="Enter Password" :messages="$errors->get('password')" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <x-inputs.input name="password_confirmation" label="{{ __('Confirm Password') }}"
                                type="password" icon="key-round" placeholder="Enter Password" :messages="$errors->get('password_confirmation')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.select name="role" label="{{ __('Role') }}" icon="shield"
                                placeholder="{{ __('Select a Role') }}" :options="$roles->pluck('name', 'id')->toArray()" :selected="$admin->role_id"
                                :messages="$errors->get('role')" />
                        </div>
                        <div class="space-y-2 col-span-2">
                            <x-inputs.file name="image" label="{{ __('Image') }}"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg" :messages="$errors->get('image')" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true"
                            icon="save">{{ __('Update') }}</x-admin.button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>

        @push('js')
            <script src="{{ asset('assets/js/filepond.js') }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                        "#image": "{{ $admin->modified_image }}"
                    });

                });
            </script>
        @endpush

    </section>
</x-admin::layout>
