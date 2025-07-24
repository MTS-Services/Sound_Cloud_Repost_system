<x-user::layout>
    <x-slot name="page_slug">repost-feed</x-slot>
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('Repost Feed') }}</h2>
            <p class="text-gray-600 dark:text-gray-400">{{ __('Repost tracks to earn credits') }}</p>
        </div>
        <div
            class="shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-2 px-3 py-2 rounded-md w-24 h-10 dark:bg-gray-800">
            <i data-lucide="filter" class="w-4 h-4"></i>
            <span>{{ __('Filter') }}</span>
        </div>
    </div>
    {{-- @foreach ($tracks as $track)
        <div class="p-3 my-6 shadow-md rounded-lg dark:bg-gray-800">
            <div class="flex justify-between">
                <div class="flex justify-start gap-3">
                    <div class="w-56 h-36 rounded-md overflow-hidden">
                        <img src="{{ $track->artwork_url }}" class="w-full h-full" alt="{{$track->title}}">
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $track->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('WaveMaker') }}</p>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                                4:45
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                                {{ __('2,458 plays') }}
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                                {{ __('458 likes') }}
                            </div>
                        </div>
                        <div class="flex space-x-2 mt-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                                {{ __('Electronic') }}</span>
                            <span
                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex justify-center items-center gap-2">
                        <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">90%</p>
                    </div>
                    <div
                        class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                        <i data-lucide="refresh cw" class="w-4 h-4"></i>
                        <a href="javascript:void(0);" class="text-sm"
                            onclick="event.preventDefault(); document.getElementById('repost_track_{{ $track->id }}').submit();">{{ __('Repost Track') }}</a>
                        <form action="{{ route('user.repost.store', encrypt($track->id)) }}" method="post"
                            class="hidden" id="repost_track_{{ $track->id }}"> @csrf </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}
    <div class="p-3 my-6 shadow-md rounded-lg dark:bg-gray-800 w-full max-w-8xl">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                     <div class="w-56 h-36 rounded-md overflow-hidden">
                <img src="{{ asset('frontend/user/image/music-2.jpg') }}" class="w-full h-full object-cover" alt="">
            </div>
                </div>
                <div class="flex-1 w-full max-w-6xl">
                 <div class="w-full max-w-5xl bg-gray-100 border border-gray-300 rounded-lg shadow-lg p-4">
    <!-- Top Info & Play Button -->
                <div class="flex items-center justify-between">
                <button id="playBtn" class="bg-orange-500 text-white p-3 rounded-full hover:bg-orange-600 transition text-lg">
                    ▶️
                </button>
                <div class="flex flex-col mx-4 truncate">
                    <span class="text-sm text-gray-600 font-semibold truncate">Mar (@marquez800k)</span>
                    <span class="text-lg font-bold truncate">my time prod. dmntxo</span>
                </div>
                <div id="timeDisplay" class="text-sm text-gray-700 font-mono w-12 text-right">0:00</div>
                </div>

                <!-- Canvas-based waveform -->
                <div class="mt-6 bg-gray-300 h-20 w-full rounded overflow-hidden relative">
                <canvas id="waveformCanvas" class="w-full h-full block"></canvas>
                </div>

                <!-- Progress Bar -->
                <div id="progressContainer" class="mt-3 relative w-full h-2 bg-gray-200 rounded cursor-pointer">
                <div id="progressBar" class="absolute top-0 left-0 h-full bg-orange-500 rounded transition-all duration-200" style="width: 0%;"></div>
                </div>
                <div class="text-right text-xs text-gray-600 mt-1" id="durationDisplay">0:00</div>

                <!-- Hidden Audio Element -->
                <audio id="audio" preload="auto">
                <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg" />
                Your browser does not support the audio element.
                </audio>
            </div>

                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="p-3 my-6 shadow-md rounded-lg dark:bg-gray-800">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                    <img src="{{ asset('frontend/user/image/music-3.jpg') }}" class="w-full h-full" alt="">
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Summer Hits') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('WaveMaker') }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="p-3 my-6 shadow-md rounded-lg dark:bg-gray-800">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                    <img src="{{ asset('frontend/user/image/music-4.jpg') }}" class="w-full h-full" alt="">
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Summer Hits') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('WaveMaker') }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div> --}}
    @push('js')
