{{-- resources/views/components/dynamic-datatable.blade.php --}}
@props([
    'config' => [],
])

@php
    $tableId = $config['id'] ?? 'dynamic-table';
    $title = $config['title'] ?? 'Data Management';
    $description = $config['description'] ?? 'Manage your data with advanced filtering and export options';
    $apiUrl = $config['api_url'] ?? '';
    $columns = $config['columns'] ?? [];
    $formFields = $config['form_fields'] ?? [];
    $actions = $config['actions'] ?? [];
@endphp

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
@endpush

<section class="dynamic-datatable-wrapper" data-table-id="{{ $tableId }}">
    <!-- Header Section -->
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-text-black dark:text-text-white">{{ $title }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $description }}</p>
            </div>
            <button id="addNewBtn-{{ $tableId }}"
                class="btn-primary px-4 py-2 rounded-xl text-white flex items-center gap-2 hover:bg-opacity-90 transition-all">
                <i data-lucide="user-plus" class="w-4 h-4 stroke-white"></i>
                {{ __('Add New') }}
            </button>
        </div>
    </div>

    <!-- DataTable Section -->
    <div class="glass-card rounded-2xl p-6 mt-6">
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <input type="text" id="searchInput-{{ $tableId }}" placeholder="Search..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <i data-lucide="search" class="absolute right-3 top-2.5 w-4 h-4 text-gray-400"></i>
            </div>

            <!-- Per Page Selector -->
            <select id="perPageSelect-{{ $tableId }}"
                class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>

            <!-- Export Dropdown -->
            <div class="relative">
                <button id="exportBtn-{{ $tableId }}" type="button"
                    class="flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                    Export
                    <i data-lucide="download" class="ml-2 w-4 h-4"></i>
                </button>
                <div id="exportDropdown-{{ $tableId }}"
                    class="hidden absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg dark:bg-gray-700">
                    <div class="py-1">
                        <button
                            class="export-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 w-full text-left"
                            data-format="csv">
                            Export CSV
                        </button>
                        <button
                            class="export-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 w-full text-left"
                            data-format="json">
                            Export JSON
                        </button>
                        <button
                            class="export-option block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 w-full text-left"
                            data-format="excel">
                            Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner-{{ $tableId }}" class="flex justify-center items-center py-8 hidden">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600">Loading...</span>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="{{ $tableId }}" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            <input type="checkbox" id="selectAll-{{ $tableId }}" class="rounded">
                        </th>
                        @foreach ($columns as $column)
                            @if ($column['key'] !== 'actions')
                                <th scope="col" class="px-4 py-3 cursor-pointer sortable"
                                    data-column="{{ $column['key'] }}">
                                    <div class="flex items-center">
                                        {{ $column['label'] }}
                                        <i data-lucide="arrow-up-down" class="ml-1 w-4 h-4 sort-icon"></i>
                                    </div>
                                </th>
                            @else
                                <th scope="col" class="px-4 py-3">{{ $column['label'] }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody id="tableBody-{{ $tableId }}" class="bg-white dark:bg-gray-800">
                    <!-- Dynamic content will be inserted here -->
                </tbody>
            </table>
        </div>

        <!-- No Data Message -->
        <div id="noDataMessage-{{ $tableId }}" class="text-center py-8 hidden">
            <i data-lucide="inbox" class="mx-auto w-12 h-12 text-gray-400 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400">No data available</p>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer-{{ $tableId }}" class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-700 dark:text-gray-400">
                Showing <span id="showingFrom-{{ $tableId }}">0</span> to <span
                    id="showingTo-{{ $tableId }}">0</span> of <span
                    id="totalRecords-{{ $tableId }}">0</span> results
            </div>
            <nav>
                <ul id="paginationList-{{ $tableId }}" class="flex items-center -space-x-px h-8 text-sm">
                    <!-- Pagination buttons will be inserted here -->
                </ul>
            </nav>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="recordModal-{{ $tableId }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 id="modalTitle-{{ $tableId }}"
                        class="text-lg font-semibold text-gray-900 dark:text-white">
                        Add New Record
                    </h3>
                    <button id="closeModal-{{ $tableId }}"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form id="recordForm-{{ $tableId }}" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($formFields as $field)
                            <div class="form-group {{ $field['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                                <label for="{{ $field['name'] }}-{{ $tableId }}"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $field['label'] }}
                                    @if ($field['required'] ?? false)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if ($field['type'] === 'select')
                                    <select id="{{ $field['name'] }}-{{ $tableId }}" name="{{ $field['name'] }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        {{ $field['required'] ?? false ? 'required' : '' }}>
                                        <option value="">Select {{ $field['label'] }}</option>
                                        @foreach ($field['options'] ?? [] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] === 'textarea')
                                    <textarea id="{{ $field['name'] }}-{{ $tableId }}" name="{{ $field['name'] }}" rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        {{ $field['required'] ?? false ? 'required' : '' }}></textarea>
                                @else
                                    <input type="{{ $field['type'] }}"
                                        id="{{ $field['name'] }}-{{ $tableId }}" name="{{ $field['name'] }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        {{ $field['required'] ?? false ? 'required' : '' }}>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" id="cancelBtn-{{ $tableId }}"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" id="saveBtn-{{ $tableId }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Save Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal-{{ $tableId }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Deletion</h3>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Are you sure you want to delete this record? This action cannot be undone.
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button id="cancelDelete-{{ $tableId }}"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button id="confirmDelete-{{ $tableId }}"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast-{{ $tableId }}" class="fixed top-4 right-4 z-50 hidden">
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div id="toastIcon-{{ $tableId }}" class="flex-shrink-0 w-6 h-6 mr-3">
                    <!-- Dynamic icon will be inserted here -->
                </div>
                <div id="toastMessage-{{ $tableId }}" class="text-sm text-gray-700 dark:text-gray-300">
                    <!-- Dynamic message will be inserted here -->
                </div>
                <button onclick="hideToast('{{ $tableId }}')" class="ml-auto text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</section>

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Initialize DataTable configuration
        window.DataTableConfigs = window.DataTableConfigs || {};
        window.DataTableConfigs['{{ $tableId }}'] = @json([
            'id' => $tableId,
            'api_url' => $apiUrl,
            'columns' => $columns,
            'actions' => $actions,
            'form_fields' => $formFields,
        ]);
    </script>
    <script src="{{ asset('assets/js/datatable2.js') }}"></script>
@endpush
