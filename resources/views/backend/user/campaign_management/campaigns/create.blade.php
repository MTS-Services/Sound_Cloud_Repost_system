<x-user::layout>
    <x-slot name="title">{{ __('Create Campaign') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Campaign') }}</x-slot>
    <x-slot name="page_slug">campains</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Campaign') }}</h2>
                <x-button href="{{ route('user.cm.campaigns.index') }}" icon="undo-2" type='info' permission="campaign-list">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('user.cm.campaigns.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        {{-- hidden fields id --}}
                        <input type="hidden" name="music_id" value="{{ $track->id }}">
                        <input type="hidden" name="music_type" value="Track">
                        <div class="space-y-2">
                            <x-inputs.input name="title" label="Title" placeholder="Enter Campaign Title"
                                value="{{ old('title') }}" :messages="$errors->get('title')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="target_reposts" label="Target Reposts"
                                placeholder="Enter Target Reposts" value="{{ old('target_reposts') }}"
                                :messages="$errors->get('target_reposts')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="credits_per_repost" label="Credits Per Repost"
                                placeholder="Enter Credits Per Repost" value="{{ old('credits_per_repost') }}"
                                :messages="$errors->get('credits_per_repost')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input name="total_credits_budget" label="Total Credits Budget"
                                placeholder="Enter Total Budget" value="{{ old('total_credits_budget') }}"
                                :messages="$errors->get('total_credits_budget')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.input type="date" name="start_date" label="Start Date" value="{{ old('start_date') }}"
                                :messages="$errors->get('start_date')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.select name="auto_approve" label="Auto Approve" :options="[
                                \App\Models\Campaign::AUTO_APPROVE_YES => 'Yes',
                                \App\Models\Campaign::AUTO_APPROVE_NO => 'No',
                            ]"
                                :messages="$errors->get('auto_approve')" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.textarea name="description" label="Description" placeholder="Enter Description"
                                value="{{ old('description') }}" :messages="$errors->get('description')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-button type="accent" :button="true" icon="save">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
</x-user::layout>
