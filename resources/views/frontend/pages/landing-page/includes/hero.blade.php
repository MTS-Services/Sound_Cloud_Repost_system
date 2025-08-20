 <section class="relative pt-32 pb-20 md:pt-40 md:pb-32 overflow-hidden">
     <div class="absolute inset-0 wave-pattern opacity-10 z-0"></div>
     <div class="absolute top-0 left-0 right-0 h-full bg-gradient-to-b from-dark-darker to-dark-bg z-0"></div>

     <div class="container mx-auto px-4 relative z-10">
         <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
             <div class="lg:w-1/2 text-center lg:text-left">
                 <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                     Grow Your Music Reach
                     <span class="block text-gradient">Build Fans, Share Feedback</span>
                 </h1>
                 <p class="text-lg text-gray-300 mb-8 max-w-lg mx-auto lg:mx-0">
                     Join More than thousands of music creators and grow audiance Through our collaborative platform.
                     <br> 100% Free to use
                 </p>
                 <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                     <x-button variant="primary"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                             <path d="M9 18V5l12-2v13"></path>
                             <circle cx="6" cy="18" r="3"></circle>
                             <circle cx="18" cy="16" r="3"></circle>
                         </svg> Connect SoundCloud</x-button>
                     {{-- <button
                         class="border-2 border-primary text-primary hover:text-white hover:bg-primary duration-300 px-8 py-3 rounded-md text-lg transition-all flex items-center justify-center">
                         <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="mr-2">
                             <polygon points="5 3 19 12 5 21 5 3"></polygon>
                         </svg>
                         See How It Works
                     </button> --}}
                     <x-button variant="outline"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                             <polygon points="5 3 19 12 5 21 5 3"></polygon>
                         </svg>
                         See How It Works</x-button>
                 </div>
             </div>

             <div class="lg:w-1/2 flex justify-center">
                 <div class="relative bg-dark-lighter rounded-xl p-6 shadow-xl max-w-md w-full">
                     <div
                         class="absolute -top-3 -left-3 bg-primary rounded-full w-12 h-12 flex items-center justify-center">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="text-white">
                             <path d="M9 18V5l12-2v13"></path>
                             <circle cx="6" cy="18" r="3"></circle>
                             <circle cx="18" cy="16" r="3"></circle>
                         </svg>
                     </div>

                     <div class="mb-4 pt-4">
                         <h3 class="text-xl font-bold mb-1">Popular Right Now</h3>
                         <p class="text-sm text-gray-400">Trending tracks from the community</p>
                     </div>

                     <div
                         class="bg-dark-darker p-4 rounded-lg mb-4 hover:bg-dark-border transition-colors cursor-pointer">
                         <div class="flex items-center gap-3 mb-2">
                             <div class="w-10 h-10 rounded-full bg-gray-700 overflow-hidden flex-shrink-0">
                                 <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=40&h=40&fit=crop&crop=face"
                                     alt="Artist avatar" class="w-full h-full object-cover">
                             </div>
                             <div class="overflow-hidden">
                                 <h4 class="font-medium truncate">Night Vibes</h4>
                                 <p class="text-sm text-gray-400 truncate">electronic_producer</p>
                             </div>
                             <button
                                 class="bg-primary hover:bg-primary-hover text-white px-3 py-1.5 rounded-md text-sm transition-all ml-auto">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                     <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                 </svg>
                             </button>
                         </div>
                         <div class="waveform" id="waveform1">
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                         </div>
                     </div>

                     <div class="bg-dark-darker p-4 rounded-lg hover:bg-dark-border transition-colors cursor-pointer">
                         <div class="flex items-center gap-3 mb-2">
                             <div class="w-10 h-10 rounded-full bg-gray-700 overflow-hidden flex-shrink-0">
                                 <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=40&h=40&fit=crop&crop=face"
                                     alt="Artist avatar" class="w-full h-full object-cover">
                             </div>
                             <div class="overflow-hidden">
                                 <h4 class="font-medium truncate">Summer Dreams</h4>
                                 <p class="text-sm text-gray-400 truncate">beat_maker_92</p>
                             </div>
                             <button
                                 class="bg-primary hover:bg-primary-hover text-white px-3 py-1.5 rounded-md text-sm transition-all ml-auto">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                     <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                 </svg>
                             </button>
                         </div>
                         <div class="waveform" id="waveform2">
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                             <span></span><span></span><span></span><span></span><span></span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary/30 to-transparent">
     </div>
 </section>
