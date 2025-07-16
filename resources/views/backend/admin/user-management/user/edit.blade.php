<x-admin::layout>
    <x-slot name="title">{{ __('Update User') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Update User') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Update User') }}</h2>
                <x-button href="{{ route('um.user.index') }}" icon="undo-2" type='info' permission="admin-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('um.user.update', encrypt($user->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" icon="user"
                                placeholder="Enter Name" value="{{ $user->name }}" :messages="$errors->get('name')" />

                        </div>
                        <!-- Nickname -->
                        <div class="space-y-2">
                            <x-inputs.input name="nickname" label="{{ __('Nickname') }}" icon="user-circle"
                                placeholder="Enter Nickname" value="{{ $user->nickname }}" :messages="$errors->get('nickname')" />

                        </div>
                        <!-- SoundCloud ID -->
                        <div class="space-y-2">
                            <x-inputs.input name="soundcloud_id" label="{{ __('SoundCloud ID') }}" icon="fingerprint"
                                placeholder="Enter SoundCloud ID" value="{{ $user->soundcloud_id }}"
                                :messages="$errors->get('soundcloud_id')" />

                        </div>

                        <!-- First Name -->
                        <div class="space-y-2">
                            <x-inputs.input name="first_name" label="{{ __('First Name') }}" icon="user"
                                placeholder="Enter First Name" value="{{ $user->userInfo?->first_name ?? '' }}"
                                :messages="$errors->get('first_name')" />

                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <x-inputs.input name="last_name" label="{{ __('Last Name') }}" icon="user"
                                placeholder="Enter Last Name" value="{{ $user->userInfo?->last_name ?? '' }}"
                                :messages="$errors->get('last_name')" />

                        </div>

                        <!-- Username -->
                        <div class="space-y-2">
                            <x-inputs.input name="username" label="{{ __('Username') }}" icon="at-sign"
                                placeholder="Enter Username" value="{{ $user->userInfo?->username ?? '' }}"
                                :messages="$errors->get('username')" />
                        </div>

                        <!-- Description -->
                        <div class="space-y-2 col-span-2">
                            <x-inputs.textarea name="description" class="textarea" rows="8"
                                label="{{ __('Description') }}" icon="align-left" placeholder="Enter Description"
                                :messages="$errors->get('description')">{{ $user->userInfo?->description ?? '' }}</x-inputs.textarea>
                        </div>

                        <!-- Country -->
                        <div class="space-y-2">
                            <x-inputs.input name="country" label="{{ __('Country') }}" icon="globe"
                                placeholder="Enter Country" value="{{ $user->userInfo?->country ?? '' }}"
                                :messages="$errors->get('country')" />
                        </div>

                        <!-- City -->
                        <div class="space-y-2">
                            <x-inputs.input name="city" label="{{ __('City') }}" icon="map-pin"
                                placeholder="Enter City" value="{{ $user->userInfo?->city ?? '' }}"
                                :messages="$errors->get('city')" />
                        </div>

                        <!-- Plan -->
                        <div class="space-y-2">
                            <x-inputs.input name="plan" label="{{ __('Plan') }}" icon="award"
                                placeholder="Enter Plan" value="{{ $user->userInfo?->plan ?? '' }}"
                                :messages="$errors->get('plan')" />
                        </div>

                        <!-- MySpace Name -->
                        <div class="space-y-2">
                            <x-inputs.input name="myspace_name" label="{{ __('MySpace Name') }}" icon="music"
                                placeholder="Enter MySpace Name" value="{{ $user->userInfo?->myspace_name ?? '' }}"
                                :messages="$errors->get('myspace_name')" />
                        </div>

                        <!-- Discogs Name -->
                        <div class="space-y-2">
                            <x-inputs.input name="discogs_name" label="{{ __('Discogs Name') }}" icon="disc"
                                placeholder="Enter Discogs Name" value="{{ $user->userInfo?->discogs_name ?? '' }}"
                                :messages="$errors->get('discogs_name')" />
                        </div>

                        <!-- Website Title -->
                        <div class="space-y-2">
                            <x-inputs.input name="website_title" label="{{ __('Website Title') }}"
                                icon="text-align-left" placeholder="Enter Website Title"
                                value="{{ $user->userInfo?->website_title ?? '' }}" :messages="$errors->get('website_title')" />
                        </div>

                        <!-- Website -->
                        <div class="space-y-2">
                            <x-inputs.input name="website" label="{{ __('Website') }}" icon="link"
                                placeholder="Enter Website URL" value="{{ $user->userInfo?->website ?? '' }}"
                                :messages="$errors->get('website')" />
                        </div>



                        <!-- Local -->
                        <div class="space-y-2">
                            <x-inputs.input name="local" label="{{ __('Local') }}" icon="map-pin"
                                placeholder="Enter Local" value="{{ $user->userInfo?->local ?? '' }}"
                                :messages="$errors->get('local')" />

                        </div>
                        <div class="space-y-2 col-span-2">
                            <x-inputs.file name="image" label="{{ __('Image') }}"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg" :messages="$errors->get('image')" />

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
            <script src="{{ asset('assets/js/filepond.js') }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                        "#image": "{{ $user->modified_image }}"
                    });

                });
            </script>
        @endpush

    </section>
</x-admin::layout>
