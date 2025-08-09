<x-admin::layout>
    <x-slot name="title">{{ __('Campaign List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Campaign List') }}</x-slot>
    <x-slot name="page_slug">campaign</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Campaign List') }}</h2>
                {{-- <div class="flex items-center gap-2">
                    <x-button href="{{ route('cm.campaign.trash') }}" icon="trash-2" type='secondary'
                        permission="campaign-trash">
                        {{ __('Trash') }}
                    </x-button>
                    <x-button href="{{ route('cm.campaign.create') }}" icon="user-plus" permission="campaign-create">
                        {{ __('Add') }}
                    </x-button>
                </div> --}}
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Target Reposts') }}</th>
                        <th>{{ __('Total Credits Budget') }}</th>
                        <th>{{ __('Cost Per Repost') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Details Modal --}}
    <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['title', true, true],
                    ['user_urn', true, true],
                    ['target_reposts', true, true],
                    ['budget_credits', true, true],
                    ['cost_per_repost', true, true],
                    ['status', true, true],
                    ['start_date', true, true],
                    ['end_date', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('cm.campaign.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5],
                    model: 'Campaign',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('cm.campaign.show', ':id') }}";

                    const details = [{
                        // 'user_urn',
        // 'music_id',
        // 'music_type',
        // 'title',
        // 'description',
        // 'target_reposts',
        // 'completed_reposts',
        // 'credits_per_repost',
        // 'total_credits_budget',
        // 'credits_spent',
        // 'min_followers_required',
        // 'max_followers_limit',
        // 'status',
        // 'start_date',
        // 'end_date',
        // 'auto_approve'
                            label: '{{ __('Title') }}',
                            key: 'title',
                        },
                        {
                            label: '{{ __('User') }}',
                            key: 'user_urn',
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'description',
                        },
                        {
                            label: '{{ __('Target Reposts') }}',
                            key: 'target_reposts',
                        },
                        {
                            label: '{{ __('Completed Reposts') }}',
                            key: 'completed_reposts',
                        },
                        {
                            label: '{{ __('Credits Per Repost') }}',
                            key: 'credits_per_repost',
                        },
                        {
                            label: '{{ __('Credits Spent') }}',
                            key: 'credits_spent',
                        },
                        {
                            label: '{{ __('Min Followers Required') }}',
                            key: 'min_followers_required',
                        },
                        {
                            label: '{{ __('Max Followers Limit') }}',
                            key: 'max_followers_limit',
                        },
                        {
                            label: '{{ __('Total Credits Budget') }}',
                            key: 'total_credits_budget',
                        },
                        {
                            label: '{{ __('Start Date') }}',
                            key: 'start_date',
                        },
                        {
                            label: '{{ __('End Date') }}',
                            key: 'end_date',
                        },
                        {
                            label: '{{ __('Auto Approve') }}',
                            key: 'auto_approve_label',
                            label_color: 'auto_approve_color',
                            type: 'badge'
                        },
                         {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'badge'
                        }
                    ];

                    showDetailsModal(route, id, '{{ __('Campaign Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
