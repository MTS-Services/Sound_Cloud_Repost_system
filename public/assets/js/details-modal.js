// Global variables to store current data
let currentModalData = null;
let currentModalConfig = null;
let currentApiRoute = null;
let currentId = null;

function showDetailsModal(apiRouteWithPlaceholder, id, title = 'Details', detailsConfig = null) {
    const url = apiRouteWithPlaceholder.replace(':id', id);
    const modalTitleElement = document.getElementById('modal-title');
    const modal = document.getElementById('details_modal');

    // Store current values for retry functionality
    currentApiRoute = apiRouteWithPlaceholder;
    currentId = id;
    currentModalConfig = detailsConfig;

    const commonDetailConfig = [
        { label: 'Created By', key: 'created_by' },
        { label: 'Created Date', key: 'created_at' },
        { label: 'Updated By', key: 'updated_by' },
        { label: 'Updated Date', key: 'updated_at' },
    ]

    if (detailsConfig) {
        detailsConfig.push(...commonDetailConfig);
    } else {
        detailsConfig = commonDetailConfig;
    }

    // Set modal title immediately
    modalTitleElement.innerText = title;

    // Show modal with loading state
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Reset states
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('details-content').classList.add('hidden');
    document.getElementById('error-state').classList.add('hidden');

    // Make API call
    axios.post(url)
        .then(res => {
            const data = res.data;
            currentModalData = data;
            loadModalDetails(data, detailsConfig);
        })
        .catch(err => {
            console.error('Error loading details:', err);
            showModalError();
        });
}

function loadModalDetails(data, detailsConfig) {
    try {
        let html = '';

        if (detailsConfig && Array.isArray(detailsConfig)) {
            detailsConfig.forEach(item => {
                const label = item.label || item.key;
                let rawValue = data[item.key];

                let formattedValue;

                if (item.loop && Array.isArray(rawValue)) {
                    // Looping through nested items (e.g., permissions)
                    formattedValue = rawValue.map(subItem =>
                        formatValue(subItem[item.loopKey], item.key)
                    ).join(', ');
                } else {
                    // Single value
                    formattedValue = formatValue(rawValue, item.key);
                }
                const icon = item.icon || getDefaultIcon(item.key);

                // Format different types of values
                let displayValue = formattedValue;

                html += `
                    <div class="detail-item flex items-center justify-between py-4 px-4 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="${icon}" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">${label}</span>
                        </div>
                        <div class="text-right ml-4">
                            ${displayValue}
                        </div>
                    </div>
                `;
            });
        } else {
            // Fallback: show everything if no specific config is provided
            for (const [key, value] of Object.entries(data)) {
                const formattedKey = key
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, l => l.toUpperCase());

                const icon = getDefaultIcon(key);
                const displayValue = formatValue(value, key);

                html += `
                    <div class="detail-item flex items-center justify-between py-4 px-4 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="${icon}" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">${formattedKey}</span>
                        </div>
                        <div class="text-right ml-4">
                            ${displayValue}
                        </div>
                    </div>
                `;
            }
        }

        document.getElementById('details-content').innerHTML = html;
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('details-content').classList.remove('hidden');

        // Reinitialize Lucide icons if available
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    } catch (error) {
        console.error('Error rendering details:', error);
        showModalError();
    }
}

function formatValue(value, key) {
    if (value === null || value === undefined || value === '') {
        return '<span class="text-gray-400 dark:text-gray-500 italic">N/A</span>';
    }

    // Format based on key type
    if (key.toLowerCase().includes('status')) {
        return formatStatus(value);
    } else if (key.toLowerCase().includes('email')) {
        return `<a href="mailto:${value}" class="text-blue-600 dark:text-blue-400 hover:underline">${value}</a>`;
    } else if (key.toLowerCase().includes('phone')) {
        return `<a href="tel:${value}" class="text-blue-600 dark:text-blue-400 hover:underline">${value}</a>`;
    }//  else if (key.toLowerCase().includes('date') || key.toLowerCase().includes('created_at') || key.toLowerCase().includes('updated_at')) {
    //     return formatDate(value);
    // } 
    else {
        return `<span class="text-gray-900 dark:text-white font-medium">${value}</span>`;
    }
}

