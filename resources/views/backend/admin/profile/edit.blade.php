<x-admin::layout>
    <x-slot name="title">{{ __('Edit Admin Profile') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit Admin Profile') }}</x-slot>
    <x-slot name="page_slug">admin-profile-edit</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Admin Profile') }}</h2>
                <x-button href="{{ route('admin.profile.index') }}" icon="undo-2" type='info'>
                    {{ __('Back to Profile') }}
                </x-button>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <form action="{{ route('admin.profile.update', encrypt($admin->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <!-- Name -->
                    <div class="space-y-2">
                        <x-inputs.input name="name" label="{{ __('Name') }}" icon="user"
                            placeholder="Enter Name" value="{{ old('name', $admin->name) }}" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <x-inputs.input name="email" label="{{ __('Email') }}" icon="mail"
                            placeholder="Enter Email" value="{{ old('email', $admin->email) }}" :messages="$errors->get('email')" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <x-inputs.input name="password" label="{{ __('New Password') }}" type="password"
                            icon="key-round" placeholder="Enter New Password" :messages="$errors->get('password')" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <x-inputs.input name="password_confirmation" label="{{ __('Confirm New Password') }}"
                            type="password" icon="key-round" placeholder="Confirm New Password" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <!-- Image -->
                     <div class="space-y-2 col-span-2">
                            <x-inputs.file name="image" label="{{ __('Image') }}"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg" :messages="$errors->get('image')" />
                        </div>
                </div>

                <div class="flex justify-end mt-5">
                    <x-button type="accent" :button="true" icon="save">{{ __('Update Profile') }}</x-button>
                </div>
            </form>
        </div>
    </section>

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
</x-admin::layout>
