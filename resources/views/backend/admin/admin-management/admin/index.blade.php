<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
    @endpush

    <section>
        <div class="glass-card rounded-2xl p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-text-black dark:text-text-white">{{ __('User Management') }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your administrators with advanced
                        filtering and export options</p>
                </div>
                <button id="addNewBtn"
                    class="btn-primary px-4 py-2 rounded-xl text-white flex items-center gap-2 hover:bg-opacity-90 transition-all">
                    <i data-lucide="user-plus" class="w-4 h-4 stroke-white"></i>
                    {{ __('Add Admin') }}
                </button>
            </div>

            <!-- Filters and Export Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-4 mb-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <!-- Search and Filters -->
                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <div class="form-control">
                            <input type="text" id="globalSearch" placeholder="Search all columns..."
                                class="input input-bordered w-full sm:w-64" />
                        </div>
                        <div class="form-control">
                            <select id="statusFilter" class="select select-bordered">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>

                    <!-- Export Dropdown -->
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-outline gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export Data
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow-2xl border">
                            <li><a id="exportCsv" class="gap-2">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    Export as CSV
                                </a></li>
                            <li><a id="exportJson" class="gap-2">
                                    <i data-lucide="file-code" class="w-4 h-4"></i>
                                    Export as JSON
                                </a></li>
                            <li><a id="exportTxt" class="gap-2">
                                    <i data-lucide="file" class="w-4 h-4"></i>
                                    Export as TXT
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- DataTable Container -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="dataTable" class="table w-full table-zebra">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">ID</th>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">Name</th>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">Email</th>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">Role</th>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">Created Date</th>
                                <th class="text-left font-semibold text-gray-900 dark:text-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div id="recordModal" class="modal">
            <div class="modal-box w-11/12 max-w-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="font-bold text-lg">Add New Record</h3>
                    <button id="closeModal" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                <form id="recordForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Name</span>
                            </label>
                            <input type="text" id="name" name="name" class="input input-bordered" required />
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" id="email" name="email" class="input input-bordered" required />
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Role</span>
                            </label>
                            <select id="role" name="role" class="select select-bordered">
                                <option value="">Select Role</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Manager">Manager</option>
                                <option value="User">User</option>
                                <option value="Guest">Guest</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Status</span>
                            </label>
                            <select id="status" name="status" class="select select-bordered">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-action">
                        <button type="button" id="cancelBtn" class="btn btn-ghost">Cancel</button>
                        <button type="submit" id="saveBtn" class="btn btn-primary">Add Record</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Confirm Deletion</h3>
                <p class="py-4">Are you sure you want to delete this record? This action cannot be undone.</p>
                <div class="modal-action">
                    <button id="cancelDelete" class="btn btn-ghost">Cancel</button>
                    <button id="confirmDelete" class="btn btn-error">Delete</button>
                </div>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast" class="toast toast-top toast-end" style="display: none;">
            <div class="alert alert-success">
                <span id="toastMessage"></span>
            </div>
        </div>
    </section>

    @push('js')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/umd/simple-datatables.js"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTable with configuration
                const config = {
                    fetchUrl: '{{ route('am.admin.fetch') }}',
                    saveUrl: '{{ route('am.admin.save') }}',
                    updateUrl: '{{ route('am.admin.update', ':id') }}',
                    deleteUrl: '{{ route('am.admin.delete', ':id') }}',
                    csrfToken: '{{ csrf_token() }}',
                    columns: [{
                            key: 'id',
                            label: 'ID'
                        },
                        {
                            key: 'name',
                            label: 'Name',
                            searchable: true
                        },
                        {
                            key: 'email',
                            label: 'Email',
                            searchable: true
                        },
                        {
                            key: 'role',
                            label: 'Role',
                            filterable: true
                        },
                        {
                            key: 'status',
                            label: 'Status',
                            filterable: true
                        },
                        {
                            key: 'created_at',
                            label: 'Created Date',
                            type: 'date'
                        },
                        {
                            key: 'actions',
                            label: 'Actions',
                            sortable: false
                        }
                    ],
                    formFields: ['name', 'email', 'role', 'status']
                };

                window.dataTableManager = new DataTableManager('dataTable', config);
            });
        </script>
    @endpush
</x-admin::layout>
