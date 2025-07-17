// A static map of keywords to Lucide icon names, declared once for efficiency.
const ICON_MAP = {
    'title': 'type', 'description': 'file-text', 'text': 'file-text', 'content': 'align-left', 'note': 'notebook-pen', 'bio': 'user-pen', 'type': 'tag', 'category': 'folder', 'tag': 'tag', 'link': 'link', 'url': 'globe', 'website': 'globe', 'dashboard': 'layout-dashboard', 'home': 'home', 'setting': 'settings', 'config': 'settings', 'search': 'search', 'filter': 'filter', 'sort': 'arrow-up-down', 'menu': 'menu', 'add': 'plus', 'create': 'plus-circle', 'edit': 'edit', 'update': 'edit', 'delete': 'trash-2', 'remove': 'trash-2', 'save': 'save', 'view': 'eye', 'hide': 'eye-off', 'close': 'x', 'copy': 'copy', 'print': 'printer', 'download': 'download', 'upload': 'upload', 'logout': 'log-out', 'login': 'log-in', 'user': 'user', 'name': 'user', 'avatar': 'user-circle', 'profile': 'user-circle', 'author': 'user-pen', 'creator': 'user-plus', 'owner': 'user-cog', 'admin': 'user-shield', 'role': 'shield', 'permission': 'shield-check', 'password': 'lock', 'key': 'key-round', 'secret': 'key-round', 'email': 'mail', 'phone': 'phone', 'contact': 'contact', 'message': 'message-square', 'comment': 'message-circle', 'notification': 'bell', 'alert': 'bell', 'status': 'activity', 'state': 'toggle-right', 'success': 'check-circle', 'error': 'alert-circle', 'warning': 'alert-triangle', 'info': 'info', 'pending': 'loader-circle', 'approved': 'check-check', 'rejected': 'x-circle', 'verify': 'badge-check', 'verified': 'badge-check', 'unverified': 'badge-check', 'enable': 'toggle-right', 'disable': 'toggle-left', 'visible': 'eye', 'hidden': 'eye-off', 'date': 'calendar', 'created': 'calendar-plus', 'updated': 'calendar-pen', 'published': 'calendar-check', 'deleted': 'calendar-x', 'time': 'clock', 'duration': 'timer', 'schedule': 'calendar-clock', 'address': 'map-pin', 'location': 'map', 'city': 'map', 'country': 'flag', 'company': 'building', 'organization': 'building-2', 'department': 'building-2', 'ip': 'server', 'file': 'file', 'document': 'file-text', 'attachment': 'paperclip', 'image': 'image', 'picture': 'image', 'photo': 'camera', 'video': 'video', 'audio': 'volume-2', 'music': 'music', 'cart': 'shopping-cart', 'bag': 'shopping-bag', 'price': 'dollar-sign', 'amount': 'dollar-sign', 'money': 'wallet', 'payment': 'credit-card', 'order': 'package', 'invoice': 'receipt', 'quantity': 'hash', 'count': 'hash', 'id': 'hash'
};

/**
 * Manages a dynamic modal for displaying details from an API.
 */
class DynamicDetailsModal {
    constructor(modalId) {
        // --- Cache DOM Elements ---
        this.modalElement = document.getElementById(modalId);
        if (!this.modalElement) {
            console.error(`Modal element with ID "${modalId}" not found.`);
            return;
        }
        this.titleElement = this.modalElement.querySelector('#modal-title');
        this.contentElement = this.modalElement.querySelector('#details-content');
        this.loadingStateElement = this.modalElement.querySelector('#loading-state');
        this.errorStateElement = this.modalElement.querySelector('#error-state');
        this.modalContentCard = this.modalElement.querySelector('.glass-card'); // For click prevention

        // --- Initialize State ---
        this.apiRoute = null;
        this.id = null;
        this.config = null;
        this.data = null;
        this.title = 'Details';

        this._setupEventListeners();
    }

