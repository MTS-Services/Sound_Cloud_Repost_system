<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    @push('head')
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet">
        <style>
            /* Custom DataTable Styles for DaisyUI */
            .dataTable-wrapper .dataTable-top,
            .dataTable-wrapper .dataTable-bottom {
                padding: 1rem;
            }

            .dataTable-input {
                @apply input input-bordered input-sm;
            }

            .dataTable-selector {
                @apply select select-bordered select-sm;
            }

            .dataTable-pagination ul {
                @apply flex gap-1;
            }

            .dataTable-pagination a {
                @apply btn btn-sm btn-outline;
            }

            .dataTable-pagination .active a {
                @apply btn-active;
            }

            .dataTable-table thead tr th {
                @apply bg-base-200 text-base-content font-semibold;
                padding: 12px 8px;
            }

            .dataTable-table tbody tr td {
                padding: 12px 8px;
                border-bottom: 1px solid hsl(var(--bc) / 0.1);
            }

            .dataTable-table tbody tr:hover {
                @apply bg-base-200/50;
            }
        </style>
    @endpush
    <section>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-black dark:text-text-white">{{ __('User Management') }}</h2>
                <button class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 stroke-text-white"></i>
                    {{ __('Add Admin') }}
                </button>
            </div>
            {{-- Dynamic DataTable Component --}}
            <div x-data="dataTableComponent()" x-init="initDataTable()" class="container mx-auto p-6">
                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-3xl font-bold">Dynamic Data Table</h1>
                        <p class="text-base-content/70">Manage your data with advanced filtering and export options</p>
                    </div>

                    {{-- Add New Button --}}
                    <button @click="openModal('add')" class="btn btn-primary gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Record
                    </button>
                </div>

                {{-- Filters and Export Section --}}
                <div class="card bg-base-100 shadow-xl mb-6">
                    <div class="card-body">
                        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                            {{-- Search and Filters --}}
                            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                                {{-- Global Search --}}
                                <div class="form-control">
                                    <input type="text" placeholder="Search all columns..."
                                        class="input input-bordered w-full sm:w-64" x-model="globalSearch"
                                        @input="applyGlobalSearch()" />
                                </div>

                                {{-- Status Filter --}}
                                <div class="form-control">
                                    <select class="select select-bordered" x-model="statusFilter"
                                        @change="applyStatusFilter()">
                                        <option value="">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Export Dropdown --}}
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-outline gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export Data
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow-2xl border">
                                    <li><a @click="exportData('csv')" class="gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                            </svg>
                                            Export as CSV
                                        </a></li>
                                    <li><a @click="exportData('json')" class="gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M5,3H7V5H5V10A2,2 0 0,1 3,8V6A2,2 0 0,1 5,4V3M19,3V4A2,2 0 0,1 21,6V8A2,2 0 0,1 19,10V5H17V3H19M12,12A1,1 0 0,0 13,11A1,1 0 0,0 12,10A1,1 0 0,0 11,11A1,1 0 0,0 12,12M19,19H17V21H19V20A2,2 0 0,0 21,18V16A2,2 0 0,0 19,14V19M5,19V14A2,2 0 0,0 3,16V18A2,2 0 0,0 5,20V21H7V19H5Z" />
                                            </svg>
                                            Export as JSON
                                        </a></li>
                                    <li><a @click="exportData('txt')" class="gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                            </svg>
                                            Export as TXT
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DataTable Container --}}
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body p-0">
                        {{-- Table will be inserted here by Simple DataTables --}}
                        <div class="overflow-x-auto">
                            <table id="dataTable" class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dynamic data will be populated here --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Add/Edit Modal --}}
                <div class="modal" :class="{ 'modal-open': showModal }" @click.self="closeModal()">
                    <div class="modal-box w-11/12 max-w-2xl">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg"
                                x-text="modalMode === 'add' ? 'Add New Record' : 'Edit Record'"></h3>
                            <button @click="closeModal()" class="btn btn-sm btn-circle btn-ghost">✕</button>
                        </div>

                        <form @submit.prevent="saveRecord()">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Name</span>
                                    </label>
                                    <input type="text" class="input input-bordered" x-model="formData.name"
                                        required />
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Email</span>
                                    </label>
                                    <input type="email" class="input input-bordered" x-model="formData.email"
                                        required />
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Role</span>
                                    </label>
                                    <select class="select select-bordered" x-model="formData.role">
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
                                    <select class="select select-bordered" x-model="formData.status">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-action">
                                <button type="button" @click="closeModal()" class="btn btn-ghost">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <span x-text="modalMode === 'add' ? 'Add Record' : 'Update Record'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Delete Confirmation Modal --}}
                <div class="modal" :class="{ 'modal-open': showDeleteModal }">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">Confirm Deletion</h3>
                        <p class="py-4">Are you sure you want to delete this record? This action cannot be undone.
                        </p>
                        <div class="modal-action">
                            <button @click="showDeleteModal = false" class="btn btn-ghost">Cancel</button>
                            <button @click="confirmDelete()" class="btn btn-error">Delete</button>
                        </div>
                    </div>
                </div>

                {{-- Toast Notifications --}}
                <div class="toast toast-top toast-end" x-show="showToast" x-transition>
                    <div class="alert alert-success">
                        <span x-text="toastMessage"></span>
                    </div>
                </div>
            </div>

            {{-- Include Simple DataTables CSS and JS --}}


        </div>
    </section>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/umd/simple-datatables.js"></script>

        <script>
            window.routes = {
                fetchAdmins: "{{ route('am.admin.fetch') }}"
            };
        </script>
        <script>
            function dataTableComponent() {
                return {
                    // DataTable instance
                    dataTable: null,

                    // Sample data
                    sampleData: [],
                    // sampleData: [{
                    //         id: 1,
                    //         name: 'John Doe',
                    //         email: 'john@example.com',
                    //         role: 'Administrator',
                    //         status: 'Active',
                    //         created_at: '2024-01-15'
                    //     },
                    //     {
                    //         id: 2,
                    //         name: 'Jane Smith',
                    //         email: 'jane@example.com',
                    //         role: 'Manager',
                    //         status: 'Active',
                    //         created_at: '2024-01-20'
                    //     },
                    //     {
                    //         id: 3,
                    //         name: 'Bob Johnson',
                    //         email: 'bob@example.com',
                    //         role: 'User',
                    //         status: 'Inactive',
                    //         created_at: '2024-02-01'
                    //     },
                    //     {
                    //         id: 4,
                    //         name: 'Alice Brown',
                    //         email: 'alice@example.com',
                    //         role: 'User',
                    //         status: 'Pending',
                    //         created_at: '2024-02-10'
                    //     },
                    //     {
                    //         id: 5,
                    //         name: 'Charlie Wilson',
                    //         email: 'charlie@example.com',
                    //         role: 'Guest',
                    //         status: 'Active',
                    //         created_at: '2024-02-15'
                    //     },
                    //     {
                    //         id: 6,
                    //         name: 'Diana Davis',
                    //         email: 'diana@example.com',
                    //         role: 'Manager',
                    //         status: 'Active',
                    //         created_at: '2024-02-20'
                    //     },
                    //     {
                    //         id: 7,
                    //         name: 'Edward Miller',
                    //         email: 'edward@example.com',
                    //         role: 'User',
                    //         status: 'Inactive',
                    //         created_at: '2024-03-01'
                    //     },
                    //     {
                    //         id: 8,
                    //         name: 'Fiona Garcia',
                    //         email: 'fiona@example.com',
                    //         role: 'Administrator',
                    //         status: 'Active',
                    //         created_at: '2024-03-05'
                    //     }
                    // ],

                    // Filters
                    globalSearch: '',
                    statusFilter: '',

                    // Modal states
                    showModal: false,
                    showDeleteModal: false,
                    modalMode: 'add', // 'add' or 'edit'
                    editingId: null,
                    deleteId: null,

                    // Form data
                    formData: {
                        name: '',
                        email: '',
                        role: '',
                        status: 'Active'
                    },

                    // Toast notification
                    showToast: false,
                    toastMessage: '',

                    // Initialize DataTable
                    initDataTable() {
                        fetch(window.routes.fetchAdmins, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.sampleData = data
                                this.populateTable() //

                                this.dataTable = new simpleDatatables.DataTable("#dataTable", {
                                    searchable: true,
                                    sortable: true,
                                    perPage: 10,
                                    perPageSelect: [5, 10, 25, 50],
                                    labels: {
                                        placeholder: "Search records...",
                                        perPage: "records per page",
                                        noRows: "No records found",
                                        info: "Showing {start} to {end} of {rows} records"
                                    },
                                    columns: [{
                                        select: 6,
                                        sortable: true
                                    }]
                                });
                            })
                            .catch(error => {
                                console.error("Error loading admin data:", error);
                            });
                    },


                    // Populate table with data
                    populateTable() {
                        const tbody = document.querySelector('#dataTable tbody');
                        tbody.innerHTML = '';

                        this.sampleData.forEach(item => {
                            const row = tbody.insertRow();
                            row.innerHTML = `
                            <td>${item.id}</td>
                            <td class="font-medium">${item.name}</td>
                            <td>${item.email}</td>
                            <td>
                                <div class="badge badge-outline">${item.role}</div>
                            </td>
                            <td>
                                <div class="badge ${this.getStatusBadgeClass(item.status)}">${item.status}</div>
                            </td>
                            <td>${this.formatDate(item.created_at)}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-outline btn-primary" onclick="Alpine.store('dataTable').editRecord(${item.id})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="btn btn-sm btn-outline btn-error" onclick="Alpine.store('dataTable').deleteRecord(${item.id})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        `;
                        });
                    },

                    // Helper methods
                    getStatusBadgeClass(status) {
                        switch (status) {
                            case 'Active':
                                return 'badge-success';
                            case 'Inactive':
                                return 'badge-error';
                            case 'Pending':
                                return 'badge-warning';
                            default:
                                return 'badge-neutral';
                        }
                    },

                    formatDate(dateString) {
                        return new Date(dateString).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    },

                    // Search and filter methods
                    applyGlobalSearch() {
                        if (this.dataTable) {
                            this.dataTable.search(this.globalSearch);
                        }
                    },

                    applyStatusFilter() {
                        // Custom filter implementation
                        this.refreshTable();
                    },

                    refreshTable() {
                        this.dataTable.destroy();
                        this.populateTable();
                        this.initDataTable();
                    },

                    // Modal methods
                    openModal(mode, id = null) {
                        this.modalMode = mode;
                        this.showModal = true;

                        if (mode === 'edit' && id) {
                            this.editingId = id;
                            const record = this.sampleData.find(item => item.id === id);
                            if (record) {
                                this.formData = {
                                    ...record
                                };
                            }
                        } else {
                            this.resetForm();
                        }
                    },

                    closeModal() {
                        this.showModal = false;
                        this.resetForm();
                    },

                    resetForm() {
                        this.formData = {
                            name: '',
                            email: '',
                            role: '',
                            status: 'Active'
                        };
                        this.editingId = null;
                    },

                    // CRUD operations
                    saveRecord() {
                        if (this.modalMode === 'add') {
                            const newId = Math.max(...this.sampleData.map(item => item.id)) + 1;
                            const newRecord = {
                                id: newId,
                                ...this.formData,
                                created_at: new Date().toISOString().split('T')[0]
                            };
                            this.sampleData.push(newRecord);
                            this.showToastMessage('Record added successfully!');
                        } else {
                            const index = this.sampleData.findIndex(item => item.id === this.editingId);
                            if (index !== -1) {
                                this.sampleData[index] = {
                                    ...this.sampleData[index],
                                    ...this.formData
                                };
                                this.showToastMessage('Record updated successfully!');
                            }
                        }

                        this.closeModal();
                        this.refreshTable();
                    },

                    editRecord(id) {
                        this.openModal('edit', id);
                    },

                    deleteRecord(id) {
                        this.deleteId = id;
                        this.showDeleteModal = true;
                    },

                    confirmDelete() {
                        const index = this.sampleData.findIndex(item => item.id === this.deleteId);
                        if (index !== -1) {
                            this.sampleData.splice(index, 1);
                            this.showToastMessage('Record deleted successfully!');
                            this.refreshTable();
                        }
                        this.showDeleteModal = false;
                        this.deleteId = null;
                    },

                    // Export methods
                    exportData(format) {
                        switch (format) {
                            case 'csv':
                                if (this.dataTable) {
                                    simpleDatatables.exportCSV(this.dataTable, {
                                        download: true,
                                        lineDelimiter: "\n",
                                        columnDelimiter: ","
                                    });
                                }
                                break;
                            case 'json':
                                if (this.dataTable) {
                                    simpleDatatables.exportJSON(this.dataTable, {
                                        download: true,
                                        space: 2
                                    });
                                }
                                break;
                            case 'txt':
                                if (this.dataTable) {
                                    simpleDatatables.exportTXT(this.dataTable, {
                                        download: true
                                    });
                                }
                                break;
                        }
                    },

                    // Toast notification
                    showToastMessage(message) {
                        this.toastMessage = message;
                        this.showToast = true;
                        setTimeout(() => {
                            this.showToast = false;
                        }, 3000);
                    }
                }
            }

            // Store the component globally for button callbacks
            document.addEventListener('alpine:init', () => {
                Alpine.store('dataTable', {
                    editRecord(id) {
                        // Find the component and call editRecord
                        const component = Alpine.findClosest(document.querySelector(
                            '[x-data*="dataTableComponent"]'), x => x.$data);
                        if (component) {
                            component.editRecord(id);
                        }
                    },
                    deleteRecord(id) {
                        const component = Alpine.findClosest(document.querySelector(
                            '[x-data*="dataTableComponent"]'), x => x.$data);
                        if (component) {
                            component.deleteRecord(id);
                        }
                    }
                });
            });
        </script>
    @endpush
</x-admin::layout>
