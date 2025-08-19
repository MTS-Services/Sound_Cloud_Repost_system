<div>
    <x-slot name="page_slug">track-submit</x-slot>
    <x-slot name="title">Track Submit</x-slot>

    <section class="mx-auto max-w-5xl">

        <div
            class="flex flex-col sm:flex-row items-center justify-between mb-10 border-b border-gray-200 dark:border-gray-800 pb-6">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                {{ __('Upload Your Track') }}
            </h2>
            <a href="{{ route('user.dashboard') }}" wire:navigate
                class="mt-4 sm:mt-0 flex items-center gap-2 bg-orange-600 text-white font-medium py-3 px-6 rounded-full hover:bg-orange-700 transition duration-300 transform hover:scale-105 shadow-md">
                <x-lucide-music class="h-5 w-5" />
                <span>{{ __('Go to Dashboard') }}</span>
            </a>
        </div>

        {{-- Status Messages Section --}}
        <div class="mb-8">
            @if (session()->has('message'))
                <div
                    class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg flex items-center space-x-3 shadow-sm transition-opacity duration-300">
                    <x-lucide-check-circle class="h-6 w-6" />
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div
                    class="p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg flex items-center space-x-3 shadow-sm transition-opacity duration-300">
                    <x-lucide-alert-triangle class="h-6 w-6" />
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <form wire:submit.prevent="submit" class="space-y-10" enctype="multipart/form-data">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="flex flex-col items-center">
                    <label class="block text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Track Artwork
                    </label>
                    <div x-data="{
                        isDragging: false,
                        artworkPreviewUrl: '',
                        artworkFileName: '',
                        artworkUploadProgress: 0,
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (!file) {
                                this.artworkFileName = '';
                                this.artworkPreviewUrl = '';
                                return;
                            }
                    
                            this.artworkFileName = file.name;
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.artworkPreviewUrl = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            } else {
                                this.artworkPreviewUrl = '';
                            }
                        }
                    }" x-on:livewire-upload-start="artworkUploadProgress = 0"
                        x-on:livewire-upload-finish="artworkUploadProgress = 100"
                        x-on:livewire-upload-error="artworkUploadProgress = 0"
                        x-on:livewire-upload-progress="artworkUploadProgress = $event.detail.progress"
                        x-on:dragover.prevent="isDragging = true" x-on:dragleave.prevent="isDragging = false"
                        x-on:drop.prevent="isDragging = false; $event.target.files = $event.dataTransfer.files; handleFileChange($event);"
                        class="relative w-full aspect-square border-4 border-dashed rounded-lg flex flex-col items-center justify-center p-6 transition-all duration-300 cursor-pointer"
                        :class="{
                            'border-orange-500 dark:border-orange-500 bg-orange-100 dark:bg-orange-900/30': isDragging ||
                                artworkPreviewUrl,
                            'border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800': !isDragging && !
                                artworkPreviewUrl
                        }">
                        <input type="file" accept="image/*" wire:model="track.artwork_data" id="artwork-upload"
                            class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileChange($event)">

                        <label for="artwork-upload"
                            class="absolute inset-0 cursor-pointer flex flex-col items-center justify-center text-center p-4">

                            {{-- Circular Progress Bar Overlay --}}
                            <div x-show="artworkUploadProgress > 0 && artworkUploadProgress < 100"
                                class="absolute inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm rounded-lg z-20">
                                <div class="relative w-24 h-24">
                                    <svg class="w-full h-full" viewBox="0 0 100 100">
                                        <circle class="text-gray-200 stroke-current dark:text-gray-700" stroke-width="8"
                                            cx="50" cy="50" r="40" fill="transparent"></circle>
                                        <circle class="text-orange-500 stroke-current transition-all duration-300"
                                            stroke-width="8" cx="50" cy="50" r="40" fill="transparent"
                                            stroke-dasharray="251.2" stroke-dashoffset="251.2"
                                            x-bind:style="`stroke-dashoffset: ${251.2 - (artworkUploadProgress / 100) * 251.2}`"
                                            transform="rotate(-90 50 50)"></circle>
                                    </svg>
                                    <span
                                        class="absolute inset-0 flex items-center justify-center text-white text-lg font-bold">
                                        <span x-text="artworkUploadProgress"></span>%
                                    </span>
                                </div>
                            </div>

                            {{-- Preview for the image --}}
                            <template x-if="artworkPreviewUrl">
                                <div class="absolute inset-0 bg-cover bg-center rounded-md"
                                    :style="`background-image: url('${artworkPreviewUrl}')`"></div>
                                <div class="absolute inset-0 bg-black/50 rounded-md"></div>
                            </template>

                            {{-- Placeholder for the image --}}
                            <div class="relative z-10 text-white dark:text-gray-200" x-show="!artworkPreviewUrl">
                                <x-lucide-image-plus
                                    class="h-12 w-12 text-orange-400 mx-auto transition-colors duration-300" />
                                <span class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Drag & Drop or <span class="text-orange-600 dark:text-orange-400">Click to
                                        Upload</span>
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, GIF up to
                                    5MB</span>
                            </div>

                            {{-- "Click to change" message for the image, including filename --}}
                            <div class="absolute bottom-4 z-10 text-white dark:text-gray-200 bg-black/60 px-2 py-0.5 rounded-md"
                                x-show="artworkPreviewUrl">
                                <span class="mt-2 text-sm font-medium text-white">Click to change</span>
                                <span class="mt-1 text-xs text-gray-200 truncate w-full px-2"
                                    x-text="artworkFileName"></span>
                            </div>
                        </label>
                    </div>
                    @error('track.artwork_data')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col items-center">
                    <label class="block text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Audio Track<sup class="text-red-500">*</sup>
                    </label>
                    <div x-data="{
                        isDragging: false,
                        mediaPreviewUrl: '',
                        audioFileName: '',
                        audioUploadProgress: 0,
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (!file) {
                                this.audioFileName = '';
                                this.mediaPreviewUrl = '';
                                return;
                            }
                    
                            this.audioFileName = file.name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.mediaPreviewUrl = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }" x-on:livewire-upload-start="audioUploadProgress = 0"
                        x-on:livewire-upload-finish="audioUploadProgress = 100"
                        x-on:livewire-upload-error="audioUploadProgress = 0"
                        x-on:livewire-upload-progress="audioUploadProgress = $event.detail.progress"
                        x-on:dragover.prevent="isDragging = true" x-on:dragleave.prevent="isDragging = false"
                        x-on:drop.prevent="isDragging = false; $event.target.files = $event.dataTransfer.files; handleFileChange($event);"
                        class="relative w-full aspect-square border-4 border-dashed rounded-lg flex flex-col items-center justify-center p-6 transition-all duration-300 cursor-pointer"
                        :class="{
                            'border-orange-500 dark:border-orange-500 bg-orange-100 dark:bg-orange-900/30': isDragging ||
                                audioFileName,
                            'border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800': !isDragging && !
                                audioFileName
                        }">
                        <input type="file" accept="audio/*, video/*" wire:model="track.asset_data" id="audio-upload"
                            class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileChange($event)">

                        <label for="audio-upload"
                            class="absolute inset-0 cursor-pointer flex flex-col items-center justify-center text-center p-4">

                            {{-- Circular Progress Bar Overlay --}}
                            <div x-show="audioUploadProgress > 0 && audioUploadProgress < 100"
                                class="absolute inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm rounded-lg z-20">
                                <div class="relative w-24 h-24">
                                    <svg class="w-full h-full" viewBox="0 0 100 100">
                                        <circle class="text-gray-200 stroke-current dark:text-gray-700" stroke-width="8"
                                            cx="50" cy="50" r="40" fill="transparent"></circle>
                                        <circle class="text-orange-500 stroke-current transition-all duration-300"
                                            stroke-width="8" cx="50" cy="50" r="40" fill="transparent"
                                            stroke-dasharray="251.2" stroke-dashoffset="251.2"
                                            x-bind:style="`stroke-dashoffset: ${251.2 - (audioUploadProgress / 100) * 251.2}`"
                                            transform="rotate(-90 50 50)"></circle>
                                    </svg>
                                    <span
                                        class="absolute inset-0 flex items-center justify-center text-white text-lg font-bold">
                                        <span x-text="audioUploadProgress"></span>%
                                    </span>
                                </div>
                            </div>

                            {{-- Preview for the audio/video file --}}
                            <template x-if="mediaPreviewUrl">
                                <div class="w-full h-full flex flex-col items-center justify-center">
                                    <template
                                        x-if="audioFileName.endsWith('.mp3') || audioFileName.endsWith('.wav') || audioFileName.endsWith('.flac') || audioFileName.endsWith('.m4a')">
                                        <audio class="w-full max-w-sm rounded-lg" controls
                                            x-bind:src="mediaPreviewUrl"></audio>
                                    </template>
                                    <template x-if="audioFileName.endsWith('.mp4') || audioFileName.endsWith('.mov')">
                                        <video class="w-full h-full object-cover rounded-lg" controls
                                            x-bind:src="mediaPreviewUrl"></video>
                                    </template>
                                    <span
                                        class="mt-4 text-sm font-medium text-gray-800 dark:text-gray-200 truncate w-full px-2 text-center"
                                        x-text="audioFileName"></span>
                                    <span class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click to change</span>
                                </div>
                            </template>

                            {{-- Placeholder for the audio file --}}
                            <template x-if="!audioFileName">
                                <div class="flex flex-col items-center">
                                    <x-lucide-file-up
                                        class="h-16 w-16 text-orange-400 transition-colors duration-300" />
                                    <span class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400"
                                        :class="{ 'text-orange-600 dark:text-orange-400': isDragging }">
                                        Drag & Drop or <span class="text-orange-600 dark:text-orange-400">Click to
                                            Upload</span>
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-500 mt-1">MP3, WAV, FLAC, M4A,
                                        MP4, MOV up to 250MB</span>
                                </div>
                            </template>
                        </label>
                    </div>
                    @error('track.asset_data')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-8 space-y-6 shadow-inner">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Track Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form.input label="Track Title" wire:model="track.title" placeholder="Enter track title"
                        required />
                    <x-form.input label="Release Title" wire:model="track.release"
                        placeholder="e.g., EP or Album Name" tip="Name of the album or EP this track belongs to." />
                    <x-form.select label="Genre" wire:model="track.genre" :options="$genres"
                        placeholder="Select a genre" />
                    <x-form.input label="Tags" wire:model="track.tag_list" placeholder="Add styles, moods, tempo."
                        tip="Separate tags with spaces. Use double quotes for multi-word tags." />
                    <x-form.input label="Label Name" wire:model="track.label_name"
                        placeholder="e.g., Record Label Name" />
                    <x-form.input label="Release Date" wire:model="track.release_date" type="date" />
                    <x-form.input label="ISRC" wire:model="track.isrc" placeholder="e.g., US-S1Z-15-00001" />
                    <x-form.input label="Purchase URL" type="url" wire:model="track.purchase_url"
                        placeholder="e.g., https://bandcamp.com/your-track" />
                </div>
                <x-form.textarea label="Description" wire:model="track.description" rows="4"
                    placeholder="Tell your fans about the track..."
                    tip="Tracks with descriptions get more engagement." />
            </div>

            <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-8 space-y-6 shadow-inner">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Sharing & Privacy</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Track
                            Privacy</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="privacy" wire:model="track.sharing" value="public"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Public</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="privacy" wire:model="track.sharing" value="private"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Private</span>
                            </label>
                        </div>
                        @error('track.sharing')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ permalink: @entangle('track.permalink') }">
                        <x-form.input type="url" label="Track Link" wire:model="track.permalink" placeholder="track-name"
                            prefix="https://soundcloud.com/" tip="This will be the public URL for your track."
                            input-id="track-link-input" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    {{-- Streamable Radio Buttons --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Streamable</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model="track.streamable" value="1"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Yes</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model="track.streamable" value="0"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">No</span>
                            </label>
                        </div>
                        @error('track.streamable')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Downloadable Radio Buttons --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Downloadable</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model="track.downloadable" value="1"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Yes</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model="track.downloadable" value="0"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">No</span>
                            </label>
                        </div>
                        @error('track.downloadable')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commentable Radio Buttons --}}
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Commentable</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model="track.commentable" value="1"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Yes</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model="track.commentable" value="0"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">No</span>
                            </label>
                        </div>
                        @error('track.commentable')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                    <x-form.select label="License" wire:model="track.license" :options="$licenses" />
                    <x-form.select label="Embeddable by" wire:model="track.embeddable_by" :options="$embeddableByOptions" />
                </div>
            </div>

            <div>
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-orange-600 hover:bg-orange-700 disabled:bg-orange-400 dark:bg-orange-1000 dark:hover:bg-orange-600 dark:disabled:bg-orange-400 text-white font-bold py-4 px-6 rounded-full transition-all duration-300 flex items-center justify-center space-x-3 shadow-lg transform hover:scale-105">
                    <span wire:loading.remove>
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-upload class="h-6 w-6" />
                            <span>Submit Track</span>
                        </div>
                    </span>
                    <span wire:loading>
                        <div class="flex items-center justify-center">
                            <x-lucide-loader-2 class="animate-spin h-6 w-6" />
                            <span class="ml-3">Submitting...</span>
                        </div>
                    </span>
                </button>
            </div>
        </form>
    </section>
</div>
{{-- 
@once
    @push('scripts')
        <script src="https://unpkg.com/lucide@latest"></script>
    @endpush
@endonce --}}
