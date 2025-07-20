<x-admin::layout>
    <x-slot name="title">{{ __('Update Plan') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Update Plan') }}</x-slot>
    <x-slot name="page_slug">plan</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Update Plan') }}</h2>
                <x-button href="{{ route('pm.plan.index') }}" icon="undo-2" type='info' permission="plan-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">

            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('pm.plan.update', encrypt($plan->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter plan name"
                                value="{{ $plan->name }}" :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="slug" label="{{ __('Slug') }}" placeholder="Enter plan slug"
                                value="{{ $plan->slug }}" :messages="$errors->get('slug')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly" label="{{ __('Price monthly') }}"
                                placeholder="Enter price monthly" value="{{ $plan->price_monthly }}"
                                :messages="$errors->get('price_monthly')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly_yearly" label="{{ __('Price monthly yearly') }}"
                                placeholder="Enter price monthly yearly" value="{{ $plan->price_monthly_yearly }}"
                                :messages="$errors->get('price_monthly_yearly')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.select name="tag" label="{{ __('Tag') }}" icon="shield"
                                placeholder="{{ __('Select a Tag') }}" :options="$plan->getTags()" :selected="$plan->tag"
                                :messages="$errors->get('tag')" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.textarea name="notes" label="{{ __('Note') }}" placeholder="Enter note"
                                value="{{ $plan->notes }}" :messages="$errors->get('notes')" />
                        </div>

                    </div>

                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>


    </section>
</x-admin::layout>
