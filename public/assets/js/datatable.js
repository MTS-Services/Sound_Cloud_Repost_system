/**
 * Enhanced Flowbite DataTable Manager
 * A comprehensive solution for managing data tables with CRUD operations and Flowbite styling
 */this.showLoading
class DataTableManager {
    constructor(tableId, config) {
        this.tableId = tableId;
        this.config = {
            fetchUrl: '',
            saveUrl: '',
            updateUrl: '',
            deleteUrl: '',
            csrfToken: '',
            columns: [],
            formFields: [],
            perPage: 10,
            perPageOptions: [5, 10, 25, 50],
            searchDelay: 500,
            exportOptions: {
                csv: true,
                json: true,
                txt: true,
                sql: true
            },
            tableName: 'export_table',
            ...config
        };

        this.dataTable = null;
        this.data = [];
        this.filteredData = [];
        this.currentEditId = null;
        this.searchTimeout = null;
        this.exportDropdown = null;

        this.init();
    }

    /**
     * Initialize the DataTable Manager
     */
    init() {
        this.setupAxiosDefaults();
        this.loadData();
        console.log('DataTableManager initialized with config:', this.loadData());
    }

    /**
     * Setup Axios defaults
     */
    setupAxiosDefaults() {
        if (typeof axios !== 'undefined') {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = this.config.csrfToken;
            axios.defaults.headers.common['Content-Type'] = 'application/json';
            axios.defaults.headers.common['Accept'] = 'application/json';
        }
    }

    /**
     * Load data from server
     */
    async loadData() {
        try {
            this.showLoading();
            
            // If fetchUrl is provided, fetch from server
            if (this.config.fetchUrl) {
                const response = await axios.post(this.config.fetchUrl);
                this.data = response.data;
            } else {
                // Use sample data if no fetchUrl provided
                this.data = this.generateSampleData();
            }
            
            this.filteredData = [...this.data];
            this.populateTableData();
            this.initializeDataTable();
            this.bindEvents();
            this.hideLoading();
        } catch (error) {
            console.error('Error loading data:', error);
            this.showToast('Error loading data', 'error');
            this.hideLoading();
        }
    }

    /**
     * Generate sample data for demonstration
     */
    generateSampleData() {
        const sampleData = [];
        const statuses = ['Active', 'Inactive', 'Pending'];
        const roles = ['Administrator', 'Manager', 'User', 'Guest'];
        
        for (let i = 1; i <= 50; i++) {
            sampleData.push({
                id: i,
                name: `User ${i}`,
                email: `user${i}@example.com`,
                role: roles[Math.floor(Math.random() * roles.length)],
                status: statuses[Math.floor(Math.random() * statuses.length)],
                created_at: new Date(Date.now() - Math.random() * 365 * 24 * 60 * 60 * 1000).toISOString()
            });
        }
        
        return sampleData;
    }

    /**
     * Populate table with data
     */
    populateTableData() {
        const tbody = document.querySelector(`#${this.tableId} tbody`);
        if (!tbody) return;

        tbody.innerHTML = '';

        this.filteredData.forEach(item => {
            const row = this.createTableRow(item);
            tbody.appendChild(row);
        });
    }

    /**
     * Create table row element
     */
    createTableRow(item) {
        const row = document.createElement('tr');
        row.className = 'border-b !border-border-black/5 hover:!bg-border-black/5 dark:!border-border-white/5 dark:hover:!bg-border-white/5 transition-colors';

        this.config.columns.forEach(col => {
            const cell = document.createElement('td');
            cell.className = '!text-text-light-secondary dark:!text-text-dark-primary px-4 py-3';

            if (col.key === 'actions') {
                cell.innerHTML = this.createActionButtons(item.id);
            } else if (col.type === 'date') {
                cell.textContent = this.formatDate(item[col.key]);
            } else if (col.key === 'status') {
                cell.innerHTML = this.createStatusBadge(item[col.key]);
            } else if (col.key === 'role') {
                cell.innerHTML = `<div class="badge badge-outline">${item[col.key]}</div>`;
            } else {
                cell.textContent = item[col.key] || '';
            }

            row.appendChild(cell);
        });

        return row;
    }

