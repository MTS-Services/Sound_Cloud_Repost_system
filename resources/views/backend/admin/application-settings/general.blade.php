<x-admin::layout>
    <x-slot name="title">{{ __('General Settings') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('General Settings') }}</x-slot>
    <x-slot name="page_slug">app-general-settings</x-slot>
    <section>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('app-settings.update-settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="2xl:col-span-6 grid grid-cols-1 gap-5 sm:grid-cols-2 h-fit">
                            <div class="space-y-2">
                                <x-inputs.input name="app_name" label="{{ __('Application Name') }}"
                                    placeholder="Enter Your Name"
                                    value="{{ $general_settings['app_name'] ?? old('app_name') }}"
                                    :messages="$errors->get('app_name')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.input name="application_short_name" label="{{ __('Application Short Name') }}"
                                    placeholder="Enter Application Short Name"
                                    value="{{ $general_settings['application_short_name'] ?? old('application_short_name') }}"
                                    :messages="$errors->get('application_short_name')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.select name="timezone" label="{{ __('Timezone') }}" :options="$timezones"
                                    selected="{{ $general_settings['timezone'] ?? 'UTC' }}" :messages="$errors->get('timezone')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.select name="environment" label="{{ __('Environment') }}" :options="App\Models\ApplicationSetting::getEnvironmentInfos()"
                                    selected="{{ $general_settings['environment'] ?? 'local' }}" :messages="$errors->get('environment')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.select name="app_debug" label="{{ __('App Debug') }}" :options="App\Models\ApplicationSetting::getAppDebugInfos()"
                                    selected="{{ $general_settings['app_debug'] ?? 'false' }}" :messages="$errors->get('app_debug')" />
                            </div>
                            <div class="space-y-2 sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="space-y-2">
                                    <x-inputs.select name="date_format" label="{{ __('Date Format') }}"
                                        :options="App\Models\ApplicationSetting::getDateFormatInfos()" selected="{{ $general_settings['date_format'] ?? 'YYYY-MM-DD' }}"
                                        :messages="$errors->get('date_format')" />
                                </div>
                                <div class="space-y-2">
                                    <x-inputs.select name="time_format" label="{{ __('Time Format') }}"
                                        :options="App\Models\ApplicationSetting::getTimeFormatInfos()" selected="{{ $general_settings['time_format'] ?? 'HH:mm:ss' }}"
                                        :messages="$errors->get('time_format')" />
                                </div>
                                <div class="space-y-2">
                                    <x-inputs.select name="theme_mode" label="{{ __('Default Theme Mode') }}"
                                        :options="App\Models\ApplicationSetting::getThemeModeInfos()" selected="{{ $general_settings['theme_mode'] ?? 'System' }}"
                                        :messages="$errors->get('theme_mode')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-5 h-fit mt-4">
                        <div class="w-full space-y-2">
                            <p class="label">{{ __('App Logo') }}<small>({{ __('Max: 400x400') }})</small></p>
                            <input type="file" name="app_logo" class="filepond" id="app_logo"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                            <x-input-error class="mt-2" :messages="$errors->get('app_logo')" />
                        </div>
                        <div class="w-full space-y-2">
                            <p class="label">{{ __('Favicon') }} <small>({{ __('16x16') }})</small></p>
                            <input type="file" name="favicon" class="filepond" id="f_icon"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                            <x-input-error class="mt-2" :messages="$errors->get('favicon')" />
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-5 h-fit mt-4">
                        <div class="w-full space-y-2">
                            <p class="label">{{ __('App Dark Mode Logo') }}<small>({{ __('Max: 400x400') }})</small>
                            </p>
                            <input type="file" name="app_logo_dark" class="filepond" id="app_logo_dark"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                            <x-input-error class="mt-2" :messages="$errors->get('app_logo_dark')" />
                        </div>
                        <div class="w-full space-y-2">
                            <p class="label">{{ __('Dark Mode Favicon') }} <small>({{ __('16x16') }})</small></p>
                            <input type="file" name="favicon_dark" class="filepond" id="favicon_dark"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml">
                            <x-input-error class="mt-2" :messages="$errors->get('favicon_dark')" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#f_icon"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg+xml"], {
                    "#f_icon": "{{ isset($general_settings['favicon']) ? asset('storage/' . $general_settings['favicon']) : null }}"
                });
                file_upload(["#app_logo"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg+xml"], {
                    "#app_logo": "{{ isset($general_settings['app_logo']) ? asset('storage/' . $general_settings['app_logo']) : null }}"
                });
                file_upload(["#favicon_dark"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg+xml"], {
                    "#favicon_dark": "{{ isset($general_settings['favicon_dark']) ? asset('storage/' . $general_settings['favicon_dark']) : null }}"
                });
                file_upload(["#app_logo_dark"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg+xml"], {
                    "#app_logo_dark": "{{ isset($general_settings['app_logo_dark']) ? asset('storage/' . $general_settings['app_logo_dark']) : null }}"
                });
            });
        </script>
    @endpush
</x-admin::layout>
