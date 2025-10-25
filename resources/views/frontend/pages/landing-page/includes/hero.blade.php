 <section class="relative pt-32 pb-20 md:pt-40 md:pb-32 overflow-hidden" id="home">

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
                     <x-gabutton href="{{ route('soundcloud.redirect') }}" variant="primary"><svg
                             xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="mr-2">
                             <path d="M9 18V5l12-2v13"></path>
                             <circle cx="6" cy="18" r="3"></circle>
                             <circle cx="18" cy="16" r="3"></circle>
                         </svg> Connect SoundCloud</x-gabutton>
                     {{-- <button
                         class="border-2 border-primary text-primary hover:text-white hover:bg-primary duration-300 px-8 py-3 rounded-md text-lg transition-all flex items-center justify-center">
                         <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="mr-2">
                             <polygon points="5 3 19 12 5 21 5 3"></polygon>
                         </svg>
                         See How It Works
                     </button> --}}
                     <x-gbutton variant="outline"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                             <polygon points="5 3 19 12 5 21 5 3"></polygon>
                         </svg>
                         See How It Works</x-gbutton>
                 </div>
             </div>

             <div class="lg:w-1/2 flex justify-center">
                 <div class="relative hidden lg:flex justify-end">
                     <div class="relative w-full max-w-2xl">
                         <img src="{{ asset('frontend/user/image/FeaturedArtist.png') }}" alt="Featured Artist"
                             class="w-full h-auto relative z-10">
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
