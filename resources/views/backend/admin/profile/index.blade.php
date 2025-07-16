<x-admin::layout>
    <x-slot name="title">{{ __('Admin Profile') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin Profile') }}</x-slot>
    <x-slot name="page_slug">admin-profile</x-slot>

    <section class="space-y-6">
        <!-- Header Section -->
        <div class="glass-card rounded-2xl p-6 shadow-md bg-gradient-to-br from-white/70 to-white/90 dark:from-gray-800/70 dark:to-gray-900/80 backdrop-blur">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-text-black dark:text-white">{{ __('Admin Profile') }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ __('View and manage your profile information') }}
                    </p>
                </div>
                <x-button href="{{ route('admin.profile.edit') }}" icon="edit" type="primary" soft="true"  class="w-full sm:w-auto">
                    {{ __('Edit Profile') }}
                </x-button>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mx-auto w-full max-w-6xl">
            <!-- Profile Image Card -->
            <div class="glass-card rounded-2xl p-6 shadow-md lg:col-span-1 bg-white dark:bg-gray-900">
                <div class="flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-text-black dark:text-white mb-4 self-start">
                        {{ __('Profile Image') }}
                    </h3>

                    <div class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-primary-500/30 shadow-lg bg-gray-100 dark:bg-gray-700 hover:shadow-xl transition duration-300">
                        <img src="{{ $admin->modified_image }}" alt="{{ $admin->name }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 ease-in-out">
                    </div>

                    <div class="mt-4 text-center">
                        <h2 class="text-2xl font-bold text-text-black dark:text-white">{{ $admin->name }}</h2>
                        <p class="text-sm text-primary-600 dark:text-primary-400">{{ __('Administrator') }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Details Card -->
            <div class="glass-card rounded-2xl p-6 shadow-md lg:col-span-2 bg-white dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-text-black dark:text-white mb-6">
                    {{ __('Profile Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="space-y-10">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 21v-2a4 4 0 0 0-3-3.87M4 21v-2a4 4 0 0 1 3-3.87M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                                </svg>
                                {{ __('Full Name') }}
                            </p>
                            <p class="mt-1 text-lg font-medium text-text-black dark:text-white">{{ $admin->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 4h16v16H4z" stroke="none"/>
                                    <path d="M22 4H2v16h20V4zm-10 9l7-5H5l7 5z"/>
                                </svg>
                                {{ __('Email Address') }}
                            </p>
                            <p class="mt-1 text-lg font-medium text-text-black dark:text-white">{{ $admin->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12l2 2l4 -4"></path>
                                    <path d="M20 12a8 8 0 1 1 -16 0a8 8 0 0 1 16 0z"></path>
                                </svg>
                                {{ __('Account Status') }}
                            </p>
                            <span
                                class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200">
                                {{ __('Active') }}
                            </span>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 0 0 2-2v-5H3v5a2 2 0 0 0 2 2z"/>
                                </svg>
                                {{ __('Member Since') }}
                            </p>
                            <p class="mt-1 text-lg font-medium text-text-black dark:text-white">
                                {{ $admin->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 4v16h16V4H4zm4 8h8M8 8h8M8 16h8"/>
                                </svg>
                                {{ __('Last Updated') }}
                            </p>
                            <p class="mt-1 text-lg font-medium text-text-black dark:text-white">
                                {{ $admin->updated_at->format('M d, Y \a\t h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-admin::layout>
