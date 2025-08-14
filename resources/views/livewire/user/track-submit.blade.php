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
                    }" x-on:dragover.prevent="isDragging = true"
                        x-on:dragleave.prevent="isDragging = false"
                        x-on:drop.prevent="isDragging = false; $event.target.files = $event.dataTransfer.files; handleFileChange($event);"
                        class="relative w-full aspect-square border-4 border-dashed rounded-lg flex flex-col items-center justify-center p-6 transition-all duration-300 cursor-pointer"
                        :class="{
                            'border-orange-500 bg-orange-100 dark:bg-orange-900/30': isDragging,
                            'border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800': !isDragging,
                            'ring-2 ring-orange-500': artworkPreviewUrl
                        }">
                        <input type="file" accept="image/*" name="artwork" id="artwork-upload"
                            class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileChange($event)">

                        <label for="artwork-upload"
                            class="absolute inset-0 cursor-pointer flex flex-col items-center justify-center text-center p-4">

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
                            <div class="relative z-10 text-white dark:text-gray-200" x-show="artworkPreviewUrl">
                                <span class="mt-2 text-sm font-medium text-white">Click to change</span>
                                <span class="mt-1 text-xs text-gray-200 truncate w-full px-2"
                                    x-text="artworkFileName"></span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <label class="block text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Audio Track<sup class="text-red-500">*</sup>
                    </label>
                    <div x-data="{
                        isDragging: false,
                        mediaPreviewUrl: '',
                        audioFileName: '',
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
                    }" x-on:dragover.prevent="isDragging = true"
                        x-on:dragleave.prevent="isDragging = false"
                        x-on:drop.prevent="isDragging = false; $event.target.files = $event.dataTransfer.files; handleFileChange($event);"
                        class="relative w-full aspect-square border-4 border-dashed rounded-lg flex flex-col items-center justify-center p-6 transition-all duration-300 cursor-pointer"
                        :class="{
                            'border-orange-500 bg-orange-100 dark:bg-orange-900/30': isDragging,
                            'border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800': !isDragging,
                            'ring-2 ring-orange-500': audioFileName
                        }">
                        <input type="file" accept="audio/*, video/*" name="asset_data" id="audio-upload"
                            class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileChange($event)">

                        <label for="audio-upload"
                            class="absolute inset-0 cursor-pointer flex flex-col items-center justify-center text-center p-4">

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
                </div>
            </div>

            <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-8 space-y-6 shadow-inner">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Track Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form.input label="Track Title" wire:model="title" placeholder="e.g., Summer Anthem" required />
                    <x-form.input label="Main Artist(s)" wire:model="artist"
                        placeholder="e.g., Mahfuz Ahmed, The Artist" required
                        tip="Use commas to separate multiple artists." />
                    <x-form.select label="Genre" wire:model="genre" :options="allGenres()" placeholder="Select a genre" />
                    <x-form.input label="Tags" wire:model="tag_list" placeholder="e.g., electronic chill 120bpm"
                        tip="Separate tags with spaces. Use double quotes for multi-word tags." />
                </div>
                <x-form.textarea label="Description" wire:model="description" rows="4"
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
                                <input type="radio" name="privacy" wire:model="sharing" value="public"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Public</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="privacy" wire:model="sharing" value="private"
                                    class="form-radio text-orange-600 h-5 w-5 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Private</span>
                            </label>
                        </div>
                        @error('sharing')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div x-data="{ permalink: @entangle('permalink') }">
                        <x-form.input label="Track Link" wire:model="permalink" placeholder="track-name"
                            prefix="https://soundcloud.com/" tip="This will be the public URL for your track."
                            input-id="track-link-input" />
                    </div>
                </div>
            </div>

            <div class="pt-8">
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-orange-600 hover:bg-orange-700 disabled:bg-orange-400 dark:bg-orange-1000 dark:hover:bg-orange-600 dark:disabled:bg-orange-400 text-white font-bold py-4 px-6 rounded-full transition-all duration-300 flex items-center justify-center space-x-3 shadow-lg transform hover:scale-105">
                    <span wire:loading.remove class="flex items-center gap-2">
                        <x-lucide-upload class="h-6 w-6" />
                        <span>Submit Track</span>
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <x-lucide-loader-2 class="animate-spin h-6 w-6" />
                        <span class="ml-3">Submitting...</span>
                    </span>
                </button>
            </div>
        </form>
    </section>
</div>

@php
    if (!function_exists('allGenres')) {
        function allGenres()
        {
            return [
                'Electronic',
                'Dance',
                'Hip Hop & Rap',
                'Pop',
                'R&B & Soul',
                'Rock',
                'Ambient',
                'Classical',
                'Country',
                'Disco',
                'Dubstep',
                'Folk',
                'House',
                'Jazz',
                'Latin',
                'Metal',
                'Piano',
                'Reggae',
                'Techno',
                'Trance',
            ];
        }
    }
@endphp

@once
    @push('scripts')
        <script src="https://unpkg.com/lucide@latest"></script>
    @endpush
@endonce