<script>
    const totalBars = 200;
    const audio = document.getElementById('audio');
    const playBtn = document.getElementById('playBtn');
    const timeDisplay = document.getElementById('timeDisplay');
    const durationDisplay = document.getElementById('durationDisplay');
    const progressBar = document.getElementById('progressBar');
    const progressContainer = document.getElementById('progressContainer');
    const waveformCanvas = document.getElementById('waveformCanvas');
    const ctx = waveformCanvas.getContext('2d');

    let isPlaying = false;
    let animationId = null;
    let waveformHeights = new Array(totalBars).fill(50); // Initial average height
    let targetHeights = new Array(totalBars).fill(50);   // Target values for smooth transition

    function setCanvasSize() {
      waveformCanvas.width = waveformCanvas.offsetWidth;
      waveformCanvas.height = waveformCanvas.offsetHeight;
    }

    setCanvasSize();
    window.addEventListener('resize', () => {
      setCanvasSize();
      drawWaveform(audio.currentTime / audio.duration || 0);
    });

    const drawWaveform = (progress, animate = false) => {
      ctx.clearRect(0, 0, waveformCanvas.width, waveformCanvas.height);
      const barWidth = waveformCanvas.width / totalBars;
      const activeBars = Math.floor(progress * totalBars);

      for (let i = 0; i < totalBars; i++) {
        if (animate) {
          // Smoothly approach the target height
          if (Math.abs(waveformHeights[i] - targetHeights[i]) < 1) {
            targetHeights[i] = 30 + Math.random() * 40; // Reduced jump
          } else {
            waveformHeights[i] += (targetHeights[i] - waveformHeights[i]) * 0.1; // Smooth speed
          }
        }

        const height = waveformHeights[i];
        const barHeight = (height / 100) * waveformCanvas.height;
        const x = i * barWidth;
        const y = waveformCanvas.height - barHeight;
        ctx.fillStyle = i < activeBars ? '#f97316' : '#4b5563';
        ctx.fillRect(x, y, barWidth - 1, barHeight);
      }
    };

    const animateWaveform = () => {
      const currentTime = audio.currentTime;
      const duration = audio.duration || 1;
      const progress = currentTime / duration;
      drawWaveform(progress, true);
      animationId = requestAnimationFrame(animateWaveform);
    };

    const stopAnimation = () => {
      cancelAnimationFrame(animationId);
      drawWaveform(audio.currentTime / audio.duration || 0, false);
    };

    const formatTime = (sec) => {
      const minutes = Math.floor(sec / 60);
      const seconds = Math.floor(sec % 60).toString().padStart(2, '0');
      return `${minutes}:${seconds}`;
    };

    playBtn.addEventListener('click', () => {
      isPlaying ? audio.pause() : audio.play();
    });

    audio.addEventListener('play', () => {
      playBtn.textContent = '⏸️';
      isPlaying = true;
      animateWaveform();
    });

    audio.addEventListener('pause', () => {
      playBtn.textContent = '▶️';
      isPlaying = false;
      stopAnimation();
    });

    audio.addEventListener('timeupdate', () => {
      const currentTime = audio.currentTime;
      const duration = audio.duration || 1;
      const progress = currentTime / duration;
      timeDisplay.textContent = formatTime(currentTime);
      durationDisplay.textContent = formatTime(duration);
      progressBar.style.width = `${progress * 100}%`;
    });

    audio.addEventListener('ended', () => {
      playBtn.textContent = '▶️';
      isPlaying = false;
      timeDisplay.textContent = '0:00';
      progressBar.style.width = `0%`;
      stopAnimation();
    });

    progressContainer.addEventListener('click', (e) => {
      const rect = progressContainer.getBoundingClientRect();
      const percentage = (e.clientX - rect.left) / rect.width;
      audio.currentTime = percentage * audio.duration;
    });

    audio.addEventListener('loadedmetadata', () => {
      durationDisplay.textContent = formatTime(audio.duration);
      drawWaveform(0);
    });

    if (audio.readyState >= 1) {
      durationDisplay.textContent = formatTime(audio.duration);
      drawWaveform(0);
    }
  </script>
@endpush
</x-user::layout>

