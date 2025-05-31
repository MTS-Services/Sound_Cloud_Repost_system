<x-admin::layout>
    <x-slot name="title">{{ __('Admin List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin List') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Admin List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.secondary-link error="true" href="{{ route('am.admin.trash') }}">{{ __('Trash') }}
                    </x-admin.secondary-link>
                    <x-admin.primary-link href="{{ route('am.admin.create') }}">{{ __('Add') }}
                    </x-admin.primary-link>
                </div>
            </div>
        </div>
        <button onclick="details_modal.showModal()" class="btn btn-primary">Open modal</button>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    <x-admin.details-modal title="Admin Details" />

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['email', true, true],
                    ['role_id', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.admin.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4],
                    model: 'Admin',
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
                    const route = "{{ route('am.admin.show', ':id') }}";

                    const details = [{
                            label: 'Name',
                            key: 'name'
                        },
                        {
                            label: 'Email',
                            key: 'email'
                        },
                        {
                            label: 'Role',
                            key: 'role_id'
                        },
                        {
                            label: 'Created By',
                            key: 'created_by'
                        },
                        {
                            label: 'Created At',
                            key: 'created_at'
                        }
                    ];

                    showDetailsModal(route, id, 'Admin Details', details);
                });


                function showDetailsModal(apiRouteWithPlaceholder, id, title = 'Details', details = null) {
                    const url = apiRouteWithPlaceholder.replace(':id', id);

                    axios.post(url)
                        .then(res => {
                            const data = res.data?.admin || res.data;
                            let html = '';

                            if (details && Array.isArray(details)) {
                                // Show only specified keys
                                details.forEach(item => {
                                    const label = item.label || item.key;
                                    const value = data[item.key] ?? 'N/A';

                                    html += `
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium text-gray-700 dark:text-gray-300">${label}</span>
                            <span class="text-gray-900 dark:text-white">${value}</span>
                        </div>
                    `;
                                });
                            } else {
                                // Fallback: show everything
                                for (const [key, value] of Object.entries(data)) {
                                    const formattedKey = key
                                        .replace(/_/g, ' ')
                                        .replace(/\b\w/g, l => l.toUpperCase());

                                    html += `
                        <div class="flex justify-between border-b py-2">
                            <span class="font-medium text-gray-700 dark:text-gray-300">${formattedKey}</span>
                            <span class="text-gray-900 dark:text-white">${value ?? 'N/A'}</span>
                        </div>
                    `;
                                }
                            }

                            document.getElementById('modal-title').innerText = title;
                            document.getElementById('modal-content').innerHTML = html;
                            details_modal.showModal();
                        })
                        .catch(err => {
                            console.error('Error loading details:', err);
                        });
                }

            });
        </script>
    @endpush
</x-admin::layout>
