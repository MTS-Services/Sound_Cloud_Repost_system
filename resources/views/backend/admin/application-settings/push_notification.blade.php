<x-admin::layout>
    <x-slot name="title">{{ __('Push Notification Settings') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Push Notification Settings') }}</x-slot>
    <x-slot name="page_slug">app-push-notification-settings</x-slot>
    <section>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <h2 class="text-2xl font-bold mb-6">{{ __('Push Notification Settings') }}</h2>
                <form action="{{ route('app-settings.update-settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 h-fit items-end justify-center">
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="pusher_id" label="{{ __('Pusher App ID') }}"
                                placeholder="Enter pusher app id" value="{{ $push_nf_settings['pusher_id'] ?? '' }}"
                                :messages="$errors->get('pusher_id')" />
                        </div>
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="pusher_key" label="{{ __('Pusher App Key') }}"
                                placeholder="Enter pusher app key" value="{{ $push_nf_settings['pusher_key'] ?? '' }}"
                                :messages="$errors->get('pusher_key')" />
                        </div>
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="pusher_secret" label="{{ __('Pusher App Secret') }}"
                                placeholder="Enter pusher app secret"
                                value="{{ $push_nf_settings['pusher_secret'] ?? '' }}" :messages="$errors->get('pusher_secret')" />
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.select name="pusher_cluster" label="{{ __('Pusher Cluster') }}" :options="App\Models\ApplicationSetting::PUSHER_CLUSTERS"
                                selected="{{ $push_nf_settings['pusher_cluster'] ?? 'ap1' }}" :messages="$errors->get('pusher_cluster')" />
                        </div>
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="pusher_port" label="{{ __('Pusher Port') }}"
                                placeholder="Enter pusher port" value="{{ $push_nf_settings['pusher_port'] ?? '' }}"
                                :messages="$errors->get('pusher_port')" />
                        </div>
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="pusher_host" label="{{ __('Pusher Host (Optional)') }}"
                                placeholder="Enter pusher host" value="{{ $push_nf_settings['pusher_host'] ?? '' }}"
                                :messages="$errors->get('pusher_host')" />
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.select name="pusher_scheme" label="{{ __('Pusher Scheme') }}" :options="App\Models\ApplicationSetting::PUSHER_SCHEMES"
                                selected="{{ $push_nf_settings['pusher_scheme'] ?? 'ap1' }}" :messages="$errors->get('pusher_schema')" />
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.select name="pusher_encrypted" label="{{ __('Pusher Encrypted') }}"
                                :options="App\Models\ApplicationSetting::PUSHER_ENCRYPTIONS" selected="{{ $push_nf_settings['pusher_encrypted'] ?? 'ap1' }}"
                                :messages="$errors->get('pusher_schema')" />
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
