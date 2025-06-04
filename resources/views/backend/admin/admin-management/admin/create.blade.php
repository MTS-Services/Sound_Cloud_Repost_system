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
                <x-admin.primary-link href="{{ route('am.admin.index') }}">{{ __('Back') }} </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Name') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </g>
                                </svg>
                                <input type="text" placeholder="Name" value="{{ old('name') }}" name="name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Email') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="example@gmail.com" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Password') }}</p>
                            <label class="input relative flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path
                                            d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z">
                                        </path>
                                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                    </g>
                                </svg>
                                <input :type="$store.password.showPassword ? 'text' : 'password'" name="password"
                                    placeholder="Password" class="flex-1" />
                                <button type="button"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4"></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />

                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Confirm Password') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path
                                            d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z">
                                        </path>
                                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                    </g>
                                </svg>
                                <input :type="$store.password.showPassword ? 'text' : 'password'"
                                    name="password_confirmation" placeholder="Confirm Password" class="flex-1" />
                                <button type="button"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4"></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Role') }}</p>
                            <select name="role" class="input select select2">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                        </div>
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Image') }}</p>
                            <input type="file" name="image" class="filepond" id="image"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
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
    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"]);
            });
        </script>
    @endpush
</x-admin::layout>
