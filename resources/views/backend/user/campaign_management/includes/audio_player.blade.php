{{-- <div
    class="bg-gray-800 text-white p-4 flex items-center justify-between w-full min-h-[80px] fixed bottom-0 z-50">
    <!-- Hidden Audio Element -->
    <audio id="audioPlayer" preload="metadata">
        <source src="https://www.soundjay.com/misc/sounds/bell-ringing-05.wav" type="audio/wav">
        <source src="{{ asset('assets/audios/MAIN_WOH_CHAAND.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <!-- Left Section - Album Info -->
    <div class="flex items-center space-x-4 flex-shrink-0">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 rounded-md flex-shrink-0">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-J4fCb9ipRfam7Lcf4pARaLRDUCbm9N.png"
                alt="Album artwork" class="w-full h-full object-cover rounded-md">
        </div>
        <div class="min-w-0">
            <h3 class="text-white font-medium text-sm truncate">Come follow me</h3>
            <p class="text-gray-400 text-xs truncate">Dom-DMR</p>
        </div>
    </div>

    <!-- Center Section - Player Controls -->
    <div class="flex-1 max-w-2xl mx-8">
        <div class="flex items-center justify-center space-x-4 mb-2">
            <!-- Previous Button -->
            <button id="prevBtn" class="text-white hover:text-[#ff4500] transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" />
                </svg>
            </button>

            <!-- Play/Pause Button -->
            <button 
                                    class="playPauseBtn bg-white text-gray-800 rounded-full p-2 hover:bg-gray-200 transition-colors">
                                    <svg  class="playIcon w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <svg class="pauseIcon w-4 h-4 hidden" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                    </svg>
                                </button>
            <!-- Next Button -->
            <button id="nextBtn" class="nextBtntext-white hover:text-[#ff4500] transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" />
                </svg>
            </button>
        </div>

        <!-- Progress Bar -->
        <div class="flex items-center space-x-3">
            <span id="currentTime" class="text-xs text-gray-400 font-mono">00:00</span>
            <div class="flex-1 bg-gray-600 rounded-full h-1 relative cursor-pointer" id="progressContainer">
                <div id="progressBar" class="bg-[#ff4500] h-1 rounded-full relative" style="width: 0%;">
                    <div id="progressHandle"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg opacity-0 transition-opacity">
                    </div>
                </div>
            </div>
            <span id="duration" class="text-xs text-gray-400 font-mono">00:00</span>
        </div>
    </div>

    <!-- Right Section - Actions -->
    <div class="flex items-center space-x-3 flex-shrink-0">
        <button
            class="bg-[#ff4500] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-600 transition-colors flex items-center space-x-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M15.73 3H8.27L3 8.27v7.46L8.27 21h7.46L21 15.73V8.27L15.73 3zM12 17.3c-.72 0-1.3-.58-1.3-1.3 0-.72.58-1.3 1.3-1.3.72 0 1.3.58 1.3 1.3 0 .72-.58 1.3-1.3 1.3zm1-4.3h-2V7h2v6z" />
            </svg>
            <span>Report</span>
        </button>

        <button class="bg-[#ff4500] text-white p-2 rounded-full hover:bg-orange-600 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
            </svg>
        </button>
    </div>
</div> --}}
