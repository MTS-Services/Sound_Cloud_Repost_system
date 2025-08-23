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
                    <div class="grid grid-cols-1 2xl:grid-cols-9 gap-5">
                        <div class="2xl:col-span-6 grid grid-cols-1 gap-5 sm:grid-cols-2 h-fit">
                            <div class="space-y-2">
                                <x-inputs.input name="application_name" label="{{ __('Application Name') }}"
                                    placeholder="Enter Your Name" value="{{ old('application_name') }}" :messages="$errors->get('application_name')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.input name="application_short_name" label="{{ __('Application Short Name') }}"
                                    placeholder="Enter Application Short Name" value="{{ old('application_short_name') }}"
                                    :messages="$errors->get('application_short_name')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.select name="timezone" label="{{ __('Timezone') }}" :options="$timezones"
                                    value="{{ old('timezone') }}" :messages="$errors->get('timezone')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.select name="environment" label="{{ __('Environment') }}" :options="App\Models\ApplicationSetting::getEnvironmentInfos()"
                                    value="{{ old('environment') }}" :messages="$errors->get('environment')" />
                            </div>
                            <div class="space-y-2">
                                <x-inputs.select name="app_debug" label="{{ __('App Debug') }}" :options="App\Models\ApplicationSetting::getAppDebugInfos()"
                                    value="{{ old('app_debug') }}" :messages="$errors->get('app_debug')" />
                            </div>
                            <div class="space-y-2 sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="space-y-2">
                                    <x-inputs.select name="date_format" label="{{ __('Date Format') }}"
                                        :options="App\Models\ApplicationSetting::getDateFormatInfos()" value="{{ old('date_format') }}" :messages="$errors->get('date_format')" />
                                </div>
                                <div class="space-y-2">
                                    <x-inputs.select name="time_format" label="{{ __('Time Format') }}"
                                        :options="App\Models\ApplicationSetting::getTimeFormatInfos()" value="{{ old('time_format') }}" :messages="$errors->get('time_format')" />
                                </div>
                                <div class="space-y-2">
                                    <x-inputs.select name="theme_mode" label="{{ __('Default Theme Mode') }}"
                                        :options="App\Models\ApplicationSetting::getThemeModeInfos()" value="{{ old('theme_mode') }}" :messages="$errors->get('theme_mode')" />
                                </div>
                            </div>
                        </div>
                        <div class="2xl:col-span-3 grid grid-cols-1 gap-5 h-fit">
                            <div class="space-y-2">
                                <p class="label">{{ __('App Logo') }}<small>({{ __('Max: 400x400') }})</small></p>
                                <input type="file" name="app_logo" class="filepond" id="app_logo"
                                    accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                                <x-input-error class="mt-2" :messages="$errors->get('app_logo')" />
                            </div>
                            <div class="space-y-2">
                                <p class="label">{{ __('Favicon') }} <small>({{ __('16x16') }})</small></p>
                                <input type="file" name="favicon" class="filepond" id="favicon"
                                    accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                                <x-input-error class="mt-2" :messages="$errors->get('favicon')" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button>{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#favicon"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                    "#favicon": "{{ isset($general_settings['favicon']) ? asset('storage/' . $general_settings['favicon']) : null }}"
                });
                file_upload(["#app_logo"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                    "#app_logo": "{{ isset($general_settings['app_logo']) ? asset('storage/' . $general_settings['app_logo']) : null }}"
                });
            });
        </script>
    @endpush
</x-admin::layout>
