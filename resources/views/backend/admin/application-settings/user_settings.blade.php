<x-admin::layout>
    <x-slot name="title">{{ __('Bonus Settings') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Bonus Settings') }}</x-slot>
    <x-slot name="page_slug">user-settings</x-slot>
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('app-settings.update-settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="2xl:col-span-6 grid grid-cols-1 gap-5 sm:grid-cols-2 h-fit">
                            <div class="space-y-2">
                                <x-inputs.input name="login_bonus" label="{{ __('First Login Bonus Credits') }}"
                                    placeholder="Enter First Login Bonus Credits"
                                    value="{{ $user_settings['login_bonus'] ?? old('login_bonus') }}"
                                    :messages="$errors->get('login_bonus')" />
                                {{-- Message --}}
                                <p>{{ __('You can remove this option if you want to set the first login bonus to') }} <span class="text-primary font-bold">0</span>.</p>
                            </div>

                        </div>
                    </div>
                    <div class="flex justify-start mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-admin::layout>
