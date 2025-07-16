<x-admin::layout>
    <x-slot name="title">{{ __('Admin Profile') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin Profile') }}</x-slot>
    <x-slot name="page_slug">admin-profile</x-slot>

    <section class="space-y-6">
        <!-- Header Section -->
        <div class="glass-card rounded-xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-text-black dark:text-text-white">{{ __('Admin Profile') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('View and manage your profile information') }}
                    </p>
                </div>
                <x-button href="{{ route('admin.profile.edit') }}" icon="edit" type="primary" class="w-full sm:w-auto">
                    {{ __('Edit Profile') }}
                </x-button>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mx-auto w-[80%]">
            <!-- Profile Image Card -->
            <div class="glass-card rounded-xl p-6  shadow-sm lg:col-span-2">
                <div class="flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-text-black dark:text-text-white mb-4 self-start">
                        {{ __('Profile Image') }}
                    </h3>

                    <div
                        class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-primary-500/20 shadow-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <img src="{{ $admin->modified_image }}" alt="{{ $admin->name }}"
                            class="w-full h-full object-cover transition-transform hover:scale-105">
                    </div>

                    <div class="mt-4 text-center">
                        <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ $admin->name }}</h2>
                        <p class="text-sm text-primary-500 dark:text-primary-400">{{ __('Administrator') }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Details Card -->
            <div class="glass-card rounded-xl p-6 shadow-sm lg:col-span-2">
                <h3 class="text-lg font-semibold text-text-black dark:text-text-white mb-6">
                    {{ __('Profile Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Full Name') }}</p>
                            <p class="mt-1 text-text-black dark:text-text-white font-medium">{{ $admin->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email Address') }}
                            </p>
                            <p class="mt-1 text-text-black dark:text-text-white font-medium">{{ $admin->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Status') }}
                            </p>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ __('Active') }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Member Since') }}
                            </p>
                            <p class="mt-1 text-text-black dark:text-text-white font-medium">
                                {{ $admin->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}
                            </p>
                            <p class="mt-1 text-text-black dark:text-text-white font-medium">
                                {{ $admin->updated_at->format('M d, Y \a\t H:i A') }}
                            </p>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </section>
</x-admin::layout>
