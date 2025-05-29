/**
 * DataTableManager - A reusable, professional DataTable implementation
 * Features: CRUD operations, search, sort, pagination, export, responsive design
 * Dependencies: jQuery, Axios
 */
class DataTableManager {
    constructor(config) {
        this.config = {
            // Default configuration
            perPage: 10,
            currentPage: 1,
            sortColumn: 'id',
            sortDirection: 'asc',
            search: '',
            data: [],
            filteredData: [],
            totalRecords: 0,
            isEditing: false,
            editingId: null,
            ...config
        };

        this.initializeElements();
        this.bindEvents();
        this.loadData();
    }

    /**
     * Initialize DOM elements
     */
    initializeElements() {
        this.elements = {
            // Table elements
            table: $('#dataTable'),
            tableBody: $('#tableBody'),
            loadingState: $('#loadingState'),
            emptyState: $('#emptyState'),

            // Controls
            searchInput: $('#searchInput'),
            perPageSelect: $('#perPage'),
            exportBtn: $('#exportBtn'),
            exportDropdown: $('#exportDropdown'),

            // Pagination
            pagination: $('#pagination'),
            prevBtn: $('#prevBtn'),
            nextBtn: $('#nextBtn'),
            pageNumbers: $('#pageNumbers'),
            showingFrom: $('#showingFrom'),
            showingTo: $('#showingTo'),
            totalRecords: $('#totalRecords'),

            // Modals
            recordModal: $('#recordModal'),
            deleteModal: $('#deleteModal'),
            recordForm: $('#recordForm'),
            modalTitle: $('#modalTitle'),
            saveBtn: $('#saveBtn'),

            // Buttons
            addNewBtn: $('#addNewBtn'),
            addFirstBtn: $('#addFirstBtn'),
            closeModal: $('#closeModal'),
            cancelBtn: $('#cancelBtn'),
            confirmDelete: $('#confirmDelete'),
            cancelDelete: $('#cancelDelete'),

            // Toast
            toast: $('#toast'),
            toastMessage: $('#toastMessage'),
            toastIcon: $('#toastIcon'),
            closeToast: $('#closeToast')
        };
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Search functionality
        this.elements.searchInput.on('input', (e) => {
            this.config.search = e.target.value;
            this.config.currentPage = 1;
            this.filterAndRenderData();
        });

        // Per page selection
        this.elements.perPageSelect.on('change', (e) => {
            this.config.perPage = parseInt(e.target.value);
            this.config.currentPage = 1;
            this.filterAndRenderData();
        });

        // Column sorting
        $('th[data-column]').on('click', (e) => {
            const column = $(e.currentTarget).data('column');
            if (this.config.sortColumn === column) {
                this.config.sortDirection = this.config.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.config.sortColumn = column;
                this.config.sortDirection = 'asc';
            }
            this.filterAndRenderData();
            this.updateSortIcons();
        });

        // Export functionality
        this.elements.exportBtn.on('click', (e) => {
            e.stopPropagation();
            this.elements.exportDropdown.toggleClass('hidden');
        });

        // Close export dropdown when clicking outside
        $(document).on('click', (e) => {
            if (!$(e.target).closest('#exportBtn, #exportDropdown').length) {
                this.elements.exportDropdown.addClass('hidden');
            }
        });

        // Export buttons
        $('[data-export]').on('click', (e) => {
            const format = $(e.target).data('export');
            this.exportData(format);
            this.elements.exportDropdown.addClass('hidden');
        });

        // Modal controls
        this.elements.addNewBtn.on('click', () => this.openModal());
        this.elements.addFirstBtn.on('click', () => this.openModal());
        this.elements.closeModal.on('click', () => this.closeModal());
        this.elements.cancelBtn.on('click', () => this.closeModal());

        // Form submission
        this.elements.recordForm.on('submit', (e) => {
            e.preventDefault();
            this.saveRecord();
        });

        // Delete confirmation
        this.elements.confirmDelete.on('click', () => this.deleteRecord());
        this.elements.cancelDelete.on('click', () => this.closeDeleteModal());

        // Toast close
        this.elements.closeToast.on('click', () => this.hideToast());

        // Pagination
        this.elements.prevBtn.on('click', () => this.changePage(this.config.currentPage - 1));
        this.elements.nextBtn.on('click', () => this.changePage(this.config.currentPage + 1));

        // Close modals on backdrop click
        this.elements.recordModal.on('click', (e) => {
            if (e.target === e.currentTarget) this.closeModal();
        });
        this.elements.deleteModal.on('click', (e) => {
            if (e.target === e.currentTarget) this.closeDeleteModal();
        });
    }

