<x-admin::layout>
    <x-slot name="title">{{ __('Email Settings') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Email Settings') }}</x-slot>
    <x-slot name="page_slug">app-smtp-settings</x-slot>
    <section>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('app-settings.update-settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 h-fit">
                        <div class="space-y-2">
                            <x-inputs.select name="smtp_driver" label="{{ __('Mail Driver') }}" :options="App\Models\ApplicationSetting::getSmtpDriverInfos()"
                                selected="{{ $smtp_settings['smtp_driver'] ?? 'smtp' }}" :messages="$errors->get('smtp_driver')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="smtp_host" label="{{ __('Mailer Host') }}" placeholder="Mailer Host"
                                value="{{ $smtp_settings['smtp_host'] ?? '' }}" :messages="$errors->get('smtp_host')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Mailer Port') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Mailer Port"
                                    value="{{ $smtp_settings['smtp_port'] ?? '' }}" name="smtp_port" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_port')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="smtp_username" label="{{ __('Mail username') }}"
                                placeholder="Enter Username" value="{{ $smtp_settings['smtp_username'] ?? '' }}"
                                :messages="$errors->get('smtp_username')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="smtp_password" label="{{ __('Mail password') }}" type="password"
                                placeholder="Enter Password" :messages="$errors->get('smtp_password')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.select name="smtp_encryption" label="{{ __('Mail Encryption') }}"
                                :options="App\Models\ApplicationSetting::getSmtpEncryptionInfos()" selected="{{ $smtp_settings['smtp_encryption'] ?? 'tls' }}"
                                :messages="$errors->get('smtp_encryption')" />
                        </div>

                        <div class="space-y-2">f
                            <x-inputs.input name="smtp_from_address" label="{{ __('Mail from address') }}"
                                placeholder="Mail from address" value="{{ $smtp_settings['smtp_from_address'] ?? '' }}"
                                :messages="$errors->get('smtp_from_address')"></x-admin.inputs.input>
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="smtp_from_name" label="{{ __('Mail from name') }}"
                                placeholder="Mail from name" value="{{ $smtp_settings['smtp_from_name'] ?? '' }}"
                                :messages="$errors->get('smtp_from_name')"></x-admin.inputs.input>
                        </div>

                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-admin::layout>