    /**
     * Initialize Flowbite DataTable with custom template
     */
    initializeDataTable() {
        if (!document.getElementById(this.tableId)) return;

        // Custom export CSV function
        const exportCustomCSV = (dataTable, userOptions = {}) => {
            const clonedUserOptions = { ...userOptions };
            clonedUserOptions.download = false;
            const csv = simpleDatatables.exportCSV(dataTable, clonedUserOptions);
            
            if (!csv) return false;
            
            const defaults = {
                download: true,
                lineDelimiter: "\n",
                columnDelimiter: ";"
            };
            const options = { ...defaults, ...clonedUserOptions };
            
            const separatorRow = Array(dataTable.data.headings.filter((_heading, index) =>
                !dataTable.columns.settings[index]?.hidden).length)
                .fill("+")
                .join("+");

            const str = separatorRow + options.lineDelimiter + csv + options.lineDelimiter + separatorRow;

            if (userOptions.download) {
                const link = document.createElement("a");
                link.href = encodeURI("data:text/csv;charset=utf-8," + str);
                link.download = (options.filename || "datatable_export") + ".txt";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            return str;
        };

        // Initialize DataTable
        this.dataTable = new simpleDatatables.DataTable(`#${this.tableId}`, {
            template: (options, dom) => this.buildTableTemplate(options, dom),
            searchable: true,
            sortable: true,
            perPage: this.config.perPage,
            perPageSelect: this.config.perPageOptions,
            labels: {
                placeholder: "Search records...",
                perPage: "records per page",
                noRows: "No records found",
                info: "Showing {start} to {end} of {rows} records"
            },
            columns: this.getColumnConfig()
        });

        // Setup export functionality
        this.setupExportFunctionality();
    }

    /**
     * Build custom table template with Flowbite styling
     */
    buildTableTemplate(options, dom) {
        const exportButtons = this.buildExportButtons();
        
        return `<div class='${options.classes.top}'>
            <div class='flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-3 rtl:space-x-reverse w-full sm:w-auto'>
                ${options.paging && options.perPageSelect ? `
                    <div class='${options.classes.dropdown}'>
                        <label>
                            <select class='${options.classes.selector}'></select> ${options.labels.perPage}
                        </label>
                    </div>
                ` : ''}
                ${exportButtons}
            </div>
            ${options.searchable ? `
                <div class='${options.classes.search}'>
                    <input class='${options.classes.input}' placeholder='${options.labels.placeholder}' type='search' title='${options.labels.searchTitle}'${dom.id ? ` aria-controls='${dom.id}'` : ''}>
                </div>
            ` : ''}
        </div>
        <div class='${options.classes.container}'${options.scrollY.length ? ` style='height: ${options.scrollY}; overflow-Y: auto;'` : ''}></div>
        <div class='${options.classes.bottom}'>
            ${options.paging ? `<div class='${options.classes.info}'></div>` : ''}
            <nav class='${options.classes.pagination}'></nav>
        </div>`;
    }

    /**
     * Build export buttons HTML
     */
    buildExportButtons() {
        const buttons = [];
        
        if (this.config.exportOptions.csv) {
            buttons.push(`
                <button id='export-csv-${this.tableId}' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>
                    <svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>
                        <path fill-rule='evenodd' d='M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm1.018 8.828a2.34 2.34 0 0 0-2.373 2.13v.008a2.32 2.32 0 0 0 2.06 2.497l.535.059a.993.993 0 0 0 .136.006.272.272 0 0 1 .263.367l-.008.02a.377.377 0 0 1-.018.044.49.49 0 0 1-.078.02 1.689 1.689 0 0 1-.297.021h-1.13a1 1 0 1 0 0 2h1.13c.417 0 .892-.05 1.324-.279.47-.248.78-.648.953-1.134a2.272 2.272 0 0 0-2.115-3.06l-.478-.052a.32.32 0 0 1-.285-.341.34.34 0 0 1 .344-.306l.94.02a1 1 0 1 0 .043-2l-.943-.02h-.003Zm7.933 1.482a1 1 0 1 0-1.902-.62l-.57 1.747-.522-1.726a1 1 0 0 0-1.914.578l1.443 4.773a1 1 0 0 0 1.908.021l1.557-4.773Zm-13.762.88a.647.647 0 0 1 .458-.19h1.018a1 1 0 1 0 0-2H6.647A2.647 2.647 0 0 0 4 13.647v1.706A2.647 2.647 0 0 0 6.647 18h1.018a1 1 0 1 0 0-2H6.647A.647.647 0 0 1 6 15.353v-1.706c0-.172.068-.336.19-.457Z' clip-rule='evenodd'/>
                    </svg>
                    <span>Export CSV</span>
                </button>
            `);
        }

        if (this.config.exportOptions.json) {
            buttons.push(`
                <button id='export-json-${this.tableId}' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>
                    <svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>
                        <path fill-rule='evenodd' d='M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Zm-.293 9.293a1 1 0 0 1 0 1.414L9.414 14l1.293 1.293a1 1 0 0 1-1.414 1.414l-2-2a1 1 0 0 1 0-1.414l2-2a1 1 0 0 1 1.414 0Zm2.586 1.414a1 1 0 0 1 1.414-1.414l2 2a1 1 0 0 1 0 1.414l-2 2a1 1 0 0 1-1.414-1.414L14.586 14l-1.293-1.293Z' clip-rule='evenodd'/>
                    </svg>
                    <span>Export JSON</span>
                </button>
            `);
        }

        if (this.config.exportOptions.txt) {
            buttons.push(`
                <button id='export-txt-${this.tableId}' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>
                    <svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>
                        <path fill-rule='evenodd' d='M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7ZM8 16a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1Zm1-5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z' clip-rule='evenodd'/>
                    </svg>
                    <span>Export TXT</span>
                </button>
            `);
        }

        if (this.config.exportOptions.sql) {
            buttons.push(`
                <button id='export-sql-${this.tableId}' class='group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white'>
                    <svg class='me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' viewBox='0 0 24 24'>
                        <path d='M12 7.205c4.418 0 8-1.165 8-2.602C20 3.165 16.418 2 12 2S4 3.165 4 4.603c0 1.437 3.582 2.602 8 2.602ZM12 22c4.963 0 8-1.686 8-2.603v-4.404c-.052.032-.112.06-.165.09a7.75 7.75 0 0 1-.745.387c-.193.088-.394.173-.6.253-.063.024-.124.05-.189.073a18.934 18.934 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.073a10.143 10.143 0 0 1-.852-.373 7.75 7.75 0 0 1-.493-.267c-.053-.03-.113-.058-.165-.09v4.404C4 20.315 7.037 22 12 22Zm7.09-13.928a9.91 9.91 0 0 1-.6.253c-.063.025-.124.05-.189.074a18.935 18.935 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.074a10.163 10.163 0 0 1-.852-.372 7.816 7.816 0 0 1-.493-.268c-.055-.03-.115-.058-.167-.09V12c0 .917 3.037 2.603 8 2.603s8-1.686 8-2.603V7.596c-.052.031-.112.059-.165.09a7.816 7.816 0 0 1-.745.386Z'/>
                    </svg>
                    <span>Export SQL</span>
                </button>
            `);
        }

        return `
            <button id='exportDropdownButton-${this.tableId}' type='button' class='flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto'>
                Export as
                <svg class='-me-0.5 ms-1.5 h-4 w-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='none' viewBox='0 0 24 24'>
                    <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7' />
                </svg>
            </button>
            <div id='exportDropdown-${this.tableId}' class='z-10 hidden w-52 divide-y divide-gray-100 rounded-lg bg-white shadow-sm dark:bg-gray-700' data-popper-placement='bottom'>
                <ul class='p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400' aria-labelledby='exportDropdownButton-${this.tableId}'>
                    ${buttons.map(button => `<li>${button}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    /**
     * Setup export functionality
     */
    setupExportFunctionality() {
        const exportButton = document.getElementById(`exportDropdownButton-${this.tableId}`);
        const exportDropdownEl = document.getElementById(`exportDropdown-${this.tableId}`);
        
        if (exportButton && exportDropdownEl && typeof Dropdown !== 'undefined') {
            this.exportDropdown = new Dropdown(exportDropdownEl, exportButton);
        }

        // Bind export events
        if (this.config.exportOptions.csv) {
            this.bindExportEvent('csv', () => {
                simpleDatatables.exportCSV(this.dataTable, {
                    download: true,
                    lineDelimiter: "\n",
                    columnDelimiter: ";"
                });
            });
        }

        if (this.config.exportOptions.json) {
            this.bindExportEvent('json', () => {
                simpleDatatables.exportJSON(this.dataTable, {
                    download: true,
                    space: 3
                });
            });
        }

        if (this.config.exportOptions.txt) {
            this.bindExportEvent('txt', () => {
                simpleDatatables.exportTXT(this.dataTable, {
                    download: true
                });
            });
        }

        if (this.config.exportOptions.sql) {
            this.bindExportEvent('sql', () => {
                simpleDatatables.exportSQL(this.dataTable, {
                    download: true,
                    tableName: this.config.tableName
                });
            });
        }
    }

    /**
     * Bind export event
     */
    bindExportEvent(format, callback) {
        const button = document.getElementById(`export-${format}-${this.tableId}`);
        if (button) {
            button.addEventListener('click', callback);
        }
    }

    /**
     * Get column configuration for DataTable
     */
    getColumnConfig() {
        return this.config.columns.map((col, index) => ({
            select: index,
            sortable: col.sortable !== false
        }));
    }

    /**
     * Create action buttons
     */
    createActionButtons(id) {
        return `
            <div class="flex gap-2">
                <button class="btn btn-sm btn-outline btn-primary edit-btn" data-id="${id}" title="Edit">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button class="btn btn-sm btn-outline btn-error delete-btn" data-id="${id}" title="Delete">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `;
    }

    /**
     * Create status badge
     */
    createStatusBadge(status) {
        const badgeClass = this.getStatusBadgeClass(status);
        return `<div class="badge ${badgeClass}">${status}</div>`;
    }

    /**
     * Get status badge class
     */
    getStatusBadgeClass(status) {
        const classes = {
            'Active': 'badge-success',
            'Inactive': 'badge-error',
            'Pending': 'badge-warning'
        };
        return classes[status] || 'badge-neutral';
    }

    /**
     * Format date
     */
    formatDate(dateString) {
        if (!dateString) return '';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionButtons();

        // Custom filter events
        this.bindFilterEvents();
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        const addBtn = document.getElementById('addNewBtn');
        const closeModalBtns = document.querySelectorAll('#closeModal, #cancelBtn');
        const recordForm = document.getElementById('recordForm');
        const modal = document.getElementById('recordModal');

        if (addBtn) {
            addBtn.addEventListener('click', () => this.openModal('add'));
        }

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', () => this.closeModal());
        });

        if (recordForm) {
            recordForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Close modal on backdrop click
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    this.closeModal();
                }
            });
        }

        // Delete modal events
        this.bindDeleteModalEvents();
    }

    /**
     * Bind delete modal events
     */
    bindDeleteModalEvents() {
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const deleteModal = document.getElementById('deleteModal');

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', () => this.closeDeleteModal());
        }

        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => this.confirmDelete());
        }

        if (deleteModal) {
            deleteModal.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    this.closeDeleteModal();
                }
            });
        }
    }

    /**
     * Bind action buttons (edit and delete)
     */
    bindActionButtons() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.edit-btn')) {
                const btn = e.target.closest('.edit-btn');
                const id = parseInt(btn.dataset.id);
                this.openModal('edit', id);
            }

            if (e.target.closest('.delete-btn')) {
                const btn = e.target.closest('.delete-btn');
                const id = parseInt(btn.dataset.id);
                this.openDeleteModal(id);
            }
        });
    }

    /**
     * Bind filter events
     */
    bindFilterEvents() {
        // Add custom filter functionality here if needed
        // For example, status filters, date range filters, etc.
    }

    /**
     * Open modal for add/edit
     */
    openModal(mode, id = null) {
        this.currentEditId = id;
        const modal = document.getElementById('recordModal');
        const modalTitle = document.getElementById('modalTitle');
        const saveBtn = document.getElementById('saveBtn');

        if (mode === 'add') {
            modalTitle.textContent = 'Add New Record';
            saveBtn.textContent = 'Add Record';
            this.resetForm();
        } else {
            modalTitle.textContent = 'Edit Record';
            saveBtn.textContent = 'Update Record';
            this.populateForm(id);
        }

        modal.classList.add('modal-open');
    }

    /**
     * Close modal
     */
    closeModal() {
        const modal = document.getElementById('recordModal');
        modal.classList.remove('modal-open');
        this.resetForm();
        this.currentEditId = null;
    }

    /**
     * Reset form
     */
    resetForm() {
        const form = document.getElementById('recordForm');
        if (form) {
            form.reset();
            // Remove error classes
            form.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
        }
    }

    /**
     * Populate form with data
     */
    populateForm(id) {
        const record = this.data.find(item => item.id === id);
        if (record) {
            this.config.formFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.value = record[field] || '';
                }
            });
        }
    }

    /**
     * Handle form submit
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        const formData = this.getFormData();
        const isEdit = this.currentEditId !== null;

        try {
            let response;
            if (isEdit) {
                // For demo purposes, simulate server response
                if (this.config.updateUrl) {
                    response = await axios.put(`${this.config.updateUrl.replace(':id', this.currentEditId)}`, formData);
                } else {
                    // Update local data
                    const index = this.data.findIndex(item => item.id === this.currentEditId);
                    if (index !== -1) {
                        this.data[index] = { ...this.data[index], ...formData };
                        response = { data: this.data[index] };
                    }
                }
            } else {
                if (this.config.saveUrl) {
                    response = await axios.post(this.config.saveUrl, formData);
                } else {
                    // Add to local data
                    const newId = Math.max(...this.data.map(item => item.id)) + 1;
                    const newRecord = { id: newId, ...formData, created_at: new Date().toISOString() };
                    this.data.push(newRecord);
                    response = { data: newRecord };
                }
            }

            this.showToast(
                isEdit ? 'Record updated successfully!' : 'Record added successfully!',
                'success'
            );

            this.closeModal();
            await this.refreshData();

        } catch (error) {
            console.error('Error saving record:', error);
            this.handleFormErrors(error);
        }
    }

    showLoading() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        if (loadingSpinner) {
            loadingSpinner.classList.remove('hidden');
        }
    }
    hideLoading() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
        }
    }
}