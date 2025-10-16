<x-admin::layout>
    <x-slot name="title">{{ __('Payment Gateway Setup') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Payment Gateway Setup') }}</x-slot>
    <x-slot name="page_slug">app-payment-gateway-setup</x-slot>
    <section>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <h2 class="text-2xl font-bold mb-6">{{ __('Stripe Gateway Setup') }}</h2>
                <form action="{{ route('app-settings.update-settings') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 h-fit items-end justify-center"
                        x-data="{ mode: '{{ $payment_settings['stripe_mode'] ?? 'sandbox' }}' }">
                        <div class="space-y-2 sm:col-span-1">
                            <x-inputs.select name="stripe_mode" label="{{ __('Stripe Mode') }}" :options="App\Models\ApplicationSetting::PAYMENT_GATEWAY_MODES"
                                :event="'x-on:change=mode=$event.target.value'" selected="{{ $payment_settings['stripe_mode'] ?? 'sandbox' }}"
                                :messages="$errors->get('stripe_mode')" />
                        </div>
                        <div class="space-y-1 sm:col-span-3 glass-card rounded-2xl py-2 px-4 overflow-auto"
                            x-show="mode==='sandbox'">

                            <p class="text-sm">{{ __('Stripe Key:') }}
                                <small>{{ __('pk_test_51RpQw7ISIl8QzoFfXw1fwhUTinGRkt0zak7gkeNkOLlcr8MUPKKGh7mK7yJVideMMfVXSOSqfT5pZCrWlYOjE5dh008ykGM5Yx') }}</small>
                            </p>
                            <p class="text-sm m-0 p-0">{{ __('Stripe Secret:') }}
                                <small>{{ __('sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx') }}</small>
                            </p>

                        </div>
                        <div class="space-y-1 sm:col-span-3 glass-card rounded-2xl py-2 px-4 overflow-auto"
                            x-show="mode==='live'">

                            <p>{{ __('Enter your stripe live client id and secret key') }}</p>

                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.input name="stripe_key" label="{{ __('Stripe Key') }}"
                                placeholder="Enter stripe key" value="{{ $payment_settings['stripe_key'] ?? '' }}"
                                :messages="$errors->get('stripe_key')" />
                        </div>
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="stripe_secret" label="{{ __('Stripe Secret') }}"
                                placeholder="Enter stripe secret"
                                value="{{ $payment_settings['stripe_secret'] ?? '' }}" :messages="$errors->get('stripe_secret')" />
                        </div>
                        <div class="space-y-2 sm:col-span-1">
                            <x-inputs.select name="stripe_gateway_status" label="{{ __('Active') }}"
                                :options="App\Models\ApplicationSetting::PAYMENT_GATEWAY_STATUSES"
                                selected="{{ $payment_settings['stripe_gateway_status'] ?? 'active' }}"
                                :messages="$errors->get('stripe_gateway_status')" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Save') }}</x-button>
                    </div>
                </form>
            </div>
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <h2 class="text-2xl font-bold mb-6">{{ __('Paypal Gateway Setup') }}</h2>
                <form action="{{ route('app-settings.update-settings') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 h-fit items-end justify-center"
                        x-data="{ mode: '{{ $payment_settings['paypal_mode'] ?? 'sandbox' }}' }">
                        <div class="space-y-2 sm:col-span-1">
                            <x-inputs.select name="paypal_mode" label="{{ __('Paypal Mode') }}" :options="App\Models\ApplicationSetting::PAYMENT_GATEWAY_MODES"
                                :event="'x-on:change=mode=$event.target.value'" selected="{{ $payment_settings['paypal_mode'] ?? 'sandbox' }}"
                                :messages="$errors->get('paypal_mode')" />
                        </div>
                        <div class="space-y-1 sm:col-span-3 glass-card rounded-2xl py-2 px-4 overflow-auto"
                            x-show="mode==='sandbox'">

                            <p class="text-sm">{{ __('Paypal Key:') }}
                                <small>{{ __('AdUa_Fvt0tf9rYbd1412hS_ChPoSbTP9fGj1PblIXwwOsBzLTyD8I2xnRDmT6eNgdBRMtiAAl9yVYYjW') }}</small>
                            </p>
                            <p class="text-sm m-0 p-0">{{ __('Paypal Secret:') }}
                                <small>{{ __('ELmbYAx_lItW-Ic1loIHQq7PmXVY2OkwbBTJKQq-GJ58n8WcLn5awnRhN_v9tJP58ULO3hSvzmQ2jDEh') }}</small>
                            </p>

                        </div>
                        <div class="space-y-1 sm:col-span-3 glass-card rounded-2xl py-2 px-4 overflow-auto"
                            x-show="mode==='live'">

                            <p>{{ __('Enter your paypal live client id and secret key') }}</p>

                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.input name="paypal_key" label="{{ __('Paypal Key') }}"
                                placeholder="Enter paypal key" value="{{ $payment_settings['paypal_key'] ?? '' }}"
                                :messages="$errors->get('paypal_key')" />
                        </div>
                        <div class="space-y-2  sm:col-span-2">
                            <x-inputs.input name="paypal_secret" label="{{ __('Paypal Secret') }}"
                                placeholder="Enter paypal secret"
                                value="{{ $payment_settings['paypal_secret'] ?? '' }}" :messages="$errors->get('paypal_secret')" />
                        </div>
                        {{-- @dd($payment_settings); --}}
                        <div class="space-y-2 sm:col-span-1">
                            <x-inputs.select name="paypal_gateway_status" label="{{ __('Active') }}"
                                :options="App\Models\ApplicationSetting::PAYMENT_GATEWAY_STATUSES"
                                selected="{{ $payment_settings['paypal_gateway_status'] ?? 'active' }}"
                                :messages="$errors->get('paypal_gateway_status')" />
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
