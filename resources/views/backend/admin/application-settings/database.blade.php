<x-admin::layout>
    <x-slot name="title">{{ __('Database Settings') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Database Settings') }}</x-slot>
    <x-slot name="page_slug">app-database-settings</x-slot>
    <section>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('app-settings.update-settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 h-fit">
                        <div class="space-y-2">
                            <p class="label">{{ __('Database Driver') }}</p>
                            <select name="database_driver" class="select">
                                <option value="" selected hidden>{{ __('Select database driver') }}</option>
                                @foreach (App\Models\ApplicationSetting::getDatabaseDriverInfos() as $key => $info)
                                    <option value="{{ $key }}"
                                        @if (isset($database_settings['database_driver']) && $database_settings['database_driver'] == $key) selected @endif>
                                        {{ $info }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('database_driver')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Database Host') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Database Host"
                                    value="{{ $database_settings['database_host'] ?? '' }}" name="database_host"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('database_host')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Database Port') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Database Port"
                                    value="{{ $database_settings['database_port'] ?? '' }}" name="database_port"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('database_port')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Database Name') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Database Name"
                                    value="{{ $database_settings['database_name'] ?? '' }}" name="database_name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('database_name')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Database Username') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="text" placeholder="Database Username"
                                    value="{{ $database_settings['database_username'] ?? '' }}"
                                    name="database_username" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('database_username')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Database Password') }}</p>
                            <label class="input flex items-center px-2">
                                <input type="password" placeholder="Database Password"
                                    value="{{ $database_settings['database_password'] ?? '' }}"
                                    name="database_password" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('database_password')" />
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