    /**
     * Load data from server
     */
    async loadData() {
        try {
            this.showLoading(true);
            const response = await axios.post(this.config.fetchUrl, {
                page: this.config.currentPage,
                per_page: this.config.perPage,
                search: this.config.search,
                sort_column: this.config.sortColumn,
                sort_direction: this.config.sortDirection
            }, {
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            this.config.data = response.data.data || [];
            this.config.totalRecords = response.data.total || 0;
            this.filterAndRenderData();
        } catch (error) {
            console.error('Error loading data:', error);
            this.showToast('Error loading data', 'error');
            this.showEmptyState();
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Filter and render data based on current filters
     */
    filterAndRenderData() {
        let filteredData = [...this.config.data];

        // Apply search filter
        if (this.config.search) {
            const searchTerm = this.config.search.toLowerCase();
            filteredData = filteredData.filter(item => {
                return this.config.columns.some(column => {
                    if (column.searchable && item[column.key]) {
                        return item[column.key].toString().toLowerCase().includes(searchTerm);
                    }
                    return false;
                });
            });
        }

        // Apply sorting
        filteredData.sort((a, b) => {
            const aVal = a[this.config.sortColumn] || '';
            const bVal = b[this.config.sortColumn] || '';

            if (this.config.sortDirection === 'asc') {
                return aVal > bVal ? 1 : -1;
            } else {
                return aVal < bVal ? 1 : -1;
            }
        });

        this.config.filteredData = filteredData;
        this.renderTable();
        this.renderPagination();
    }

    /**
     * Render table data
     */
    renderTable() {
        const startIndex = (this.config.currentPage - 1) * this.config.perPage;
        const endIndex = startIndex + this.config.perPage;
        const pageData = this.config.filteredData.slice(startIndex, endIndex);

        if (pageData.length === 0) {
            this.showEmptyState();
            return;
        }

        this.hideEmptyState();

        const tbody = this.elements.tableBody;
        tbody.empty();

        pageData.forEach(item => {
            const row = this.createTableRow(item);
            tbody.append(row);
        });

        this.bindRowEvents();
    }

    /**
     * Create a table row
     */
    createTableRow(item) {
        const row = $('<tr>').addClass('hover:bg-gray-50 dark:hover:bg-gray-700');

        this.config.columns.forEach(column => {
            const cell = $('<td>').addClass('px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white');

            if (column.key === 'actions') {
                cell.html(this.createActionButtons(item.id));
            } else if (column.key === 'status') {
                cell.html(this.createStatusBadge(item[column.key]));
            } else if (column.type === 'date' && item[column.key]) {
                cell.text(new Date(item[column.key]).toLocaleDateString());
            } else {
                cell.text(item[column.key] || '');
            }

            row.append(cell);
        });

        return row;
    }

    /**
     * Create action buttons for each row
     */
    createActionButtons(id) {
        return `
            <div class="flex items-center space-x-2">
                <button class="edit-btn text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" data-id="${id}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button class="delete-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" data-id="${id}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
    }

    /**
     * Create status badge
     */
    createStatusBadge(status) {
        const statusClass = `status-${status.toLowerCase()}`;
        return `<span class="status-badge ${statusClass}">${status}</span>`;
    }

    /**
     * Bind events to table rows
     */
    bindRowEvents() {
        $('.edit-btn').on('click', (e) => {
            const id = $(e.currentTarget).data('id');
            this.openModal(id);
        });

        $('.delete-btn').on('click', (e) => {
            const id = $(e.currentTarget).data('id');
            this.openDeleteModal(id);
        });
    }

    /**
     * Render pagination
     */
    renderPagination() {
        const totalPages = Math.ceil(this.config.filteredData.length / this.config.perPage);
        const startRecord = (this.config.currentPage - 1) * this.config.perPage + 1;
        const endRecord = Math.min(this.config.currentPage * this.config.perPage, this.config.filteredData.length);

        // Update showing info
        this.elements.showingFrom.text(this.config.filteredData.length > 0 ? startRecord : 0);
        this.elements.showingTo.text(endRecord);
        this.elements.totalRecords.text(this.config.filteredData.length);

        // Update pagination buttons
        this.elements.prevBtn.prop('disabled', this.config.currentPage <= 1);
        this.elements.nextBtn.prop('disabled', this.config.currentPage >= totalPages);

        // Generate page numbers
        this.generatePageNumbers(totalPages);
    }

    /**
     * Generate page numbers for pagination
     */
    generatePageNumbers(totalPages) {
        const pageNumbers = this.elements.pageNumbers;
        pageNumbers.empty();

        const maxVisiblePages = 5;
        let startPage = Math.max(1, this.config.currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage < maxVisiblePages - 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = $(`
                <button class="relative inline-flex items-center px-4 py-2 border text-sm font-medium 
                    ${i === this.config.currentPage
                    ? 'z-10 bg-blue-50 dark:bg-blue-900 border-blue-500 text-blue-600 dark:text-blue-300'
                    : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                }">
                    ${i}
                </button>
            `);

            pageBtn.on('click', () => this.changePage(i));
            pageNumbers.append(pageBtn);
        }
    }

    /**
     * Change current page
     */
    changePage(page) {
        const totalPages = Math.ceil(this.config.filteredData.length / this.config.perPage);
        if (page >= 1 && page <= totalPages) {
            this.config.currentPage = page;
            this.renderTable();
            this.renderPagination();
        }
    }

    /**
     * Update sort icons in table headers
     */
    updateSortIcons() {
        $('th[data-column] svg').removeClass('text-blue-500').addClass('text-gray-400');
        $(`th[data-column="${this.config.sortColumn}"] svg`)
            .removeClass('text-gray-400')
            .addClass('text-blue-500');
    }

    /**
     * Open modal for add/edit
     */
    openModal(id = null) {
        this.config.isEditing = !!id;
        this.config.editingId = id;

        if (this.config.isEditing) {
            this.elements.modalTitle.text('Edit Admin');
            this.elements.saveBtn.text('Update');
            this.populateForm(id);
        } else {
            this.elements.modalTitle.text('Add New Admin');
            this.elements.saveBtn.text('Save');
            this.clearForm();
        }

        this.elements.recordModal.removeClass('hidden');
    }

    /**
     * Close modal
     */
    closeModal() {
        this.elements.recordModal.addClass('hidden');
        this.clearForm();
        this.config.isEditing = false;
        this.config.editingId = null;
    }

    /**
     * Populate form with existing data
     */
    populateForm(id) {
        const record = this.config.data.find(item => item.id == id);
        if (record) {
            this.config.formFields.forEach(field => {
                $(`#${field}`).val(record[field]);
            });
        }
    }

    /**
     * Clear form fields
     */
    clearForm() {
        this.config.formFields.forEach(field => {
            $(`#${field}`).val('');
        });
    }

    /**
     * Save record (create or update)
     */
    async saveRecord() {
        try {
            const formData = {};
            this.config.formFields.forEach(field => {
                formData[field] = $(`#${field}`).val();
            });

            let url, method;
            if (this.config.isEditing) {
                url = this.config.updateUrl.replace(':id', this.config.editingId);
                method = 'PUT';
            } else {
                url = this.config.saveUrl;
                method = 'POST';
            }

            const response = await axios({
                method: method,
                url: url,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            this.showToast(
                this.config.isEditing ? 'Record updated successfully' : 'Record created successfully',
                'success'
            );

            this.closeModal();
            this.loadData();
        } catch (error) {
            console.error('Error saving record:', error);
            this.showToast('Error saving record', 'error');
        }
    }

    /**
     * Open delete confirmation modal
     */
    openDeleteModal(id) {
        this.config.deletingId = id;
        this.elements.deleteModal.removeClass('hidden');
    }

    /**
     * Close delete confirmation modal
     */
    closeDeleteModal() {
        this.elements.deleteModal.addClass('hidden');
        this.config.deletingId = null;
    }

    /**
     * Delete record
     */
    async deleteRecord() {
        try {
            const url = this.config.deleteUrl.replace(':id', this.config.deletingId);

            await axios.delete(url, {
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken
                }
            });

            this.showToast('Record deleted successfully', 'success');
            this.closeDeleteModal();
            this.loadData();
        } catch (error) {
            console.error('Error deleting record:', error);
            this.showToast('Error deleting record', 'error');
        }
    }

    /**
     * Export data in specified format
     */
    exportData(format) {
        try {
            const exportData = this.config.filteredData.map(item => {
                const exportItem = {};
                this.config.columns.forEach(column => {
                    if (column.key !== 'actions') {
                        exportItem[column.label] = item[column.key] || '';
                    }
                });
                return exportItem;
            });

            let content, mimeType, extension;

            switch (format) {
                case 'csv':
                    content = this.convertToCSV(exportData);
                    mimeType = 'text/csv';
                    extension = 'csv';
                    break;
                case 'json':
                    content = JSON.stringify(exportData, null, 2);
                    mimeType = 'application/json';
                    extension = 'json';
                    break;
                case 'txt':
                    content = this.convertToTXT(exportData);
                    mimeType = 'text/plain';
                    extension = 'txt';
                    break;
                default:
                    throw new Error('Unsupported export format');
            }

            this.downloadFile(content, `export_${new Date().toISOString().split('T')[0]}.${extension}`, mimeType);
            this.showToast(`Data exported as ${format.toUpperCase()}`, 'success');
        } catch (error) {
            console.error('Export error:', error);
            this.showToast('Error exporting data', 'error');
        }
    }

    /**
     * Convert data to CSV format
     */
    convertToCSV(data) {
        if (data.length === 0) return '';

        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(item =>
                headers.map(header =>
                    `"${String(item[header]).replace(/"/g, '""')}"`
                ).join(',')
            )
        ].join('\n');

        return csvContent;
    }

    /**
     * Convert data to TXT format
     */
    convertToTXT(data) {
        if (data.length === 0) return '';

        const headers = Object.keys(data[0]);
        const maxWidths = headers.map(header =>
            Math.max(header.length, ...data.map(item => String(item[header]).length))
        );

        const separator = '+' + maxWidths.map(width => '-'.repeat(width + 2)).join('+') + '+';
        const headerRow = '|' + headers.map((header, i) => ` ${header.padEnd(maxWidths[i])} `).join('|') + '|';

        const dataRows = data.map(item =>
            '|' + headers.map((header, i) => ` ${String(item[header]).padEnd(maxWidths[i])} `).join('|') + '|'
        );

        return [separator, headerRow, separator, ...dataRows, separator].join('\n');
    }

    /**
     * Download file
     */
    downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();

        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    /**
     * Show loading state
     */
    showLoading(show) {
        if (show) {
            this.elements.loadingState.removeClass('hidden');
            this.elements.tableBody.addClass('hidden');
            this.elements.emptyState.addClass('hidden');
        } else {
            this.elements.loadingState.addClass('hidden');
            this.elements.tableBody.removeClass('hidden');
        }
    }

    /**
     * Show empty state
     */
    showEmptyState() {
        this.elements.emptyState.removeClass('hidden');
        this.elements.tableBody.addClass('hidden');
        this.elements.pagination.addClass('hidden');
    }

    /**
     * Hide empty state
     */
    hideEmptyState() {
        this.elements.emptyState.addClass('hidden');
        this.elements.pagination.removeClass('hidden');
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'success') {
        const toastColors = {
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600',
            info: 'text-blue-600'
        };

        this.elements.toastIcon.attr('class', `h-6 w-6 ${toastColors[type] || toastColors.success}`);
        this.elements.toastMessage.text(message);
        this.elements.toast.removeClass('hidden');

        // Auto-hide after 5 seconds
        setTimeout(() => {
            this.hideToast();
        }, 5000);
    }

    /**
     * Hide toast notification
     */
    hideToast() {
        this.elements.toast.addClass('hidden');
    }

    /**
     * Refresh data
     */
    refresh() {
        this.loadData();
    }

    /**
     * Get current configuration
     */
    getConfig() {
        return { ...this.config };
    }

    /**
     * Update configuration
     */
    updateConfig(newConfig) {
        this.config = { ...this.config, ...newConfig };
    }
}