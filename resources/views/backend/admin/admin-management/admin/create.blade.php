<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-black dark:text-text-white">{{ __('User Management') }}</h2>
                <button class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add User
                </button>
            </div>

            <!-- User Table -->
            <div class="mt-6">
                <livewire:admin.admin-management.admin.create />
            </div>
        </div>
    </section>
</x-admin::layout>
