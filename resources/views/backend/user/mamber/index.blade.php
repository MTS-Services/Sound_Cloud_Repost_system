<x-user::layout>

    <x-slot name="page_slug">mamber</x-slot>

    <div class="container mx-auto px-4 py-8 max-w-8xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-2 dark:text-white">Browse Members</h1>
            <p class="text-text-gray text-sm md:text-base dark:text-white">Search, filter or browse the list of
                recommended members that can repost your music.</p>
        </div>

        <!-- Search and Filters -->
        <div class="mb-8 flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-text-gray dark:text-gray-400 " fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Name or sub-genre"
                    class="w-full bg-card-blue border border-gray-600 rounded-lg pl-10 pr-4 py-3 text-white dark:text-white dark:bg-gray-900 placeholder-text-gray focus:outline-none focus:border-orange-500">
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button
                    class="bg-card-blue border border-gray-600 rounded-lg px-4 py-3 text-text-gray  dark:text-white  hover:border-orange-500 transition-colors flex items-center gap-2 min-w-[160px] justify-between">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Filter by track
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <select
                    class="bg-card-blue border border-gray-600 dark:bg-gray-900  dark:text-white rounded-lg px-4 py-3 text-text-gray hover:border-orange-500 transition-colors min-w-[160px] focus:outline-none focus:border-orange-500">
                    <option class="dark:text-white">Filter by genre</option>
                    <option class="dark:text-white">Electronic</option>
                    <option class="dark:text-white">Hip-Hop</option>
                    <option class="dark:text-white">Pop</option>
                    <option class="dark:text-white">Rock</option>
                </select>

                <select
                    class="bg-card-blue border border-gray-600 dark:text-white dark:bg-gray-900 rounded-lg px-4 py-3 text-text-gray hover:border-orange-500 transition-colors min-w-[160px] focus:outline-none focus:border-orange-500">
                    <option class="dark:text-white">Filter by cost</option>
                    <option class="dark:text-white">Low to High</option>
                    <option class="dark:text-white">High to Low</option>
                </select>
            </div>
        </div>

        <!-- Member Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-6">
            <!-- Card 1 -->
            @foreach ($users as $user)
            <div class="bg-card-blue rounded-lg p-6 border border-gray-600">
                <!-- Profile Header -->

                
                    
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative">
                        <img src="{{asset($user->avatar)}}" alt="{{$user->name}}" class="w-12 h-12 rounded-full">
                        <div
                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-card-blue">
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">{{ $user->name }}</h3>
                        <p class="text-text-gray text-sm dark:text-white">{{$user->created_at}}</p>
                    </div>
                    {{-- <div class="ml-auto flex items-center gap-1 text-orange-500">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 01.82-.38z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-medium dark:text-white">1,112</span>
                    </div> --}}
                </div>

                <!-- Genre Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-gray-600 text-white  text-xs px-2 py-1 rounded">Ambient</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Dubstep</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Electronic</span>
                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded">Techno</span>
                </div>

                <!-- Repost Price -->
                     @php
                    $followerCount = $userinfo->count();
                    $credit = max(1, floor($followerCount / 100));
                @endphp

                <div class="flex justify-between items-center w-full mb-4">
                    <p class="text-text-gray text-sm dark:text-white">Repost price:</p>
                    <p class="text-sm font-medium dark:text-white">
                        {{ $credit }} Credit{{ $credit > 1 ? 's' : '' }}
                    </p>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Credibility</p>
                        <p class="text-green-400 font-bold ">83%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Efficiency</p>
                        <p class="text-orange-500 font-bold ">92%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-text-gray text-xs mb-1 dark:text-white">Total Reposts</p>
                        <p class="text-black font-bold dark:text-white">156</p>
                    </div>
                </div>

                <!-- Request Button -->
                 <button
        class="w-full max-w-xs bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition-colors dark:text-white"
        onclick="document.getElementById('trackSelectionModal').classList.remove('hidden')">
        Request
    </button>

    <!-- Modal -->
   <div id="trackSelectionModal"
    class="fixed inset-0 bg-opacity-10 flex justify-center items-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto mx-auto relative overflow-hidden">
        
        <!-- Close Button -->
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none"
            onclick="document.getElementById('trackSelectionModal').classList.add('hidden')">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 text-center">Choose a track or playlist</h2>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200">
            <button id="tracksTab"
                class="flex-1 py-3 text-center text-red-500 font-semibold border-b-2 border-red-500 focus:outline-none">
                Tracks
            </button>
            <button id="playlistsTab"
                class="flex-1 py-3 text-center text-gray-600 hover:text-gray-800 focus:outline-none">
                Playlists
            </button>
        </div>

        <!-- Tracks Content -->
        <div id="tracksContent" class="p-6 space-y-4">
            <a href="#">
                <div class="flex items-center bg-gray-50 rounded-lg p-3 shadow-sm">
                    <img src="{{ asset('frontend/user/image/pexels-photo-1040881.jpeg') }}" alt="Artist"
                        class="w-20 h-20 rounded-md object-cover mr-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Sample Track 7</h3>
                        <p class="text-sm text-gray-600">by author_5 <span
                                class="text-xs text-gray-500 ml-1">Electronic</span></p>
                        <span
                            class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded mt-1">US-RIL-2539579</span>
                    </div>
                </div>
            </a>
            <div class="text-center mt-6">
                <button class="text-red-500 font-semibold hover:text-red-600 focus:outline-none">
                    Load more Tracks
                </button>
            </div>
        </div>

        <!-- Playlists Content -->
       <div x-data="{ showModal: false, selectedPlaylist: null }">
    @foreach ($playlists as $playlist)
        <div id="playlist-{{ $playlist->id }}"
            class="p-6 space-y-4 {{ $loop->last ? '' : 'hidden' }} relative cursor-pointer"
            @click="selectedPlaylist = {
                title: '{{ $playlist->title }}',
                urn: '{{ $playlist->user_urn }}',
                tags: '{{ $playlist->tag_list }}',
                id: '{{ $playlist->id }}',
                track_count: '{{ $playlist->track_count }}'
            }; showModal = true"
        >
            <div class="flex items-center bg-gray-50 rounded-lg p-3 shadow-sm">
                <img src="{{ asset('frontend/user/image/pexels-photo-1040881.jpeg') }}" alt="Playlist Cover"
                    class="w-20 h-20 rounded-md object-cover mr-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $playlist->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $playlist->user_urn }}
                        <span class="text-xs text-gray-500 ml-1">{{ $playlist->tag_list }}</span>
                    </p>
                    <span class="inline-block bg-blue-200 text-blue-700 text-xs px-2 py-1 rounded mt-1">
                        {{ $playlist->track_count }} 
                    </span>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal -->
 <div x-data="{
        showModal: true,
        selectedTrackId: '',
        tracks: @json($tracks),
        selectedTrack: {},
    }">

    <div x-show="showModal"
         x-transition
         class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50 p-4 sm:p-6">

        <div class="bg-white dark:bg-gray-800 w-full max-w-sm sm:max-w-md p-4 sm:p-6 rounded-lg shadow-xl relative"
             @click.away="showModal = false">

            <!-- Playlist Selector -->
            <div class="mb-4 flex justify-center">
                <select id="playlistId" name="playlist_id"
                        x-model="selectedTrackId"
                        @change="selectedTrack = tracks.find(t => t.id == parseInt(selectedTrackId))"
                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white max-w-xs">
                    <option value="">Select a track</option>
                    @foreach ($tracks as $track)
                        <option value="{{ $track->id }}">{{ $track->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Playlist Image -->
            <div class="mb-4 flex justify-center">
                <img src="{{ asset('frontend/user/image/pexels-photo-1040881.jpeg') }}" 
                     alt="Playlist Image" 
                     class="w-24 h-28 sm:w-32 sm:h-36 object-cover rounded-lg shadow">
            </div>

            <!-- Playlist Details -->
            <h2 class="text-base sm:text-xl font-bold mb-2 text-center dark:text-white" x-text="selectedPlaylist.title"></h2>
            <p class="text-gray-600 dark:text-white mb-1 text-sm sm:text-base text-center">
                User URN: <span class="font-medium" x-text="selectedPlaylist.urn"></span>
            </p>
            <p class="text-gray-600 dark:text-white mb-1 text-sm sm:text-base text-center">
                Tags: <span class="font-medium" x-text="selectedPlaylist.tags"></span>
            </p>
            <p class="text-gray-600 dark:text-white mb-4 text-sm sm:text-base text-center">
                User ID: <span class="font-medium" x-text="selectedPlaylist.user_id"></span>
            </p>

            @php
                $followerCount = $userinfo->count();
                $credit = max(1, floor($followerCount / 100));
            @endphp

            <div class="flex gap-6 text-center justify-center">
                <p class="text-text-gray text-sm dark:text-white">Repost price:</p>
                <p class="text-sm font-medium dark:text-white">
                    {{ $credit }} Credit{{ $credit > 1 ? 's' : '' }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-3 mt-6">
                <button @click="showModal = false"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                    Cancel
                </button>
                <form :action="'{{ url('confirm/repost') }}/' + selectedPlaylist.id"
                      method="POST" class="w-full sm:w-auto text-center">
                    @csrf
                    <button type="submit"
                            class="w-full sm:w-auto px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Confirm
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>



</div>

            </div>
            </div>
            
            </div>
            @endforeach
        </div>
    </div>


    @push('js')
     <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tracksTab = document.getElementById("tracksTab");
            const playlistsTab = document.getElementById("playlistsTab");
            const tracksContent = document.getElementById("tracksContent");
            const playlistsContent = document.getElementById("playlistsContent");

            tracksTab.addEventListener("click", () => {
                // Tab style updates
                tracksTab.classList.add("text-red-500", "border-b-2", "border-red-500", "font-semibold");
                tracksTab.classList.remove("text-gray-600");
                playlistsTab.classList.remove("text-red-500", "border-b-2", "border-red-500", "font-semibold");
                playlistsTab.classList.add("text-gray-600");

                // Content visibility
                tracksContent.classList.remove("hidden");
                playlistsContent.classList.add("hidden");
            });

            playlistsTab.addEventListener("click", () => {
                playlistsTab.classList.add("text-red-500", "border-b-2", "border-red-500", "font-semibold");
                playlistsTab.classList.remove("text-gray-600");
                tracksTab.classList.remove("text-red-500", "border-b-2", "border-red-500", "font-semibold");
                tracksTab.classList.add("text-gray-600");

                tracksContent.classList.add("hidden");
                playlistsContent.classList.remove("hidden");
            });
        });
    </script>
        
    @endpush
</x-user::layout>