    /**
     * Sets up all necessary event listeners for the modal.
     * @private
     */
    _setupEventListeners() {
        // Close modal when clicking the backdrop
        this.modalElement.addEventListener('click', () => this.close());

        // Prevent modal from closing when clicking inside the content
        if (this.modalContentCard) {
            this.modalContentCard.addEventListener('click', (e) => e.stopPropagation());
        }

        // Close modal on Escape key press
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!this.modalElement.classList.contains('hidden')) {
                    this.close();
                }
                this._closeLightbox(); // Also close any open lightbox
            }
        });
    }

    /**
     * Public method to show the modal and fetch data.
     * @param {string} apiRoute - The API route with a ':id' placeholder.
     * @param {string|number} id - The ID of the item to fetch.
     * @param {string} title - The title for the modal.
     * @param {Array|null} config - The configuration for displaying details.
     */
    async show(apiRoute, id, title = 'Details', config = null) {
        // --- Store current request info for retries ---
        this.apiRoute = apiRoute;
        this.id = id;
        this.config = config;
        this.title = title;

        // --- Reset UI to loading state ---
        this._resetUI();
        this.titleElement.innerText = this.title;
        this.modalElement.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // --- Fetch and display data ---
        try {
            const url = this.apiRoute.replace(':id', this.id);
            const response = await axios.post(url);
            this.data = response.data;
            this._renderDetails(this.data, this.config);
        } catch (error) {
            console.error('Error loading details:', error);
            this._showErrorState();
        }
    }

    /**
     * Resets the modal to its initial loading view.
     * @private
     */
    _resetUI() {
        this.loadingStateElement.classList.remove('hidden');
        this.contentElement.classList.add('hidden');
        this.errorStateElement.classList.add('hidden');
    }

    /**
     * Renders the fetched data into the modal body.
     * @param {object} data - The data object from the API.
     * @param {Array|null} config - The display configuration.
     * @private
     */
    _renderDetails(data, config) {
        try {
            const commonDetailConfig = [
                { label: 'Created By', key: 'creater_name' },
                { label: 'Created Date', key: 'created_at_formatted' },
                { label: 'Updated By', key: 'updater_name' },
                { label: 'Updated Date', key: 'updated_at_formatted' },
            ];

            let finalConfig = config ? [...config, ...commonDetailConfig] : commonDetailConfig;

            const detailItemsHtml = finalConfig.map(item => {
                const label = item.label || item.key;
                let rawValue = data[item.key];
                const icon = item.icon || this._getIconForKey(item.key);
                const badgeColor = data[item.label_color] || 'badge-secondary';

                let displayValue;

                if (item.loop && Array.isArray(rawValue)) {
                    displayValue = rawValue.map(subItem =>
                        this._formatValue(subItem[item.loopKey], item.key, item.type, badgeColor)
                    ).join(', ');
                } else {
                    displayValue = this._formatValue(rawValue, item.key, item.type, badgeColor);
                }

                return `
                    <div class="detail-item flex items-center justify-between py-4 px-4 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="${icon}" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">${label}</span>
                        </div>
                        <div class="text-right ml-4">${displayValue}</div>
                    </div>
                `;
            }).join('');

            this.contentElement.innerHTML = detailItemsHtml;
            this.loadingStateElement.classList.add('hidden');
            this.contentElement.classList.remove('hidden');

            this._refreshIcons();
        } catch (error) {
            console.error('Error rendering details:', error);
            this._showErrorState();
        }
    }

    /**
     * Formats a value for display based on its type or key.
     * @private
     */
    _formatValue(value, key, type, badgeColor) {
        if (value === null || value === undefined || value === '') {
            return '<span class="text-gray-400 dark:text-gray-500 italic">N/A</span>';
        }

        switch (type) {
            case 'image':
                return `
                    <div class="relative group cursor-pointer" onclick="detailsModal._openImageLightbox('${value}')">
                        <img src="${value}" alt="Preview" class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow-md">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                            <i data-lucide="zoom-in" class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                        </div>
                    </div>`;
            case 'video':
                return `
                    <div class="relative group cursor-pointer" onclick="detailsModal._openVideoLightbox('${value}')">
                        <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 flex items-center justify-center">
                            <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-40 rounded-lg flex items-center justify-center">
                                <i data-lucide="play" class="w-8 h-8 text-white opacity-80 group-hover:opacity-100 transition-opacity duration-200"></i>
                            </div>
                            <i data-lucide="video" class="w-8 h-8 text-gray-500 dark:text-gray-400"></i>
                        </div>
                    </div>`;
            case 'badge':
                return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium badge badge-soft ${badgeColor}">${value}</span>`;
        }

        const keyLower = key.toLowerCase();
        if (keyLower.includes('email')) {
            return `<a href="mailto:${value}" class="text-blue-600 dark:text-blue-400 hover:underline">${value}</a>`;
        } else if (keyLower.includes('phone')) {
            return `<a href="tel:${value}" class="text-blue-600 dark:text-blue-400 hover:underline">${value}</a>`;
        }

        return `<span class="text-gray-900 dark:text-white font-medium">${value}</span>`;
    }

    /**
     * Gets a default icon name from the ICON_MAP based on a key.
     * @private
     */
    _getIconForKey(key) {
        const keyLower = String(key).toLowerCase();
        for (const keyword in ICON_MAP) {
            if (keyLower.includes(keyword)) {
                return ICON_MAP[keyword];
            }
        }
        return 'info'; // Default fallback icon
    }

    /**
     * Displays the error state in the modal.
     * @private
     */
    _showErrorState() {
        this.loadingStateElement.classList.add('hidden');
        this.contentElement.classList.add('hidden');
        this.errorStateElement.classList.remove('hidden');
        this._refreshIcons();
    }

    /**
     * Closes the modal and resets its state.
     */
    close() {
        this.modalElement.classList.add('hidden');
        document.body.style.overflow = 'auto';

        // Clear state
        this.apiRoute = null;
        this.id = null;
        this.config = null;
        this.data = null;
    }

    /**
     * Retries the last failed API call.
     */
    retry() {
        if (this.apiRoute && this.id) {
            this.errorStateElement.classList.add('hidden');
            this.loadingStateElement.classList.remove('hidden');
            setTimeout(() => {
                this.show(this.apiRoute, this.id, this.title, this.config);
            }, 300);
        }
    }

    /**
     * Exports the current modal data to a CSV file.
     */
    exportAsCSV() {
        if (!this.data) {
            alert('No data available to export.');
            return;
        }
        try {
            let csvContent = 'Key,Value\n';
            csvContent += Object.entries(this.data).map(([key, value]) => {
                const cleanValue = String(value ?? 'N/A').replace(/"/g, '""');
                return `"${key}","${cleanValue}"`;
            }).join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `details-${this.id}-${Date.now()}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        } catch (error) {
            console.error('CSV export failed:', error);
            alert('Export to CSV failed.');
        }
    }

    /**
     * Opens a lightbox for displaying an image.
     * @private
     */
    _openImageLightbox(src) {
        this._closeLightbox(); // Close any existing lightbox first
        const lightboxHtml = `
            <div id="media-lightbox" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm animate-fade-in" onclick="detailsModal._closeLightbox()">
                <div class="relative max-w-4xl max-h-[90vh] mx-4" onclick="event.stopPropagation()">
                    <button onclick="detailsModal._closeLightbox()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10"><i data-lucide="x" class="w-8 h-8"></i></button>
                    <img src="${src}" alt="Full Size Preview" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl animate-scale-in">
                </div>
            </div>`;
        document.body.insertAdjacentHTML('beforeend', lightboxHtml);
        document.body.style.overflow = 'hidden'; // Ensure scroll is disabled
        this._refreshIcons();
    }

    /**
     * Opens a lightbox for displaying a video.
     * @private
     */
    _openVideoLightbox(src) {
        this._closeLightbox(); // Close any existing lightbox first
        const lightboxHtml = `
            <div id="media-lightbox" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm animate-fade-in" onclick="detailsModal._closeLightbox()">
                <div class="relative max-w-4xl w-full mx-4 bg-black rounded-lg overflow-hidden shadow-2xl animate-scale-in" onclick="event.stopPropagation()">
                    <button onclick="detailsModal._closeLightbox()" class="absolute top-2 right-2 z-10 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                    <video class="w-full h-auto max-h-[90vh]" controls autoplay><source src="${src}">Your browser does not support the video tag.</video>
                </div>
            </div>`;
        document.body.insertAdjacentHTML('beforeend', lightboxHtml);
        document.body.style.overflow = 'hidden'; // Ensure scroll is disabled
        this._refreshIcons();
    }

    /**
     * Closes any active media lightbox.
     * @private
     */
    _closeLightbox() {
        const lightbox = document.getElementById('media-lightbox');
        if (lightbox) {
            lightbox.remove();
        }
        // Restore body scroll only if the main modal is also hidden
        if (this.modalElement.classList.contains('hidden')) {
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * Re-initializes Lucide icons if the library is available.
     * @private
     */
    _refreshIcons() {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }
}


// --- Global Instantiation and Legacy Function Wrapper ---

// Create a single instance of the modal to be reused.
const detailsModal = new DynamicDetailsModal('details_modal');

// A simple, legacy-compatible wrapper function to maintain the original call signature.
// This ensures you don't have to change your existing code.
function showDetailsModal(apiRoute, id, title, config) {
    if (detailsModal) {
        detailsModal.show(apiRoute, id, title, config);
    }
}

// Global functions for inline `onclick` events in the modal's HTML.
// These need to be in the global scope to be accessible.
function retryLoadDetails() {
    detailsModal.retry();
}

function closeDetailsModal() {
    detailsModal.close();
}

function exportDetailsAsCSV() {
    detailsModal.exportAsCSV();
}