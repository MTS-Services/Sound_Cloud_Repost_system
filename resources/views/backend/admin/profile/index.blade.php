<x-admin::layout>
    <x-slot name="title">{{ __('Admin Profile') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin Profile') }}</x-slot>
    <x-slot name="page_slug">admin-profile</x-slot>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
            }
            to {
                transform: scale(1);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .scale-in {
            animation: scaleIn 0.3s ease-out forwards;
        }
    </style>

    <section class="space-y-8 fade-in">
        <!-- Header Section -->
        <div class="glass-card rounded-2xl p-6  ">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold">{{ __('Admin Profile') }}</h1>
                    <p class="text-sm mt-1 opacity-80">
                        {{ __('Your personal and account details.') }}
                    </p>
                </div>
                <x-button href="{{ route('admin.profile.edit') }}" icon="edit" type="primary" class="w-full sm:w-auto transition transform hover:scale-105">
                    {{ __('Edit Profile') }}
                </x-button>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Image Card -->
            <div class="glass-card rounded-2xl p-6 shadow-md lg:col-span-1 bg-white dark:bg-gray-800 scale-in flex flex-col items-center text-center">
                <div class="relative w-48 h-48 rounded-2xl overflow-hidden border-8 border-gray-200 dark:border-gray-700 shadow-xl mb-6 transition transform hover:scale-105">
                    <img src="{{ $admin->modified_image }}" alt="{{ $admin->name }}"
                        class="w-full h-full object-cover">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $admin->name }}</h2>
                <p class="text-md text-purple-600 dark:text-purple-400 font-semibold">{{ __('Administrator') }}</p>
            </div>

            <!-- Profile Details Card -->
            <div class="glass-card rounded-2xl p-8 shadow-md lg:col-span-2 bg-white dark:bg-gray-800 scale-in">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 border-b pb-4 border-gray-200 dark:border-gray-700">
                    {{ __('Profile Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Info -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-500 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Full Name') }}</p>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $admin->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-500 dark:text-green-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Email Address') }}</p>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $admin->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Account Status') }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                    {{ __('Active') }}
                                </span>
                            </div>
                        </div>
                         <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-500 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Verified Status') }}</p>
                                @if ($admin->email_verified_at)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-200">
                                        {{ __('Verified') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-200 text-red-800 dark:bg-red-800 dark:text-red-200">
                                        {{ __('Not Verified') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l9-5-9-5-9 5 9 5zM12 14v7l9-5" />
                                    <path d="M12 21l-9-5" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Assigned Role') }}</p>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $admin->getRoleNames()->first() }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-purple-500 dark:text-purple-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Member Since') }}</p>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $admin->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 transition transform hover:translate-x-2">
                            <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-red-500 dark:text-red-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M4 4v16h16V4H4zm4 8h8m-8-4h8m-8 8h8" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</p>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $admin->updated_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-admin::layout>