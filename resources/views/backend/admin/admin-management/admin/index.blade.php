<x-admin::layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="breadcrumb">Dashboard</x-slot>
    <x-slot name="page_slug">admin-dashboard</x-slot>

    <div class="mx-auto space-y-6">
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-white">User Management</h2>
                <button class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add User
                </button>
            </div>

            <!-- User Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left text-text-dark-primary font-medium py-3 px-4">User</th>
                            <th class="text-left text-text-dark-primary font-medium py-3 px-4">Email</th>
                            <th class="text-left text-text-dark-primary font-medium py-3 px-4">Role</th>
                            <th class="text-left text-text-dark-primary font-medium py-3 px-4">Status</th>
                            <th class="text-left text-text-dark-primary font-medium py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="user in users" :key="user.id">
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <img :src="user.avatar" :alt="user.name"
                                            class="w-10 h-10 rounded-xl object-cover">
                                        <div>
                                            <div class="text-text-white font-medium" x-text="user.name">
                                            </div>
                                            <div class="text-text-dark-primary text-sm" x-text="user.username">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-text-white/80" x-text="user.email"></td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                        :class="user.role === 'admin' ? 'bg-red-500/20 text-red-400' : user
                                            .role === 'manager' ? 'bg-blue-500/20 text-blue-400' :
                                            'bg-gray-500/20 text-gray-400'"
                                        x-text="user.role"></span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                        :class="user.status === 'active' ? 'bg-green-500/20 text-green-400' :
                                            'bg-red-500/20 text-red-400'"
                                        x-text="user.status"></span>
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
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin::layout>
