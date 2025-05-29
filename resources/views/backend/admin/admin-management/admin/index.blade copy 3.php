<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    @push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
        {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet"> --}}
        <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
    @endpush

    <section>
        <div class="glass-card rounded-2xl p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
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

        </div>
        <div class="glass-card rounded-2xl p-6 mt-6">        

            <!-- DataTable Container -->
            <div class="overflow-x-auto">
                <table id="export-table" class="table w-full table-zebra">
                    <thead>
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
        {{-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/umd/simple-datatables.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTable with configuration
                const config = {
                    // fetchUrl: '{{ route('am.admin.fetch') }}',
                    // saveUrl: '{{ route('am.admin.save') }}',
                    // updateUrl: '{{ route('am.admin.update', ':id') }}',
                    // deleteUrl: '{{ route('am.admin.delete', ':id') }}',
                    // csrfToken: '{{ csrf_token() }}',
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

        {{-- <script>
            if (document.getElementById("export-table") && typeof simpleDatatables.DataTable !== 'undefined') {

                const exportCustomCSV = function(dataTable, userOptions = {}) {
                    // A modified CSV export that includes a row of minuses at the start and end.
                    const clonedUserOptions = {
                        ...userOptions
                    }
                    clonedUserOptions.download = false
                    const csv = simpleDatatables.exportCSV(dataTable, clonedUserOptions)
                    // If CSV didn't work, exit.
                    if (!csv) {
                        return false
                    }
                    const defaults = {
                        download: true,
                        lineDelimiter: "\n",
                        columnDelimiter: ";"
                    }
                    const options = {
                        ...defaults,
                        ...clonedUserOptions
                    }
                    const separatorRow = Array(dataTable.data.headings.filter((_heading, index) => !dataTable.columns
                            .settings[index]?.hidden).length)
                        .fill("+")
                        .join("+"); // Use "+" as the delimiter

                    const str = separatorRow + options.lineDelimiter + csv + options.lineDelimiter + separatorRow;

                    if (userOptions.download) {
                        // Create a link to trigger the download
                        const link = document.createElement("a");
                        link.href = encodeURI("data:text/csv;charset=utf-8," + str);
                        link.download = (options.filename || "datatable_export") + ".txt";
                        // Append the link
                        document.body.appendChild(link);
                        // Trigger the download
                        link.click();
                        // Remove the link
                        document.body.removeChild(link);
                    }

                    return str
                }
                const table = new simpleDatatables.DataTable("#export-table", {
                    template: (options, dom) => "<div class='" + options.classes.top + "'>" +
                        "<div class='flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-3 rtl:space-x-reverse w-full sm:w-auto'>" +
                        (options.paging && options.perPageSelect ?
                            "<div class='" + options.classes.dropdown + "'>" +
                            "<label>" +
                            "<select class='" + options.classes.selector + "'></select> " + options.labels.perPage +
                            "</label>" +
                            "</div>" : ""
                        ) +
                        "<button id='exportDropdownButton' type='button' class='flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto'>" +
                        "Export as" +
                        "<svg class='-me-0.5 ms-1.5 h-4 w-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'>" +
                        "<path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7' />" +
                        "</svg>" +
                        "</button>" +
                        "<div id='exportDropdown' class='z-10 hidden w-52 divide-y divide-gray-100 rounded-lg bg-white shadow-sm dark:bg-gray-700' data-popper-placement='bottom'>" +
                        "<ul class='p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400' aria-labelledby='exportDropdownButton'>" +
                        "<li>" +
                        "<button id='export-csv' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>" +
                        "<svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>" +
                        "<path fill-rule='evenodd' d='M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm1.018 8.828a2.34 2.34 0 0 0-2.373 2.13v.008a2.32 2.32 0 0 0 2.06 2.497l.535.059a.993.993 0 0 0 .136.006.272.272 0 0 1 .263.367l-.008.02a.377.377 0 0 1-.018.044.49.49 0 0 1-.078.02 1.689 1.689 0 0 1-.297.021h-1.13a1 1 0 1 0 0 2h1.13c.417 0 .892-.05 1.324-.279.47-.248.78-.648.953-1.134a2.272 2.272 0 0 0-2.115-3.06l-.478-.052a.32.32 0 0 1-.285-.341.34.34 0 0 1 .344-.306l.94.02a1 1 0 1 0 .043-2l-.943-.02h-.003Zm7.933 1.482a1 1 0 1 0-1.902-.62l-.57 1.747-.522-1.726a1 1 0 0 0-1.914.578l1.443 4.773a1 1 0 0 0 1.908.021l1.557-4.773Zm-13.762.88a.647.647 0 0 1 .458-.19h1.018a1 1 0 1 0 0-2H6.647A2.647 2.647 0 0 0 4 13.647v1.706A2.647 2.647 0 0 0 6.647 18h1.018a1 1 0 1 0 0-2H6.647A.647.647 0 0 1 6 15.353v-1.706c0-.172.068-.336.19-.457Z' clip-rule='evenodd'/>" +
                        "</svg>" +
                        "<span>Export CSV</span>" +
                        "</button>" +
                        "</li>" +
                        "<li>" +
                        "<button id='export-json' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>" +
                        "<svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>" +
                        "<path fill-rule='evenodd' d='M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Zm-.293 9.293a1 1 0 0 1 0 1.414L9.414 14l1.293 1.293a1 1 0 0 1-1.414 1.414l-2-2a1 1 0 0 1 0-1.414l2-2a1 1 0 0 1 1.414 0Zm2.586 1.414a1 1 0 0 1 1.414-1.414l2 2a1 1 0 0 1 0 1.414l-2 2a1 1 0 0 1-1.414-1.414L14.586 14l-1.293-1.293Z' clip-rule='evenodd'/>" +
                        "</svg>" +
                        "<span>Export JSON</span>" +
                        "</button>" +
                        "</li>" +
                        "<li>" +
                        "<button id='export-txt' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>" +
                        "<svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>" +
                        "<path fill-rule='evenodd' d='M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7ZM8 16a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm1-5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z' clip-rule='evenodd'/>" +
                        "</svg>" +
                        "<span>Export TXT</span>" +
                        "</button>" +
                        "</li>" +
                        "<li>" +
                        "<button id='export-sql' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>" +
                        "<svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>" +
                        "<path d='M12 7.205c4.418 0 8-1.165 8-2.602C20 3.165 16.418 2 12 2S4 3.165 4 4.603c0 1.437 3.582 2.602 8 2.602ZM12 22c4.963 0 8-1.686 8-2.603v-4.404c-.052.032-.112.06-.165.09a7.75 7.75 0 0 1-.745.387c-.193.088-.394.173-.6.253-.063.024-.124.05-.189.073a18.934 18.934 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.073a10.143 10.143 0 0 1-.852-.373 7.75 7.75 0 0 1-.493-.267c-.053-.03-.113-.058-.165-.09v4.404C4 20.315 7.037 22 12 22Zm7.09-13.928a9.91 9.91 0 0 1-.6.253c-.063.025-.124.05-.189.074a18.935 18.935 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.074a10.163 10.163 0 0 1-.852-.372 7.816 7.816 0 0 1-.493-.268c-.055-.03-.115-.058-.167-.09V12c0 .917 3.037 2.603 8 2.603s8-1.686 8-2.603V7.596c-.052.031-.112.059-.165.09a7.816 7.816 0 0 1-.745.386Z'/>" +
                        "</svg>" +
                        "<span>Export SQL</span>" +
                        "</button>" +
                        "</li>" +
                        "</ul>" +
                        "</div>" + "</div>" +
                        (options.searchable ?
                            "<div class='" + options.classes.search + "'>" +
                            "<input class='" + options.classes.input + "' placeholder='" + options.labels.placeholder +
                            "' type='search' title='" + options.labels.searchTitle + "'" + (dom.id ?
                                " aria-controls='" + dom.id + "'" : "") + ">" +
                            "</div>" : ""
                        ) +
                        "</div>" +
                        "<div class='" + options.classes.container + "'" + (options.scrollY.length ?
                            " style='height: " + options.scrollY + "; overflow-Y: auto;'" : "") + "></div>" +
                        "<div class='" + options.classes.bottom + "'>" +
                        (options.paging ?
                            "<div class='" + options.classes.info + "'></div>" : ""
                        ) +
                        "<nav class='" + options.classes.pagination + "'></nav>" +
                        "</div>"
                })
                const $exportButton = document.getElementById("exportDropdownButton");
                const $exportDropdownEl = document.getElementById("exportDropdown");
                const dropdown = new Dropdown($exportDropdownEl, $exportButton); 

                document.getElementById("export-csv").addEventListener("click", () => {
                    simpleDatatables.exportCSV(table, {
                        download: true,
                        lineDelimiter: "\n",
                        columnDelimiter: ";"
                    })
                })
                document.getElementById("export-sql").addEventListener("click", () => {
                    simpleDatatables.exportSQL(table, {
                        download: true,
                        tableName: "export_table"
                    })
                })
                document.getElementById("export-txt").addEventListener("click", () => {
                    simpleDatatables.exportTXT(table, {
                        download: true
                    })
                })
                document.getElementById("export-json").addEventListener("click", () => {
                    simpleDatatables.exportJSON(table, {
                        download: true,
                        space: 3
                    })
                })
            }
        </script> --}}
    @endpush
</x-admin::layout>
