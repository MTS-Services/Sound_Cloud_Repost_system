<div>
    <x-slot name="page_slug">track-submit</x-slot>
    {{-- <!-- Main Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl card-shadow overflow-hidden">
        <!-- Form Header -->
        <div class="gradient-bg px-8 py-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-black dark:text-white">Track Information</h2>
                <x-button href="{{ route('user.dashboard') }}">Back</x-button>
            </div>
        </div>

        <form action="/submit-track" method="POST" class="p-8 pt-0 space-y-8">
            <!-- Basic Information Section -->
            <div class="space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Track Title<sup
                                class="text-sm text-orange-400">*</sup></label>
                        <input type="text" name="title" value="Some title" required
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>

                    <!-- Genre -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Genre</label>
                        <input type="text" name="genre" value="Rock"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>
                    <!-- Artwork URL -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Artwork URL<sup
                                class="text-sm text-orange-400">*</sup></label>
                        <input type="url" name="artwork_url" value="https://i1.sndcdn.com/artworks-large.jpg"
                            required
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>

                    <!-- Label Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Label Name</label>
                        <input type="text" name="label_name" value="some label"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>

                </div>
            </div>

            <!-- Technical Details Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Duration -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Duration (ms)</label>
                        <input type="number" name="duration" value="40000"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>

                    <!-- BPM -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">BPM</label>
                        <input type="number" name="bpm" placeholder="Optional"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>

                    <!-- Stream URL -->
                    <div class="space-y-2 md:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Stream URL</label>
                        <input type="url" name="stream_url" value="https://api.soundcloud.com/tracks/1234/stream"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>
                </div>
            </div>

            <!-- Release Information Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Release Day</label>
                        <input type="number" name="release_day" value="22" min="1" max="31"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Release Month</label>
                        <input type="number" name="release_month" value="8" min="1" max="12"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Release Year</label>
                        <input type="number" name="release_year" value="2019"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                    </div>
                </div>
            </div>
            <!-- Settings Section -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    <label
                        class="flex items-center gap-3 px-3 py-2 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                        <input type="checkbox" name="commentable" checked
                            class="checkbox-custom w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-circle" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700">Allow Comments</span>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 px-3 py-2 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                        <input type="checkbox" name="downloadable"
                            class="checkbox-custom w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700">Allow Downloads</span>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 px-3 py-2 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                        <input type="checkbox" name="streamable" checked
                            class="checkbox-custom w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="play" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700">Enable Streaming</span>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 px-3 py-2 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                        <input type="checkbox" name="user_favorite" checked
                            class="checkbox-custom w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="heart" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700">User Favorite</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="space-y-6">
                <div class="space-y-6">
                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="4" placeholder="Tell us about your track..."
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white resize-none"></textarea>
                    </div>

                    <!-- Tags -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tags</label>
                        <input type="text" name="tag_list" placeholder="#rock #alternative #indie"
                            class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                        <p class="text-xs text-gray-500">Separate tags with spaces. Use # for hashtags.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-gray-200">
                <x-button type="submit" class="">Submit</x-button>
            </div>
        </form>
    </div> --}}

    <div>
        <section>
            <div class="container mx-auto bg-white shadow-md rounded-lg p-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-text-black dark:text-text-white">
                        {{ __('Track Submit') }}
                    </h2>
                    <div class="flex items-center gap-2">
                        <x-button href="{{ route('um.user.index') }}">
                            {{ __('Back') }}
                        </x-button>
                    </div>
                </div>

                {{-- Success Message --}}
                @if (session()->has('message'))
                    <div
                        class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ session('message') }}
                    </div>
                @endif

                {{-- Warning Message --}}
                @if (session()->has('warning'))
                    <div
                        class="mt-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        {{ session('warning') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @if (session()->has('error'))
                    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit" class="space-y-6 mt-6" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Title -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Track Title<sup
                                        class="text-sm text-orange-400">*</sup></label>
                                <input type="text" wire:model="title"
                                    class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                                {{-- Error message --}}
                                @error('title')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Main Artist<sup
                                        class="text-sm text-orange-400">*</sup></label>
                                <input type="text" wire:model="artist"
                                    class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                                {{-- Error message --}}
                                @error('artist')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Artwork URL -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Track<sup
                                        class="text-sm text-orange-400">*</sup></label>
                                <input type="file" accept="audio/*"  value="" wire:model="asset_data">
                                @error('asset_data')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Track Link<sup
                                        class="text-sm text-orange-400">*</sup></label>
                                <input 
                                    type="text" 
                                    id="urlInput" wire:model="permalink"
                                    value="https://soundcloud.com/username/"
                                    x-model="permalink" 
                                    class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white"
                                    @input="if (!$event.target.value.startsWith('https://soundcloudcom/username/')) { 
                                        $event.target.value = 'https://soundcloudcom/username/' + $event.target.value.substring(24); 
                                    }"
                                    @keydown="if ($event.target.selectionStart < 30 && ['Backspace', 'Delete'].includes($event.key)) { 
                                        $event.preventDefault(); 
                                    }"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Genre</label>
                                <select class="select w-full outline-none" wire:model="genre">
                                    @foreach (allGenres() as $genre)
                                        <option value="{{ $genre }}">{{ $genre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tags --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Tags</label>
                                <input type="text"wire:model="tag_list" value="" placeholder="Add styles, moods, tempos"
                                    class="form-input w-full px-3 py-2 border border-gray-200 rounded-md focus:border-indigo-500 focus:ring-0 transition-colors duration-200 bg-gray-50 focus:bg-white">
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit" wire:loading.attr="disabled" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-md transition duration-200 flex items-center justify-center">
                            <span wire:loading.remove class="flex items-center">
                                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <span>Submit Track</span>
                            </span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                               <span>Submitting...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
