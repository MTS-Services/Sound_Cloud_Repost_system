<x-user::layout>
    <x-slot name="page_slug">analytics</x-slot>

    <section class="min-h-screen bg-white p-8" x-data="{ activeTab: 'tracks' }">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Promote Dashboard</h1>

            <!-- Tabs -->
            <div class="flex bg-gray-100 rounded-md overflow-hidden mb-6">
                <button @click="activeTab = 'tracks'"
                    :class="activeTab === 'tracks' ? 'bg-white text-gray-800' : 'text-gray-500 hover:bg-gray-50'"
                    class="flex-1 py-3 px-4 text-sm font-medium border-r border-gray-200 focus:outline-none">
                    Tracks
                </button>
                <button @click="activeTab = 'playlists'"
                    :class="activeTab === 'playlists' ? 'bg-white text-gray-800' : 'text-gray-500 hover:bg-gray-50'"
                    class="flex-1 py-3 px-4 text-sm font-medium focus:outline-none">
                    Playlists
                </button>
            </div>


            <!-- Track List -->
            <div class="space-y-4" x-show="activeTab === 'tracks'">
                <!-- Track Item 1 -->
                <div class="flex items-center bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <img src="https://via.placeholder.com/64" alt="Album Cover" class="rounded-md mr-4" />
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">Ab To Forever</h2>
                        <p class="text-sm text-gray-600 mb-1">herman hedrick <span class="ml-1 text-xs">&#x24CB;</span>
                        </p>
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
                            Ambient
                        </span>
                    </div>
                </div>

                <!-- Track Item 2 -->
                <div class="flex items-center bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <img src="https://via.placeholder.com/64" alt="Album Cover" class="rounded-md mr-4" />
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">Dil Dooba</h2>
                        <p class="text-sm text-gray-600 mb-1">herman hedrick <span class="ml-1 text-xs">&#x24CB;</span>
                        </p>
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
                            Indie
                        </span>
                    </div>
                </div>
            </div>

            <!-- Playlist List -->
            <div class="space-y-4" x-show="activeTab === 'playlists'">
                <!-- Example Playlist Item -->
                <div class="flex items-center bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <img src="https://via.placeholder.com/64" alt="Playlist Cover" class="rounded-md mr-4" />
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">Chill Vibes</h2>
                        <p class="text-sm text-gray-600 mb-1">curated by <strong>herman hedrick</strong></p>
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
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
    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @endpush
</x-user::layout>
