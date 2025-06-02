/**
 * Reusable DataTable Manager
 * A comprehensive solution for managing data tables with CRUD operations
 */
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
            ...config
        };

        this.dataTable = null;
        this.data = [];
        this.filteredData = [];
        this.currentEditId = null;
        this.searchTimeout = null;

        this.init();
    }

    /**
     * Initialize the DataTable Manager
     */
    init() {
        this.setupAxiosDefaults();
        this.bindEvents();
        this.loadData();
    }

    /**
     * Setup Axios defaults
     */
    setupAxiosDefaults() {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = this.config.csrfToken;
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Accept'] = 'application/json';
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Search functionality
        $('#globalSearch').on('input', (e) => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.handleGlobalSearch(e.target.value);
            }, this.config.searchDelay);
        });

        // Status filter
        $('#statusFilter').on('change', (e) => {
            this.handleStatusFilter(e.target.value);
        });

        // Modal events
        $('#addNewBtn').on('click', () => this.openModal('add'));
        $('#closeModal, #cancelBtn').on('click', () => this.closeModal());
        $('#recordForm').on('submit', (e) => this.handleFormSubmit(e));

        // Delete modal events
        $('#cancelDelete').on('click', () => this.closeDeleteModal());
        $('#confirmDelete').on('click', () => this.confirmDelete());

        // Export events
        $('#exportCsv').on('click', () => this.exportData('csv'));
        $('#exportJson').on('click', () => this.exportData('json'));
        $('#exportTxt').on('click', () => this.exportData('txt'));

        // Close modals on backdrop click
        $('.modal').on('click', (e) => {
            if (e.target === e.currentTarget) {
                this.closeModal();
                this.closeDeleteModal();
            }
        });
    }

    /**
     * Load data from server
     */
    async loadData() {
        try {
            this.showLoading();
            const response = await axios.post(this.config.fetchUrl);
            this.data = response.data;
            this.filteredData = [...this.data];
            this.initializeDataTable();
            this.hideLoading();
        } catch (error) {
            console.error('Error loading data:', error);
            this.showToast('Error loading data', 'error');
            this.hideLoading();
        }
    }

    /**
     * Initialize Simple DataTable
     */
    initializeDataTable() {
        // Populate table first
        this.populateTable();

        // Initialize DataTable
        this.dataTable = new simpleDatatables.DataTable(`#${this.tableId}`, {
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
     * Populate table with data
     */
    populateTable() {
        const tbody = $(`#${this.tableId} tbody`);
        tbody.empty();

        this.filteredData.forEach(item => {
            const row = this.createTableRow(item);
            tbody.append(row);
        });
    }

    /**
     * Create table row
     */
    createTableRow(item) {
        const cells = this.config.columns.map(col => {
            if (col.key === 'actions') {
                return this.createActionButtons(item.id);
            } else if (col.type === 'date') {
                return this.formatDate(item[col.key]);
            } else if (col.key === 'status') {
                return this.createStatusBadge(item[col.key]);
            } else if (col.key === 'role') {
                return `<div class="badge badge-outline">${item[col.key]}</div>`;
            } else {
                return item[col.key] || '';
            }
        });

        return `<tr class='border-b !border-border-black/5 hover:!bg-border-black/5 dark:!border-border-white/5 dark:hover:!bg-border-white/5 transition-colors'>${cells.map(cell => `<td class='!text-text-light-secondary dark:!text-text-dark-primary'>${cell}</td>`).join('')}</tr>`;
    }

    /**
     * Create action buttons
     */
    createActionButtons(id) {
        return `
            <div class="flex gap-2">
                <button class="btn btn-sm btn-outline btn-primary edit-btn" data-id="${id}">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button class="btn btn-sm btn-outline btn-error delete-btn" data-id="${id}">
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
     * Handle global search
     */
    handleGlobalSearch(searchTerm) {
        if (this.dataTable) {
            this.dataTable.search(searchTerm);
        }
    }

    /**
     * Handle status filter
     */
    handleStatusFilter(status) {
        if (status === '') {
            this.filteredData = [...this.data];
        } else {
            this.filteredData = this.data.filter(item => item.status === status);
        }
        this.refreshTable();
    }

    /**
     * Refresh table
     */
    refreshTable() {
        if (this.dataTable) {
            this.dataTable.destroy();
        }
        this.initializeDataTable();
        this.bindActionButtons();
    }

    /**
     * Bind action buttons (edit and delete)
     */
    bindActionButtons() {
        $(document).off('click', '.edit-btn').on('click', '.edit-btn', (e) => {
            const id = parseInt($(e.currentTarget).data('id'));
            this.openModal('edit', id);
        });

        $(document).off('click', '.delete-btn').on('click', '.delete-btn', (e) => {
            const id = parseInt($(e.currentTarget).data('id'));
            this.openDeleteModal(id);
        });
    }

    /**
     * Open modal for add/edit
     */
    openModal(mode, id = null) {
        this.currentEditId = id;

        if (mode === 'add') {
            $('#modalTitle').text('Add New Record');
            $('#saveBtn').text('Add Record');
            this.resetForm();
        } else {
            $('#modalTitle').text('Edit Record');
            $('#saveBtn').text('Update Record');
            this.populateForm(id);
        }

        $('#recordModal').addClass('modal-open');
    }

    /**
     * Close modal
     */
    closeModal() {
        $('#recordModal').removeClass('modal-open');
        this.resetForm();
        this.currentEditId = null;
    }

    /**
     * Reset form
     */
    resetForm() {
        $('#recordForm')[0].reset();
        $('#recordForm .error').removeClass('error');
    }

    /**
     * Populate form with data
     */
    populateForm(id) {
        const record = this.data.find(item => item.id === id);
        if (record) {
            this.config.formFields.forEach(field => {
                $(`#${field}`).val(record[field]);
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
                response = await axios.put(`${this.config.updateUrl}/${this.currentEditId}`, formData);
            } else {
                response = await axios.post(this.config.saveUrl, formData);
            }

            this.showToast(
                isEdit ? 'Record updated successfully!' : 'Record added successfully!',
                'success'
            );

            this.closeModal();
            await this.loadData();

        } catch (error) {
            console.error('Error saving record:', error);
            this.handleFormErrors(error);
        }
    }

    /**
     * Get form data
     */
    getFormData() {
        const formData = {};
        this.config.formFields.forEach(field => {
            formData[field] = $(`#${field}`).val();
        });
        return formData;
    }

    /**
     * Handle form errors
     */
    handleFormErrors(error) {
        if (error.response && error.response.data && error.response.data.errors) {
            const errors = error.response.data.errors;
            Object.keys(errors).forEach(field => {
                $(`#${field}`).addClass('error');
            });
            this.showToast('Please check the form for errors', 'error');
        } else {
            this.showToast('Error saving record', 'error');
        }
    }

    /**
     * Open delete confirmation modal
     */
    openDeleteModal(id) {
        this.currentEditId = id;
        $('#deleteModal').addClass('modal-open');
    }

    /**
     * Close delete modal
     */
    closeDeleteModal() {
        $('#deleteModal').removeClass('modal-open');
        this.currentEditId = null;
    }

    /**
     * Confirm delete
     */
    async confirmDelete() {
        try {
            await axios.delete(`${this.config.deleteUrl}/${this.currentEditId}`);
            this.showToast('Record deleted successfully!', 'success');
            this.closeDeleteModal();
            await this.loadData();
        } catch (error) {
            console.error('Error deleting record:', error);
            this.showToast('Error deleting record', 'error');
        }
    }

    /**
     * Export data
     */
    exportData(format) {
        if (!this.dataTable) return;

        const filename = `data_export_${new Date().toISOString().split('T')[0]}`;

        switch (format) {
            case 'csv':
                this.exportToCSV(filename);
                break;
            case 'json':
                this.exportToJSON(filename);
                break;
            case 'txt':
                this.exportToTXT(filename);
                break;
        }
    }

    /**
     * Export to CSV
     */
    exportToCSV(filename) {
        const headers = this.config.columns
            .filter(col => col.key !== 'actions')
            .map(col => col.label);

        const rows = this.filteredData.map(item =>
            this.config.columns
                .filter(col => col.key !== 'actions')
                .map(col => item[col.key] || '')
        );

        const csvContent = [headers, ...rows]
            .map(row => row.map(cell => `"${cell}"`).join(','))
            .join('\n');

        this.downloadFile(csvContent, `${filename}.csv`, 'text/csv');
    }

    /**
     * Export to JSON
     */
    exportToJSON(filename) {
        const jsonContent = JSON.stringify(this.filteredData, null, 2);
        this.downloadFile(jsonContent, `${filename}.json`, 'application/json');
    }

    /**
     * Export to TXT
     */
    exportToTXT(filename) {
        const headers = this.config.columns
            .filter(col => col.key !== 'actions')
            .map(col => col.label)
            .join('\t');

        const rows = this.filteredData.map(item =>
            this.config.columns
                .filter(col => col.key !== 'actions')
                .map(col => item[col.key] || '')
                .join('\t')
        );

        const txtContent = [headers, ...rows].join('\n');
        this.downloadFile(txtContent, `${filename}.txt`, 'text/plain');
    }

    /**
     * Download file
     */
    downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    /**
     * Show loading state
     */
    showLoading() {
        $(`#${this.tableId}`).addClass('opacity-50 pointer-events-none');
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        $(`#${this.tableId}`).removeClass('opacity-50 pointer-events-none');
        this.bindActionButtons();
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        $('#toast .alert').removeClass('alert-success alert-error').addClass(alertClass);
        $('#toastMessage').text(message);
        $('#toast').fadeIn();

        setTimeout(() => {
            $('#toast').fadeOut();
        }, 3000);
    }
}