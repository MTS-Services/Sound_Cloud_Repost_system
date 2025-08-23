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
                            <p class="label">{{ __('Mailer') }}</p>
                            <select name="smtp_driver" class="select">
                                <option value="" selected hidden>{{ __('Select mailer driver') }}</option>
                                @foreach (App\Models\ApplicationSetting::getSmtpDriverInfos() as $key => $info)
                                    <option value="{{ $key }}"
                                        @if (isset($smtp_settings['smtp_driver']) && $smtp_settings['smtp_driver'] == $key) selected @endif>
                                        {{ $info }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_driver')" />
                        </div>

                        <div class="space-y-2">
                            <p class="label">{{ __('Mailer Host') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Mailer Host"
                                    value="{{ $smtp_settings['smtp_host'] ?? '' }}" name="smtp_host"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_host')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Mailer Port') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Mailer Port"
                                    value="{{ $smtp_settings['smtp_port'] ?? '' }}" name="smtp_port"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_port')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Mail username') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Mail username"
                                    value="{{ $smtp_settings['smtp_username'] ?? '' }}" name="smtp_username"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_username')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Mail password') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="password" placeholder="Mail password"
                                    value="{{ $smtp_settings['smtp_password'] ?? '' }}" name="smtp_password"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_password')" />
                        </div>

                        <div class="space-y-2">
                            <p class="label">{{ __('Mail Encryption') }}</p>
                            <select name="smtp_encryption" class="select">
                                <option value="" selected hidden>{{ __('Select mailer encryption') }}</option>
                                @foreach (App\Models\ApplicationSetting::getSmtpEncryptionInfos() as $key => $info)
                                    <option value="{{ $key }}"
                                        @if (isset($smtp_settings['smtp_encryption']) && $smtp_settings['smtp_encryption'] == $key) selected @endif>
                                        {{ $info }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_encryption')" />
                        </div>

                        <div class="space-y-2">
                            <p class="label">{{ __('Mail from address') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Mail from address"
                                    value="{{ $smtp_settings['smtp_from_address'] ?? '' }}" name="smtp_from_address"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_from_address')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Mail from name') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Mail from name"
                                    value="{{ $smtp_settings['smtp_from_name'] ?? '' }}" name="smtp_from_name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('smtp_from_name')" />
                        </div>

                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button>{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-admin::layout>
