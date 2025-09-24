<x-admin::layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="breadcrumb">Dashboard</x-slot>
    <x-slot name="page_slug">admin-dashboard</x-slot>

    <section>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6"
            x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Total Admin --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;"
                @click="showDetails('admins')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-200 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $admin }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Admins</p>
            </div>

            {{-- Active Admin --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;"
                @click="showDetails('active_admin')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-cog" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $active_admin }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Active Admins</p>
            </div>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;"
                @click="showDetails('users')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $user }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Users</p>
            </div>

            {{-- Active Users --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;"
                @click="showDetails('active_users')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-check" class="w-6 h-6 text-green-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $active_user }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Active Users</p>
            </div>

            {{-- Total Users Plan --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;"
                @click="showDetails('user_plan')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="layers" class="w-6 h-6 text-indigo-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $user_plan }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total User Plans</p>
            </div>

            {{-- Total Tracks --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;"
                @click="showDetails('tracks')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="music" class="w-6 h-6 text-pink-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $track }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Tracks</p>
            </div>

            {{-- Total Orders --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;"
                @click="showDetails('orders')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-purple-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $order }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Orders</p>
            </div>
            {{-- completed Orders --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card cursor-pointer transition-transform hover:scale-105"
                style="animation-delay: 0.4s;" @click="showDetails('completed_order')">

                <div class="flex items-center justify-between mb-4">
                    <!-- Icon Container -->
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center shadow-md">
                        <i data-lucide="check-circle" class="w-6 h-6 text-purple-500"></i>
                    </div>

                    <!-- Completed Orders Count -->
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                        {{ $completed_order }}
                    </h3>
                </div>

                <!-- Description -->
                <p class="text-gray-800/60 dark:text-gray-300 text-sm">
                    Completed Orders
                </p>
            </div>

            {{-- Active Campaigns --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('active_campaigns')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="megaphone" class="w-6 h-6 text-yellow-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $active_campaign }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Active Campaigns</p>
            </div>
            {{-- Completed Campaigns --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card cursor-pointer transition-transform hover:scale-105"
                style="animation-delay: 0.6s;" @click="showDetails('completed_campaign')">

                <div class="flex items-center justify-between mb-4">
                    <!-- Icon Container -->
                    <div class="w-12 h-12 bg-yellow-700/20 rounded-xl flex items-center justify-center shadow-md">
                        <i data-lucide="book-check" class="w-6 h-6 text-yellow-900"></i>
                    </div>

                    <!-- Campaign Count -->
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                        {{ $completed_campaign }}
                    </h3>
                </div>

                <!-- Description -->
                <p class="text-gray-800/60 dark:text-gray-300 text-sm">
                    Completed Campaigns
                </p>
            </div>

            {{-- Plans --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('plans')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="calendar-range" class="w-6 h-6 text-cyan-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $plan }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Active Plans</p>
            </div>

            {{-- Credits --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('credits')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-teal-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="wallet" class="w-6 h-6 text-teal-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $credit }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Active Credits</p>
            </div>

            {{-- Reposts --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('reposts')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="file-text" class="w-6 h-6 text-red-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $report }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Reposts</p>
            </div>
            {{-- Reposts Requests --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('repost_request')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-rose-200 rounded-xl flex items-center justify-center">
                        <i data-lucide="file-clock" class="w-6 h-6 text-rose-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $repost_request }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Repost Requests</p>
            </div>
            {{-- Payment --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('payments')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-rose-200 rounded-xl flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-6 h-6 text-rose-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $payments }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Payments </p>
            </div>

            {{-- Total Amount --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;"
                @click="showDetails('amount')">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-200 rounded-xl flex items-center justify-center">
                        <i data-lucide="banknote" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">{{ $amount }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">Total Amount Purchased</p>
            </div>
            {{-- Credit Transactions --}}
            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card cursor-pointer transition-transform hover:scale-105"
                style="animation-delay: 0.6s;" @click="showDetails('transactions_credit')">

                <div class="flex items-center justify-between mb-4">
                    <!-- Icon Container -->
                    <div
                        class="w-12 h-12 bg-yellow-100/80 dark:bg-yellow-200/20 rounded-xl flex items-center justify-center shadow-md">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-yellow-600"></i>
                    </div>

                    <!-- Transaction Count -->
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                        {{ $transactions_credit }}
                    </h3>
                </div>

                <!-- Description -->
                <p class="text-gray-700/70 dark:text-gray-300 text-sm">Total Credit Transactions </p>
            </div>




        </div>

        <!-- Charts Section -->
        {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-transition:enter="transition-all duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">

            <!-- Main Chart -->
            <div class="lg:col-span-2 glass-card rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-text-white mb-1">Revenue Analytics</h3>
                        <p class="text-text-dark-primary text-sm">Monthly revenue breakdown</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select
                            class="bg-white/10 text-text-white text-sm px-3 py-2 rounded-lg border border-white/20 outline-none">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="daily">Daily</option>
                        </select>
                        <button
                            class="btn-primary text-text-white text-sm px-4 py-2 rounded-xl flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export
                        </button>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="revenueChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Recent Activity -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-text-white">Recent Activity</h3>
                        <button class="text-text-dark-primary hover:text-text-white transition-colors">
                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="activity in recentActivity" :key="activity.id">
                            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    :class="activity.iconBg">
                                    <i :data-lucide="activity.icon" class="w-4 h-4" :class="activity.iconColor"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-text-white text-sm font-medium" x-text="activity.title"></p>
                                    <p class="text-text-dark-primary text-xs" x-text="activity.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-text-white mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            class="btn-primary p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 hover:scale-105 transition-transform">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add User
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            Send Mail
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Reports
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            Settings
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
</x-admin::layout>
