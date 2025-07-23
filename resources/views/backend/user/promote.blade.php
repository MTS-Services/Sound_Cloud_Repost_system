<x-user::layout>
    <x-slot name="page_slug">promote</x-slot>

    <section class="min-h-screen bg-white dark:bg-gray-900 p-8" x-data="{ activeTab: 'tracks' }">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 dark:text-gray-100">Promote Dashboard</h1>

            <!-- Tabs -->
            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden mb-6">
                <button @click="activeTab = 'tracks'"
                    :class="activeTab === 'tracks' ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100' :
                        'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 px-4 text-sm font-medium border-r border-gray-200 dark:border-gray-600 focus:outline-none">
                    Tracks
                </button>
                <button @click="activeTab = 'playlists'"
                    :class="activeTab === 'playlists' ? 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100' :
                        'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 px-4 text-sm font-medium focus:outline-none">
                    Playlists
                </button>
            </div>

            <!-- Track List -->
            <div class="space-y-4" x-show="activeTab === 'tracks'">
                @foreach ($tracks as $track)
                    <!-- Track Item -->
                    <div
                        class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <img src="{{ $track->artwork_url ?? 'https://via.placeholder.com/64' }}"
                            alt="{{ $track->title }}" class="rounded-md" width="150" height="100" />
                        <div class="flex-1 p-4 ">
                            <h2 class="text-lg font-semibold dark:text-gray-100">{{ $track->title }}</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                by <strong>{{ $track->author_username }}</strong>
                                <span class="ml-1 text-xs">{{ $track->genre }}</span>
                            </p>
                            <span
                                class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                                {{ $track->isrc }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Playlist List -->
            <div class="space-y-4" x-show="activeTab === 'playlists'">
                <!-- Example Playlist Item -->
                <div
                    class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                    <img src="https://via.placeholder.com/64" alt="Playlist Cover" class="rounded-md mr-4" />
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold dark:text-gray-100">Chill Vibes</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">curated by <strong>herman
                                hedrick</strong></p>
                        <span
                            class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs px-2 py-1 rounded-full">
                            Chillout
                        </span>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-8">
                <button class="text-red-500 text-sm font-medium hover:underline focus:outline-none">
                    Load more
                </button>
            </div>
        </div>
    </section>

</x-user::layout>
