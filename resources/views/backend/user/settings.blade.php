<x-user::layout>
    <x-slot name="page_slug">settings</x-slot>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="flex space-x-8">
            <button onclick="showTab('notifications')" id="tab-notifications"
                class="tab-button py-2 px-1 border-b-2 font-medium border-brand-orange text-brand-orange">
                <i class="fas fa-bell mr-2"></i>Notifications
            </button>
            <button onclick="showTab('security')" id="tab-security"
                class="tab-button py-2 px-1 border-b-2 hover:text-gray-700 border-transparent text-gray-500">
                <i class="fas fa-shield-alt mr-2"></i>Security
            </button>
            <button onclick="showTab('billing')" id="tab-billing"
                class="tab-button py-2 px-1 border-b-2 hover:text-gray-700 border-transparent text-gray-500">
                <i class="fas fa-credit-card mr-2"></i>Billing
            </button>
            <button onclick="showTab('api')" id="tab-api"
                class="tab-button py-2 px-1 border-b-2 hover:text-gray-700 border-transparent text-gray-500">
                <i class="fas fa-key mr-2"></i>API Keys
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div id="tab-content-container">
        <div id="notifications" class="tab-content p-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Notification Preferences</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Email Notifications</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Campaign Updates</label>
                                    <p class="text-sm text-gray-500">Get notified when your campaigns receive new
                                        responses</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked="" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Weekly Reports</label>
                                    <p class="text-sm text-gray-500">Receive weekly analytics and performance summaries
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked="" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Security Alerts</label>
                                    <p class="text-sm text-gray-500">Important security notifications and login alerts
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked="" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Push Notifications</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Real-time Updates</label>
                                    <p class="text-sm text-gray-500">Instant notifications for campaign activities</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Marketing Updates</label>
                                    <p class="text-sm text-gray-500">New features and promotional content</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button
                        class="bg-brand-orange text-white px-6 py-2 rounded-lg hover:bg-brand-orange-dark transition-colors">
                        Save Preferences
                    </button>
                </div>
            </div>
        </div>
        <div id="security" class="tab-content p-4 hidden">
            <div class="space-y-6">
                <!-- Password Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Password & Authentication</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-orange focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-orange focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-orange focus:border-transparent">
                        </div>

                        <button
                            class="bg-brand-orange text-white px-6 py-2 rounded-lg hover:bg-brand-orange-dark transition-colors">
                            Update Password
                        </button>
                    </div>
                </div>

                <!-- Two-Factor Authentication -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Two-Factor Authentication</h2>

                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Enable 2FA</h3>
                            <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-orange">
                            </div>
                        </label>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Use an authenticator app like Google Authenticator or Authy to
                            generate verification codes.</p>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Active Sessions</h2>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-desktop text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Current Session</h4>
                                    <p class="text-sm text-gray-500">Chrome on Windows • Bangladesh</p>
                                    <p class="text-xs text-gray-400">Last active: Now</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Mobile App</h4>
                                    <p class="text-sm text-gray-500">iOS App • Bangladesh</p>
                                    <p class="text-xs text-gray-400">Last active: 2 hours ago</p>
                                </div>
                            </div>
                            <button class="text-red-600 hover:text-red-800 text-sm font-medium">Revoke</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="billing" class="tab-content p-4 hidden">
            <div class="space-y-6">
                <!-- Current Plan -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Current Plan</h2>

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Free Plan</h3>
                            <p class="text-sm text-gray-500">0 Credits remaining</p>
                        </div>
                        <button
                            class="bg-brand-orange text-white px-6 py-2 rounded-lg hover:bg-brand-orange-dark transition-colors">
                            Upgrade Plan
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">0</div>
                            <div class="text-sm text-gray-500">Credits Used</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">5</div>
                            <div class="text-sm text-gray-500">Campaigns</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">∞</div>
                            <div class="text-sm text-gray-500">Storage</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Payment Methods</h2>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fab fa-cc-visa text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Visa ending in 4242</h4>
                                    <p class="text-sm text-gray-500">Expires 12/2025</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Default</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>

                        <button
                            class="w-full p-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-brand-orange hover:text-brand-orange transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Payment Method
                        </button>
                    </div>
                </div>

                <!-- Billing History -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Billing History</h2>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border-b border-gray-100">
                            <div>
                                <h4 class="font-medium text-gray-900">Premium Plan - Monthly</h4>
                                <p class="text-sm text-gray-500">Jan 15, 2024</p>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-gray-900">$29.00</div>
                                <button
                                    class="text-sm text-brand-orange hover:text-brand-orange-dark">Download</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border-b border-gray-100">
                            <div>
                                <h4 class="font-medium text-gray-900">Premium Plan - Monthly</h4>
                                <p class="text-sm text-gray-500">Dec 15, 2023</p>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-gray-900">$29.00</div>
                                <button
                                    class="text-sm text-brand-orange hover:text-brand-orange-dark">Download</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4">
                            <div>
                                <h4 class="font-medium text-gray-900">Premium Plan - Monthly</h4>
                                <p class="text-sm text-gray-500">Nov 15, 2023</p>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-gray-900">$29.00</div>
                                <button
                                    class="text-sm text-brand-orange hover:text-brand-orange-dark">Download</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="api" class="tab-content p-4 hidden">
            <div class="space-y-6">
                <!-- API Keys Management -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">API Keys</h2>
                        <button
                            class="bg-brand-orange text-white px-4 py-2 rounded-lg hover:bg-brand-orange-dark transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create New Key
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">Production API Key</h4>
                                    <p class="text-sm text-gray-500">Created on Jan 15, 2024</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 mb-3">
                                <code
                                    class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm font-mono">rpc_live_sk_1234567890abcdef...</code>
                                <button
                                    class="px-3 py-2 text-sm text-brand-orange hover:text-brand-orange-dark border border-brand-orange rounded hover:bg-brand-orange hover:text-white transition-colors">
                                    Copy
                                </button>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Last used: 2 hours ago</span>
                                <div class="space-x-2">
                                    <button class="text-brand-orange hover:text-brand-orange-dark">Edit</button>
                                    <button class="text-red-600 hover:text-red-800">Revoke</button>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">Development API Key</h4>
                                    <p class="text-sm text-gray-500">Created on Dec 10, 2023</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Limited</span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 mb-3">
                                <code
                                    class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm font-mono">rpc_test_sk_abcdef1234567890...</code>
                                <button
                                    class="px-3 py-2 text-sm text-brand-orange hover:text-brand-orange-dark border border-brand-orange rounded hover:bg-brand-orange hover:text-white transition-colors">
                                    Copy
                                </button>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Last used: 1 week ago</span>
                                <div class="space-x-2">
                                    <button class="text-brand-orange hover:text-brand-orange-dark">Edit</button>
                                    <button class="text-red-600 hover:text-red-800">Revoke</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Documentation -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">API Documentation</h2>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Getting Started Guide</h4>
                                <p class="text-sm text-gray-500">Learn how to integrate with RepostChain API</p>
                            </div>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </div>

                        <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-code text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">API Reference</h4>
                                <p class="text-sm text-gray-500">Complete API endpoints and parameters</p>
                            </div>
                            <button class="text-green-600 hover:text-green-800">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </div>

                        <div class="flex items-center space-x-4 p-4 bg-purple-50 rounded-lg">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-flask text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">API Testing</h4>
                                <p class="text-sm text-gray-500">Test your API calls in our interactive playground</p>
                            </div>
                            <button class="text-purple-600 hover:text-purple-800">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rate Limits -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Rate Limits</h2>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Current Usage</h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Requests per minute</span>
                                        <span class="text-gray-900">45/100</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-brand-orange h-2 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Daily requests</span>
                                        <span class="text-gray-900">1,250/5,000</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-brand-orange h-2 rounded-full" style="width: 25%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Plan Limits</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Requests per minute:</span>
                                    <span class="text-gray-900">100</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Daily requests:</span>
                                    <span class="text-gray-900">5,000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly requests:</span>
                                    <span class="text-gray-900">100,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            // Remove active style from all buttons
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('border-brand-orange', 'text-brand-orange', 'font-medium');
                el.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabId).classList.remove('hidden');

            // Highlight active button
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.add('border-brand-orange', 'text-brand-orange', 'font-medium');
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
        }
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
