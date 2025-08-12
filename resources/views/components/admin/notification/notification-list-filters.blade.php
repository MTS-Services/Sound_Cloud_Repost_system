<div class="flex flex-col sm:flex-row gap-4">
    <div class="flex-1">
        <div class="relative">
            <input type="text" 
                   x-model="searchTerm"
                   @input.debounce.500ms="refreshNotifications()"
                   placeholder="Search notifications..."
                   class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>
    
    <div class="flex space-x-2">
        <button @click="setFilter('all')" 
                :class="filter === 'all' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-slate-600 dark:text-gray-300'"
                class="px-4 py-2 text-sm rounded-md transition-colors">
            All
        </button>
        <button @click="setFilter('unread')" 
                :class="filter === 'unread' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-slate-600 dark:text-gray-300'"
                class="px-4 py-2 text-sm rounded-md transition-colors">
            Unread
        </button>
        <button @click="setFilter('read')" 
                :class="filter === 'read' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-slate-600 dark:text-gray-300'"
                class="px-4 py-2 text-sm rounded-md transition-colors">
            Read
        </button>
    </div>
</div>
