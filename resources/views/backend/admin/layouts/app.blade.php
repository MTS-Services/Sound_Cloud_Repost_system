<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    {{-- Theme selector && Theme store --}}
    <script>
        // On page load, immediately apply theme from localStorage to prevent flash
        (function() {
            let theme = localStorage.getItem('theme') || 'system';

            // Apply theme immediately
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', systemPrefersDark);
                document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>

    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

</head>

<body x-data="dashboardData()" class="animated-bg min-h-screen">

    <!-- Mobile/Tablet Overlay -->
    <div x-show="mobile_menu_open && !desktop" x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-all duration-300 ease-in" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="closeMobileMenu()" class="fixed inset-0 z-40 glass-card lg:hidden">
    </div>

    <div class="flex h-screen">
        <!-- Sidebar -->

        <x-admin::side-bar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->

            <x-admin::header />

            <!-- Main Content Area -->
            <main class="flex-1 p-4 lg:p-6 custom-scrollbar overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Notification Panel -->
    <x-admin::notification />

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>

    <script>
        function dashboardData() {
            return {
                // Responsive state
                desktop: window.innerWidth >= 1024,
                sidebar_expanded: window.innerWidth >= 1024,
                mobile_menu_open: false,

                // App state
                activeTab: 'dashboard',
                searchQuery: '',
                darkMode: true,
                showNotifications: false,

                // Data
                stats: {
                    users: 12384,
                    revenue: 48392,
                    orders: 2847,
                    activeUsers: 847
                },

                recentActivity: [{
                        id: 1,
                        title: 'New user registered',
                        time: '2 minutes ago',
                        icon: 'user-plus',
                        iconBg: 'bg-green-500/20',
                        iconColor: 'text-green-400'
                    },
                    {
                        id: 2,
                        title: 'Payment received',
                        time: '5 minutes ago',
                        icon: 'credit-card',
                        iconBg: 'bg-blue-500/20',
                        iconColor: 'text-blue-400'
                    },
                    {
                        id: 3,
                        title: 'Order completed',
                        time: '10 minutes ago',
                        icon: 'check-circle',
                        iconBg: 'bg-purple-500/20',
                        iconColor: 'text-purple-400'
                    },
                    {
                        id: 4,
                        title: 'New message received',
                        time: '15 minutes ago',
                        icon: 'mail',
                        iconBg: 'bg-yellow-500/20',
                        iconColor: 'text-yellow-400'
                    }
                ],

                users: [{
                        id: 1,
                        name: 'John Doe',
                        username: '@johndoe',
                        email: 'john@example.com',
                        role: 'admin',
                        status: 'active',
                        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 2,
                        name: 'Jane Smith',
                        username: '@janesmith',
                        email: 'jane@example.com',
                        role: 'manager',
                        status: 'active',
                        avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 3,
                        name: 'Mike Johnson',
                        username: '@mikej',
                        email: 'mike@example.com',
                        role: 'user',
                        status: 'inactive',
                        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 4,
                        name: 'Sarah Wilson',
                        username: '@sarahw',
                        email: 'sarah@example.com',
                        role: 'manager',
                        status: 'active',
                        avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 5,
                        name: 'David Brown',
                        username: '@davidb',
                        email: 'david@example.com',
                        role: 'user',
                        status: 'active',
                        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=40&h=40&fit=crop&crop=face&auto=format'
                    }
                ],

                projects: [{
                        id: 1,
                        name: 'Website Redesign',
                        description: 'Complete overhaul of company website with modern design',
                        progress: 75,
                        status: 'active',
                        deadline: 'Dec 31, 2024',
                        team: '5',
                        icon: 'globe',
                        iconBg: 'bg-blue-500/20',
                        iconColor: 'text-blue-400'
                    },
                    {
                        id: 2,
                        name: 'Mobile App',
                        description: 'Native iOS and Android application development',
                        progress: 45,
                        status: 'active',
                        deadline: 'Feb 15, 2025',
                        team: '8',
                        icon: 'smartphone',
                        iconBg: 'bg-green-500/20',
                        iconColor: 'text-green-400'
                    },
                    {
                        id: 3,
                        name: 'API Integration',
                        description: 'Third-party service integration and optimization',
                        progress: 90,
                        status: 'pending',
                        deadline: 'Nov 30, 2024',
                        team: '3',
                        icon: 'zap',
                        iconBg: 'bg-purple-500/20',
                        iconColor: 'text-purple-400'
                    },
                    {
                        id: 4,
                        name: 'Database Migration',
                        description: 'Migrate legacy database to new infrastructure',
                        progress: 20,
                        status: 'active',
                        deadline: 'Jan 20, 2025',
                        team: '4',
                        icon: 'database',
                        iconBg: 'bg-yellow-500/20',
                        iconColor: 'text-yellow-400'
                    },
                    {
                        id: 5,
                        name: 'Security Audit',
                        description: 'Comprehensive security review and improvements',
                        progress: 60,
                        status: 'active',
                        deadline: 'Dec 15, 2024',
                        team: '2',
                        icon: 'shield',
                        iconBg: 'bg-red-500/20',
                        iconColor: 'text-red-400'
                    },
                    {
                        id: 6,
                        name: 'Performance Optimization',
                        description: 'Optimize application performance and loading times',
                        progress: 35,
                        status: 'pending',
                        deadline: 'Mar 10, 2025',
                        team: '6',
                        icon: 'activity',
                        iconBg: 'bg-indigo-500/20',
                        iconColor: 'text-indigo-400'
                    }
                ],

                messages: [{
                        id: 1,
                        sender: 'Alice Johnson',
                        subject: 'Project Update Required',
                        preview: 'Hi there! Can you please provide an update on the current project status...',
                        time: '2 hours ago',
                        read: false,
                        avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 2,
                        sender: 'Mark Thompson',
                        subject: 'Budget Approval Needed',
                        preview: 'The Q4 budget proposal is ready for your review and approval...',
                        time: '4 hours ago',
                        read: false,
                        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 3,
                        sender: 'Lisa Chen',
                        subject: 'Team Meeting Scheduled',
                        preview: 'Our weekly team meeting has been scheduled for tomorrow at 10 AM...',
                        time: '6 hours ago',
                        read: true,
                        avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 4,
                        sender: 'Robert Davis',
                        subject: 'New Feature Request',
                        preview: 'We have received a new feature request from our premium customers...',
                        time: '1 day ago',
                        read: true,
                        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=40&h=40&fit=crop&crop=face&auto=format'
                    },
                    {
                        id: 5,
                        sender: 'Emma Wilson',
                        subject: 'Performance Report',
                        preview: 'The monthly performance report is now available for review...',
                        time: '2 days ago',
                        read: true,
                        avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=40&h=40&fit=crop&crop=face&auto=format'
                    }
                ],

                notifications: [{
                        id: 1,
                        title: 'System Update',
                        message: 'System maintenance scheduled for tonight',
                        time: '5 minutes ago',
                        icon: 'settings',
                        iconBg: 'bg-blue-500/20',
                        iconColor: 'text-blue-400'
                    },
                    {
                        id: 2,
                        title: 'New Comment',
                        message: 'Someone commented on your post',
                        time: '10 minutes ago',
                        icon: 'message-circle',
                        iconBg: 'bg-green-500/20',
                        iconColor: 'text-green-400'
                    },
                    {
                        id: 3,
                        title: 'Security Alert',
                        message: 'New login from unknown device',
                        time: '1 hour ago',
                        icon: 'shield-alert',
                        iconBg: 'bg-red-500/20',
                        iconColor: 'text-red-400'
                    }
                ],

                // Methods
                init() {
                    this.handleResize();
                    this.initChart();
                    window.addEventListener('resize', () => this.handleResize());

                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                            e.preventDefault();
                            this.focusSearch();
                        }
                    });
                },

                handleResize() {
                    this.desktop = window.innerWidth >= 1024;
                    if (this.desktop) {
                        this.mobile_menu_open = false;
                        this.sidebar_expanded = true;
                    } else {
                        this.sidebar_expanded = false;
                    }
                },

                toggleSidebar() {
                    if (this.desktop) {
                        this.sidebar_expanded = !this.sidebar_expanded;
                    } else {
                        this.mobile_menu_open = !this.mobile_menu_open;
                    }
                },

                closeMobileMenu() {
                    if (!this.desktop) {
                        this.mobile_menu_open = false;
                    }
                },

                setActiveTab(tab) {
                    this.activeTab = tab;
                    this.closeMobileMenu();

                    // Reinitialize chart if switching to dashboard
                    if (tab === 'dashboard') {
                        this.$nextTick(() => {
                            this.initChart();
                        });
                    }
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    // Add theme switching logic here
                    console.log('Theme toggled:', this.darkMode ? 'dark' : 'light');
                },

                toggleNotifications() {
                    this.showNotifications = !this.showNotifications;
                },

                handleSearch() {
                    if (this.searchQuery.length > 0) {
                        console.log('Searching for:', this.searchQuery);
                        // Add search logic here
                    }
                },

                focusSearch() {
                    const searchInput = document.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                },

                showDetails(type) {
                    console.log('Showing details for:', type);
                    // Add modal or detailed view logic here
                },

                initChart() {
                    this.$nextTick(() => {
                        const canvas = document.getElementById('revenueChart');
                        if (!canvas) return;

                        const ctx = canvas.getContext('2d');

                        // Destroy existing chart if it exists
                        if (window.revenueChart instanceof Chart) {
                            window.revenueChart.destroy();
                        }

                        // Create gradient
                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
                        gradient.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

                        window.revenueChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                                    'Oct', 'Nov', 'Dec'
                                ],
                                datasets: [{
                                    label: 'Revenue',
                                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000,
                                        32000, 38000, 42000, 48000
                                    ],
                                    borderColor: '#667eea',
                                    backgroundColor: gradient,
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#667eea',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    pointRadius: 6,
                                    pointHoverRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: 'rgba(255, 255, 255, 0.6)'
                                        }
                                    },
                                    y: {
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.1)'
                                        },
                                        ticks: {
                                            color: 'rgba(255, 255, 255, 0.6)',
                                            callback: function(value) {
                                                return '$' + value.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                },
                                elements: {
                                    point: {
                                        hoverBackgroundColor: '#ffffff'
                                    }
                                }
                            }
                        });
                    });
                }
            }
        }

        // Initialize Lucide icons after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Smooth scrolling for anchor links
        document.addEventListener('click', function(e) {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });

        // Add loading states for interactive elements
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn-primary') || e.target.closest('.btn-primary')) {
                const btn = e.target.matches('.btn-primary') ? e.target : e.target.closest('.btn-primary');
                btn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    btn.style.transform = '';
                }, 150);
            }
        });

        // Add ripple effect to interactive cards
        document.addEventListener('click', function(e) {
            if (e.target.matches('.interactive-card') || e.target.closest('.interactive-card')) {
                const card = e.target.matches('.interactive-card') ? e.target : e.target.closest(
                    '.interactive-card');
                const rect = card.getBoundingClientRect();
                const ripple = document.createElement('span');
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
    position: absolute;
    width: ${size}px;
    height: ${size}px;
    left: ${x}px;
    top: ${y}px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: scale(0);
    animation: ripple 0.6s ease-out;
    pointer-events: none;
    `;

                card.style.position = 'relative';
                card.style.overflow = 'hidden';
                card.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes ripple {
    to {
    transform: scale(2);
    opacity: 0;
    }
    }
    `;
        document.head.appendChild(style);
    </script>

</body>

</html>
