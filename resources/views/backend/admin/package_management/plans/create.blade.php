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
                            <x-inputs.input name="name" label="{{ __('Name') }}" placeholder="Enter plan name"
                                value="{{ old('name') }}" :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="slug" label="{{ __('Slug') }}" placeholder="Enter plan slug"
                                value="{{ old('slug') }}" :messages="$errors->get('slug')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly" label="{{ __('Price monthly') }}"
                                placeholder="Enter price monthly" value="{{ old('price_monthly') }}"
                                :messages="$errors->get('price_monthly')" />
                        </div>
                        <div class="space-y-2">
                            <x-inputs.input name="price_monthly_yearly" label="{{ __('Price monthly yearly') }}"
                                placeholder="Enter price monthly yearly" value="{{ old('price_monthly_yearly') }}"
                                :messages="$errors->get('price_monthly_yearly')" />
                        </div>

                        <div class="space-y-2">
                            <x-inputs.select name="tag" label="{{ __('Tag') }}" icon="shield"
                                placeholder="{{ __('Select a Tag') }}" :options="App\Models\plan::getTags()" :selected="old('tag')"
                                :messages="$errors->get('tag')" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <x-inputs.textarea name="notes" label="{{ __('Note') }}" placeholder="Enter note"
                                value="{{ old('notes') }}" :messages="$errors->get('notes')" />
                        </div>
                    </div>
                    <div class="space-y-2 sm:col-span-2" id="featureWrapper">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 items-center mt-3 feature-group">
                            <div class="space-y-2">
                                <x-inputs.select name="features[][feature]" label="{{ __('Features') }}" icon="shield"
                                    data-id="featureSelect" placeholder="{{ __('Select a Feature') }}"
                                    :options="$features->pluck('name', 'id')->toArray()" :selected="old('feature')" :messages="$errors->get('tag')" />
                            </div>
                            <div class="flex items-center gap-3 justify-between">
                                @if ($features->first()->type === App\Models\Feature::TYPE_STRING)
                                    <div class="space-y-2 w-full value-wrapper">
                                        <x-inputs.input name="values[][value]" label="{{ __('Value') }}"
                                            class="w-96" disabled placeholder="Enter value"
                                            value="{{ old('value') }}" :messages="$errors->get('value')" />
                                    </div>
                                @else
                                    <div class="space-y-2 w-full flex items-center gap-2 mt-8 value-wrapper">
                                        <input name="values[][value]" type="checkbox" value="1"
                                            class="!checkbox !checkbox-sm bg-transparent checkbox-accent my-5">
                                        <label for="values[][value]"
                                            class="label mb-2">{{ __('Please check the checkbox') }}</label>
                                    </div>
                                @endif
                                <x-button :button="true" :isSubmit="false" class="add-btn mt-6" data-id="addNewBtn"
                                    icon="">{{ __('Add new') }}</x-button>
                            </div>
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

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                function updateFieldState(group) {
                    const select = group.find('[data-id="featureSelect"]');
                    const valueInput = group.find('input');
                    const button = group.find('[data-id="addNewBtn"]');

                    // Enable input only if feature is selected
                    if (select.val()) {
                        valueInput.prop('disabled', false);
                    } else {
                        valueInput.prop('disabled', true);
                        valueInput.val('');
                    }
                    console.log(valueInput.val());

                    // Enable button only if both selected and input filled and feature 1 selected
                    if (select.val() && valueInput.val()) {
                        button.prop('disabled', false);
                    } else {
                        button.prop('disabled', true);
                    }
                }

                function refreshAllFeatureOptions() {
                    const selectedValues = $('select').map(function() {
                        return $(this).val();
                    }).get().filter(Boolean);

                    $('select').each(function() {
                        const currentSelect = $(this);
                        const currentVal = currentSelect.val();

                        currentSelect.find('option').each(function() {
                            const val = $(this).val();
                            if (val && val !== currentVal && selectedValues.includes(val)) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            }
                        });
                    });
                }

                // Initial state
                $('.feature-group').each(function() {
                    updateFieldState($(this));
                    refreshAllFeatureOptions();
                });

                // On change of select or input
                $(document).on('change input', 'select, input', function() {
                    const group = $(this).closest('.feature-group');
                    updateFieldState(group);
                    refreshAllFeatureOptions();
                });

                // Add new
                $(document).on('click', '.add-btn', function() {
                    const parentGroup = $(this).closest('.feature-group');

                    $(this)
                        .removeClass('add-btn btn-primary')
                        .addClass('remove-btn btn-secondary')
                        .text('REMOVE');

                    const newGroup = parentGroup.clone();

                    newGroup.find('select').val('');
                    newGroup.find('input').val('').prop('disabled', true);
                    newGroup.find('[data-id="addNewBtn"]')
                        .removeClass('remove-btn btn-secondary')
                        .addClass('add-btn btn-primary')
                        .prop('disabled', true)
                        .text('Add new');

                    $('#featureWrapper').append(newGroup);

                    updateFieldState(newGroup);
                    refreshAllFeatureOptions();
                });

                // Remove
                $(document).on('click', '.remove-btn', function() {
                    $(this).closest('.feature-group').remove();
                    refreshAllFeatureOptions();
                });
            });
        </script>


        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                function updateFieldState(group) {
                    const select = group.find('select');
                    const valueInput = group.find('input');
                    const button = group.find('[data-id="addNewBtn"]');

                    // Enable input only if feature is selected
                    if (select.val()) {
                        valueInput.prop('disabled', false);
                    } else {
                        valueInput.prop('disabled', true);
                        valueInput.val(''); // Clear the value if no feature selected
                    }

                    // Enable button only if both selected and input filled
                    if (select.val() && valueInput.val()) {
                        button.prop('disabled', false);
                    } else {
                        button.prop('disabled', true);
                    }
                }

                function refreshAllFeatureOptions() {
                    const selectedValues = $('select').map(function() {
                        return $(this).val();
                    }).get().filter(Boolean);

                    $('select').each(function() {
                        const currentSelect = $(this);
                        const currentVal = currentSelect.val();

                        currentSelect.find('option').each(function() {
                            const val = $(this).val();
                            if (val && val !== currentVal && selectedValues.includes(val)) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });
                    });
                }

                // Initial state
                $('.feature-group').each(function() {
                    updateFieldState($(this));
                    refreshAllFeatureOptions();
                });

                // On change of select or input
                $(document).on('change input', 'select, input', function() {
                    const group = $(this).closest('.feature-group');
                    updateFieldState(group);
                    refreshAllFeatureOptions();
                });

                // Add new
                $(document).on('click', '.add-btn', function() {
                    const parentGroup = $(this).closest('.feature-group');

                    $(this)
                        .removeClass('add-btn btn-primary')
                        .addClass('remove-btn btn-secondary')
                        .text('REMOVE');

                    const newGroup = parentGroup.clone();

                    newGroup.find('select').val('');
                    newGroup.find('input').val('').prop('disabled', true);
                    newGroup.find('[data-id="addNewBtn"]')
                        .removeClass('remove-btn btn-secondary')
                        .addClass('add-btn btn-primary')
                        .prop('disabled', true)
                        .text('Add new');

                    $('#featureWrapper').append(newGroup);

                    updateFieldState(newGroup);
                    refreshAllFeatureOptions();
                });

                // Remove
                $(document).on('click', '.remove-btn', function() {
                    $(this).closest('.feature-group').remove();
                    refreshAllFeatureOptions();
                });
            });
        </script> --}}



        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Initially disable value input + add button
                $('.feature-group').each(function() {
                    const select = $(this).find('select');
                    const valueInput = $(this).find('input');
                    const button = $(this).find('[data-id="addNewBtn"]');

                    if (!select.val()) {
                        valueInput.prop('disabled', true);
                        button.prop('disabled', true);
                    }
                });

                // On feature select change
                $(document).on('change', 'select', function() {
                    const group = $(this).closest('.feature-group');
                    const valueInput = group.find('input');
                    const button = group.find('[data-id="addNewBtn"]');

                    if ($(this).val()) {
                        valueInput.prop('disabled', false);
                        button.prop('disabled', false);
                    } else {
                        valueInput.prop('disabled', true);
                        button.prop('disabled', true);
                    }
                });

                // Add new row
                $(document).on('click', '.add-btn', function() {
                    const parentGroup = $(this).closest('.feature-group');

                    // Change current to REMOVE
                    $(this)
                        .removeClass('add-btn btn-primary')
                        .addClass('remove-btn btn-secondary')
                        .text('REMOVE');

                    // Clone new row
                    const newGroup = parentGroup.clone();

                    // Clear values
                    newGroup.find('select').val('');
                    newGroup.find('input').val('').prop('disabled', true);
                    newGroup.find('[data-id="addNewBtn"]')
                        .removeClass('remove-btn btn-secondary')
                        .addClass('add-btn btn-primary')
                        .prop('disabled', true)
                        .text('Add new');

                    $('#featureWrapper').append(newGroup);
                });

                // Remove row
                $(document).on('click', '.remove-btn', function() {
                    $(this).closest('.feature-group').remove();
                });
            });
        </script> --}}


        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Initial state: hide value input + button if not selected
                $('.feature-group').each(function() {
                    const select = $(this).find('select');
                    const valueInputWrapper = $(this).find('.value-wrapper');
                    const button = $(this).find('[data-id="addNewBtn"]');

                    if (!select.val()) {
                        valueInputWrapper.hide();
                        button.hide();
                    }
                });

                // Toggle value input and button on feature select
                $(document).on('change', 'select', function() {
                    const group = $(this).closest('.feature-group');
                    const valueInputWrapper = group.find('.value-wrapper');
                    const button = group.find('[data-id="addNewBtn"]');

                    if ($(this).val()) {
                        valueInputWrapper.show();
                        button.show();
                    } else {
                        valueInputWrapper.hide();
                        button.hide();
                    }
                });

                // Add new row
                $(document).on('click', '.add-btn', function() {
                    const parentGroup = $(this).closest('.feature-group');

                    // Turn current to "REMOVE"
                    $(this)
                        .removeClass('add-btn')
                        .addClass('remove-btn btn-secondary')
                        .removeClass('btn-primary')
                        .text('REMOVE');

                    // Clone group
                    const newGroup = parentGroup.clone();

                    // Clear values
                    newGroup.find('select').val('');
                    newGroup.find('input').val('');
                    newGroup.find('[data-id="addNewBtn"]')
                        .removeClass('remove-btn btn-secondary')
                        .addClass('add-btn btn-primary')
                        .text('Add new');

                    // Hide value/button until feature selected
                    newGroup.find('.value-wrapper').hide();
                    newGroup.find('[data-id="addNewBtn"]').hide();

                    $('#featureWrapper').append(newGroup);
                });

                // Remove row
                $(document).on('click', '.remove-btn', function() {
                    $(this).closest('.feature-group').remove();
                });
            });
        </script> --}}


        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                $(document).on('click', '.add-btn', function() {
                    const parentGroup = $(this).closest('.feature-group');

                    // Change current "Add" button to "Remove"
                    $(this)
                        .removeClass('add-btn')
                        .removeClass('btn-primary')
                        .addClass('btn-secondary remove-btn')
                        .text('REMOVE');

                    // Clone the group
                    const newGroup = parentGroup.clone();

                    // Clear inputs in the clone
                    newGroup.find('select').val('');
                    newGroup.find('input').val('');
                    newGroup.find('[data-id="addNewBtn"]')
                        .removeClass('btn-secondary remove-btn')
                        .addClass('btn-primary')
                        .addClass('add-btn')
                        .text('Add new');

                    // Append clone
                    $('#featureWrapper').append(newGroup);
                });

                // Handle remove button
                $(document).on('click', '.remove-btn', function() {
                    $(this).closest('.feature-group').remove();
                });
            });
        </script> --}}
    @endpush
</x-admin::layout>
