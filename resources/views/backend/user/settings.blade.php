<x-user::layout>
    <x-slot name="page_slug">settings</x-slot>

    <div class="bg-gray-50 font-sans">
        <div class="flex flex-col lg:flex-row">
            <aside class="w-64 bg-white p-8 hidden lg:block">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Settings</h2>
                <nav class="flex flex-col space-y-2"> <button onclick="showTab('notifications')" id="tab-notifications"
                        class="py-2 px-4 rounded-lg text-left text-brand-orange font-medium bg-brand-orange-light"> <i
                            class="fas fa-bell mr-2"></i>Notifications </button> <button onclick="showTab('security')"
                        id="tab-security"
                        class="py-2 px-4 rounded-lg text-left text-gray-500 hover:bg-gray-100 hover:text-gray-700"> <i
                            class="fas fa-shield-alt mr-2"></i>Security </button> <button onclick="showTab('billing')"
                        id="tab-billing"
                        class="py-2 px-4 rounded-lg text-left text-gray-500 hover:bg-gray-100 hover:text-gray-700"> <i
                            class="fas fa-credit-card mr-2"></i>Billing </button> <button onclick="showTab('api')"
                        id="tab-api"
                        class="py-2 px-4 rounded-lg text-left text-gray-500 hover:bg-gray-100 hover:text-gray-700"> <i
                            class="fas fa-key mr-2"></i>API Keys </button> </nav>
            </aside>
            <main class="flex-1 p-4 sm:p-8">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4 lg:mb-8">Settings</h1>
                    <div class="border-b border-gray-200 mb-6 lg:hidden">
                        <div class="relative"> <select id="mobile-tab-select"
                                class="block w-full py-2 px-3 border border-gray-300 rounded-lg bg-white appearance-none pr-8">
                                <option value="notifications" selected>Notifications</option>
                                <option value="security">Security</option>
                                <option value="billing">Billing</option>
                                <option value="api">API Keys</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="notifications-content" class="tab-content">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Notification Preferences</h2>
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-3 sm:mb-4">Email Notifications</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div> <label class="text-sm font-medium text-gray-700">Campaign
                                                    Updates</label>
                                                <p class="text-sm text-gray-500">Get notified when your campaigns
                                                    receive new responses</p>
                                            </div> <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" checked class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                                </div>
                                            </label>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div> <label class="text-sm font-medium text-gray-700">Weekly
                                                    Reports</label>
                                                <p class="text-sm text-gray-500">Receive weekly analytics and
                                                    performance summaries</p>
                                            </div> <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" checked class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                                </div>
                                            </label>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div> <label class="text-sm font-medium text-gray-700">Security
                                                    Alerts</label>
                                                <p class="text-sm text-gray-500">Important security notifications and
                                                    login alerts</p>
                                            </div> <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" checked class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-3 sm:mb-4">Push Notifications</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div> <label class="text-sm font-medium text-gray-700">Real-time
                                                    Updates</label>
                                                <p class="text-sm text-gray-500">Instant notifications for campaign
                                                    activities</p>
                                            </div> <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                                </div>
                                            </label>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div> <label class="text-sm font-medium text-gray-700">Marketing
                                                    Updates</label>
                                                <p class="text-sm text-gray-500">New features and promotional content
                                                </p>
                                            </div> <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-200"> <button
                                    class="w-full sm:w-auto bg-brand-orange text-white px-6 py-2 rounded-lg hover:bg-brand-orange-dark transition-colors">
                                    Save Preferences </button> </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        function showTab(
            tabId
        ) { // Handle desktop tabs (sidebar buttons) document.querySelectorAll('.tab-button').forEach(button => { button.classList.remove('border-brand-orange', 'text-brand-orange'); button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700'); }); const selectedButton = document.getElementById(`tab-${tabId}`); if (selectedButton) { selectedButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700'); selectedButton.classList.add('border-brand-orange', 'text-brand-orange'); } // Handle mobile tabs (select dropdown) const mobileSelect = document.getElementById('mobile-tab-select'); if (mobileSelect) { mobileSelect.value = tabId; } // Show/hide content document.querySelectorAll('.tab-content').forEach(tabContent => { tabContent.classList.add('hidden'); }); document.getElementById(`${tabId}-content`).classList.remove('hidden'); } // Event listener for the mobile select dropdown document.addEventListener('DOMContentLoaded', () => { const mobileTabSelect = document.getElementById('mobile-tab-select'); if (mobileTabSelect) { mobileTabSelect.addEventListener('change', (event) => { showTab(event.target.value); }); } // Initially show the 'notifications' tab showTab('notifications'); });
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-orange': '#FF5722',
                        'brand-orange-dark': '#E64A19',
                        'brand-orange-light': '#FF8A65'
                    }
                }
            }
        }
    </script>

</x-user::layout>