function formatStatus(status) {
    const statusStr = String(status).toLowerCase();
    let colorClass = '';

    if (statusStr === 'active' || statusStr === '1' || statusStr === 'enabled') {
        colorClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
    } else if (statusStr === 'inactive' || statusStr === '0' || statusStr === 'disabled') {
        colorClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
    } else if (statusStr === 'pending') {
        colorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
    } else {
        colorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }

    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colorClass}">${status}</span>`;
}

// function formatDate(dateString) {
//     try {
//         const date = new Date(dateString);
//         const options = {
//             year: 'numeric',
//             month: 'short',
//             day: 'numeric',
//             hour: '2-digit',
//             minute: '2-digit'
//         };
//         return `<span class="text-gray-900 dark:text-white font-medium">${date.toLocaleDateString('en-US', options)}</span>`;
//     } catch (error) {
//         return `<span class="text-gray-900 dark:text-white font-medium">${dateString}</span>`;
//     }
// }

function getDefaultIcon(key) {
    const keyLower = key.toLowerCase();

    if (keyLower.includes('permission')) return 'shield-check';
    if (keyLower.includes('role')) return 'shield';
    if (keyLower.includes('name')) return 'user';
    if (keyLower.includes('email')) return 'mail';
    if (keyLower.includes('phone')) return 'phone';
    if (keyLower.includes('status')) return 'activity';
    if (keyLower.includes('created')) return 'calendar';
    if (keyLower.includes('updated')) return 'edit';
    if (keyLower.includes('date')) return 'calendar';
    if (keyLower.includes('time')) return 'clock';
    if (keyLower.includes('department')) return 'building';
    if (keyLower.includes('address')) return 'map-pin';
    if (keyLower.includes('id')) return 'hash';

    return 'info';
}

function showModalError() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('details-content').classList.add('hidden');
    document.getElementById('error-state').classList.remove('hidden');

    // Reinitialize Lucide icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function retryLoadDetails() {
    if (currentApiRoute && currentId) {
        document.getElementById('error-state').classList.add('hidden');
        document.getElementById('loading-state').classList.remove('hidden');

        setTimeout(() => {
            showDetailsModal(currentApiRoute, currentId, document.getElementById('modal-title').innerText, currentModalConfig);
        }, 500);
    }
}

function closeDetailsModal() {
    const modal = document.getElementById('details_modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';

    // Clear stored data
    currentModalData = null;
    currentModalConfig = null;
    currentApiRoute = null;
    currentId = null;
}

// function exportDetails() {
//     if (!currentModalData) return;

//     try {
//         const dataStr = JSON.stringify(currentModalData, null, 2);
//         const dataBlob = new Blob([dataStr], { type: 'application/json' });
//         const url = URL.createObjectURL(dataBlob);
//         const link = document.createElement('a');
//         link.href = url;
//         link.download = `details-${Date.now()}.json`;
//         link.click();
//         URL.revokeObjectURL(url);
//     } catch (error) {
//         console.error('Export failed:', error);
//         alert('Export failed. Please try again.');
//     }
// }


function exportDetailsAsCSV() {
    if (!currentModalData) return;

    try {
        let csv = 'Key,Value\n';
        for (const [key, value] of Object.entries(currentModalData)) {
            csv += `"${key}","${value ?? 'N/A'}"\n`;
        }

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `details-${Date.now()}.csv`;
        link.click();
        URL.revokeObjectURL(url);
    } catch (error) {
        console.error('CSV export failed:', error);
        alert('Export to CSV failed. Please try again.');
    }
}


// Close modal on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('details_modal');
        if (!modal.classList.contains('hidden')) {
            closeDetailsModal();
        }
    }
});

// Prevent modal from closing when clicking inside the modal content
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('details_modal');
    if (modal) {
        const modalContent = modal.querySelector('.glass-card');
        if (modalContent) {
            modalContent.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    }
});