<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    <section>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-black dark:text-text-white">{{ __('User Management') }}</h2>
                <button class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 stroke-text-white"></i>
                    {{ __('Add Admin') }}
                </button>
            </div>

            <!-- Admin Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b border-border-black/5 hover:bg-border-black/5 dark:border-border-white/5 dark:hover:bg-border-white/5">
                            <th
                                class="text-left text-text-light-secondary dark:text-text-dark-primary font-medium py-3 px-4">
                                {{ __('Admin') }}</th>
                            <th
                                class="text-left text-text-light-secondary dark:text-text-dark-primary font-medium py-3 px-4">
                                {{ __('Email') }}</th>
                            <th
                                class="text-left text-text-light-secondary dark:text-text-dark-primary font-medium py-3 px-4">
                                {{ __('Role') }}</th>
                            <th
                                class="text-left text-text-light-secondary dark:text-text-dark-primary font-medium py-3 px-4">
                                {{ __('Status') }}</th>
                            <th
                                class="text-left text-text-light-secondary dark:text-text-dark-primary font-medium py-3 px-4">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr
                                class="border-b border-border-black/5 hover:bg-border-black/5 dark:border-border-white/5 dark:hover:bg-border-white/5 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="" alt="" class="w-10 h-10 rounded-xl object-cover">
                                        <div>
                                            <div class="text-text-black dark:text-text-white font-medium">
                                                {{ $admin->name }}
                                            </div>
                                            <div class="text-text-light-secondary dark:text-text-dark-primary text-sm">
                                                UserName
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-text-black/80 dark:text-text-white/80">
                                    {{ $admin->email }}
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400">Role</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                        :class="'active'
                                        === 'active' ? 'bg-green-500/20 text-green-400' :
                                            'bg-red-500/20 text-red-400'">Active</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <button class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4 text-text-dark-primary"></i>
                                        </button>
                                        <button class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-red-400"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin::layout>
