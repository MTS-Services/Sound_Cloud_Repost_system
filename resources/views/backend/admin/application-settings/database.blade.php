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
                            <x-inputs.select name="database_driver" label="{{ __('Database Driver') }}"
                                :options="App\Models\ApplicationSetting::getDatabaseDriverInfos()"
                                selected="{{ $database_settings['database_driver'] ?? '' }}"
                                :messages="$errors->get('database_driver')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="database_host" label="{{ __('Database Host') }}"
                                placeholder="Database Host" value="{{ $database_settings['database_host'] ?? '' }}"
                                :messages="$errors->get('database_host')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="database_port" label="{{ __('Database Port') }}"
                                placeholder="Database Port" value="{{ $database_settings['database_port'] ?? '' }}"
                                :messages="$errors->get('database_port')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="database_name" label="{{ __('Database Name') }}"
                                placeholder="Database Name" value="{{ $database_settings['database_name'] ?? '' }}"
                                :messages="$errors->get('database_name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="database_username" label="{{ __('Database Username') }}"
                                placeholder="Enter Username"
                                value="{{ $database_settings['database_username'] ?? '' }}" :messages="$errors->get('database_username')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="database_password" label="{{ __('Database Password') }}"
                                type="password" placeholder="Enter Password" :messages="$errors->get('database_password')" />
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
