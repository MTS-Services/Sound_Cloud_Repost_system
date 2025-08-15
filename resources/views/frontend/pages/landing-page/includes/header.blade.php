 <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent backdrop-blur-sm">
     <div class="container mx-auto px-4 py-4 flex justify-between items-center">
         <div class="flex gap-2 items-center">
             <div class="w-7 h-7 md:w-8 md:h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                 <span class="text-slate-100 dark:text-black font-bold text-md md:text-lg">R</span>
             </div>
             <span class="text-slate-100 dark:text-black font-bold text-lg md:text-xl hidden sm:block">
                 REPOST<span class="text-orange-500">CHAIN</span>
             </span>
         </div>

         <nav class="hidden md:flex items-center space-x-8">
             <a href="#about" class="text-gray-300 hover:text-white transition-colors">About</a>
             <a href="#how-it-works" class="text-gray-300 hover:text-white transition-colors">How it Works</a>
             <a href="#features" class="text-gray-300 hover:text-white transition-colors">Features</a>
             <a href="#testimonials" class="text-gray-300 hover:text-white transition-colors">Testimonials</a>
             {{-- <a href="#faq" class="text-gray-300 hover:text-white transition-colors">FAQ</a> --}}
             {{-- <button class="p-2 rounded-full hover:bg-dark-lighter text-gray-300" id="theme-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M12 2v2"></path>
                        <path d="M12 20v2"></path>
                        <path d="m4.93 4.93 1.41 1.41"></path>
                        <path d="m17.66 17.66 1.41 1.41"></path>
                        <path d="M2 12h2"></path>
                        <path d="M20 12h2"></path>
                        <path d="m6.34 17.66-1.41 1.41"></path>
                        <path d="m19.07 4.93-1.41 1.41"></path>
                    </svg>
                </button> --}}

             @if (Auth::check())
                 <a href="{{ route('user.dashboard') }}"
                     class="bg-orange-600 rounded-md  px-3 py-1 text-white hover:bg-orange-700 transition-colors">{{ __('Dashboard') }}</a>
             @else
                 <a href="{{ route('soundcloud.redirect') }}"
                     class="w-fit px-2 py-1 flex gap-2 items-center justify-center border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                     <svg width="24" height="24" viewBox="0 0 512 512" fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg">
                         <path
                             d="M437.019 211.35c-13.398 0-26.193 2.519-38.018 7.477-7.664-67.457-65.098-120.037-134.644-120.037-6.837 0-13.672 0.557-20.342 1.66-9.725 1.558-16.883 10.135-16.883 20.004v231.77c0 11.175 9.064 20.238 20.238 20.238h189.649c49.452 0 89.981-40.529 89.981-89.981s-40.529-89.981-89.981-89.981zM65.26 239.723c-11.156 0-20.238 9.082-20.238 20.238v81.481c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238v-81.481c0-11.156-9.082-20.238-20.238-20.238zm53.744-34.942c-11.156 0-20.238 9.082-20.238 20.238v116.423c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238V224.999c0-11.156-9.082-20.238-20.238-20.238zm53.744-25.26c-11.156 0-20.238 9.082-20.238 20.238v141.683c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238V200.02c0-11.156-9.082-20.238-20.238-20.238zm53.744-13.385c-11.156 0-20.238 9.082-20.238 20.238v154.98c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238V186.376c0-11.156-9.082-20.238-20.238-20.238z" />
                     </svg>

                     Continue with SoundCloud
                 </a>
             @endif

         </nav>

         <div class="md:hidden flex items-center space-x-4">
             <button class="p-2 rounded-full hover:bg-dark-lighter text-gray-300" id="theme-toggle-mobile">
                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                     <circle cx="12" cy="12" r="4"></circle>
                     <path d="M12 2v2"></path>
                     <path d="M12 20v2"></path>
                     <path d="m4.93 4.93 1.41 1.41"></path>
                     <path d="m17.66 17.66 1.41 1.41"></path>
                     <path d="M2 12h2"></path>
                     <path d="M20 12h2"></path>
                     <path d="m6.34 17.66-1.41 1.41"></path>
                     <path d="m19.07 4.93-1.41 1.41"></path>
                 </svg>
             </button>
             <button class="text-gray-200 hover:text-white" id="mobile-menu-toggle">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                     <line x1="4" x2="20" y1="12" y2="12"></line>
                     <line x1="4" x2="20" y1="6" y2="6"></line>
                     <line x1="4" x2="20" y1="18" y2="18"></line>
                 </svg>
             </button>
         </div>
     </div>

     <!-- Mobile Menu -->
     <div class="mobile-menu bg-dark-card border-t border-dark-border md:hidden" id="mobile-menu">
         <div class="container mx-auto px-4 py-4 space-y-4">
             <a href="#about" class="block text-gray-300 hover:text-white transition-colors">About</a>
             <a href="#how-it-works" class="block text-gray-300 hover:text-white transition-colors">How it Works</a>
             <a href="#features" class="block text-gray-300 hover:text-white transition-colors">Features</a>
             <a href="#testimonials" class="block text-gray-300 hover:text-white transition-colors">Testimonials</a>
             <a href="#faq" class="block text-gray-300 hover:text-white transition-colors">FAQ</a>
             <div class="pt-4">
                 <a href="{{ route('soundcloud.redirect') }}"
                     class="w-fit px-2 py-1 flex gap-2 items-center justify-center border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                     <svg width="24" height="24" viewBox="0 0 512 512" fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg">
                         <path
                             d="M437.019 211.35c-13.398 0-26.193 2.519-38.018 7.477-7.664-67.457-65.098-120.037-134.644-120.037-6.837 0-13.672 0.557-20.342 1.66-9.725 1.558-16.883 10.135-16.883 20.004v231.77c0 11.175 9.064 20.238 20.238 20.238h189.649c49.452 0 89.981-40.529 89.981-89.981s-40.529-89.981-89.981-89.981zM65.26 239.723c-11.156 0-20.238 9.082-20.238 20.238v81.481c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238v-81.481c0-11.156-9.082-20.238-20.238-20.238zm53.744-34.942c-11.156 0-20.238 9.082-20.238 20.238v116.423c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238V224.999c0-11.156-9.082-20.238-20.238-20.238zm53.744-25.26c-11.156 0-20.238 9.082-20.238 20.238v141.683c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238V200.02c0-11.156-9.082-20.238-20.238-20.238zm53.744-13.385c-11.156 0-20.238 9.082-20.238 20.238v154.98c0 11.156 9.082 20.238 20.238 20.238s20.238-9.082 20.238-20.238V186.376c0-11.156-9.082-20.238-20.238-20.238z" />
                     </svg>

                     Continue with SoundCloud
                 </a>
             </div>
         </div>
     </div>
 </header>
