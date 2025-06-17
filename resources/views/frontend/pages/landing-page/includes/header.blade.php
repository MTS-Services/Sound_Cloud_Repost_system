 <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent backdrop-blur-sm">
     <div class="container mx-auto px-4 py-4 flex justify-between items-center">
         <div class="flex items-center">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="h-8 w-8 text-primary mr-2">
                 <path d="M9 18V5l12-2v13"></path>
                 <circle cx="6" cy="18" r="3"></circle>
                 <circle cx="18" cy="16" r="3"></circle>
             </svg>
             <span class="text-xl font-bold">RepostChain</span>
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
             <a href="{{ route('login') }}"
                 class="border-2 border-primary text-primary hover:bg-primary/10 px-3 py-1.5 rounded-md text-sm transition-all">Log
                 In</a>
             <a href=" {{ route('register') }}"
                 class="bg-primary hover:bg-primary-hover text-white px-3 py-1.5 rounded-md text-sm transition-all shadow-md hover:shadow-lg">Sign
                 Up</a>
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
             <div class="flex space-x-4 pt-4">
                 <a href="{{ route('login') }}"
                     class="border-2 border-primary text-primary hover:bg-primary/10 px-3 py-1.5 rounded-md text-sm transition-all">Log
                     In</a>
                 <a href=" {{ route('register') }}"
                     class="bg-primary hover:bg-primary-hover text-white px-3 py-1.5 rounded-md text-sm transition-all">Sign
                     Up</a>
             </div>
         </div>
     </div>
 </header>
