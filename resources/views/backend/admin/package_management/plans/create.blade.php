<x-admin::layout>
    <x-slot name="title">{{ __('Create plan') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create plan') }}</x-slot>
    <x-slot name="page_slug">plan</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create plan') }}</h2>
                <x-button href="{{ route('pm.plan.index') }}" icon="undo-2" type='info' permission="plan-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.plan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter Plan Name"
                                value="{{ old('name') }}" :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="slug" label="{{ __('Slug') }}" placeholder="Enter plan slug"
                                value="{{ old('slug') }}" :messages="$errors->get('slug')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly" label="{{ __('Monthly Price') }}"
                                placeholder="Enter Price Monthly" value="{{ old('price_monthly') }}"
                                :messages="$errors->get('price_monthly')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly_yearly" label="{{ __('Yearly Price') }}"
                                placeholder="Enter Price Monthly Yearly" value="{{ old('price_monthly_yearly') }}"
                                :messages="$errors->get('price_monthly_yearly')" />
                        </div>
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <x-inputs.textarea name="notes" label="{{ __('Notes') }}" placeholder="Enter notes"
                            value="{{ old('notes') }}" :messages="$errors->get('notes')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
    @push('js')
   
    @endpush
</x-admin::layout>
