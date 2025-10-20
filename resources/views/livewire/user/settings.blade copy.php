<div>
    <section>
    <x-slot name="page_slug">settings</x-slot>
    {{-- error check --}}

    <style>
        /* Custom styles for the active tab indicator */
        .tab-indicator {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: orange;
            /* Blue color for the active tab */
            transition: transform 0.3s ease-in-out;
        }

        /* Hide cloaked elements until Alpine shows them */
        [x-cloak] {
            display: none !important;
        }
    </style>

<div>


<div class="flex-1 bg-gray-50">
  <div class="max-w-[90rem] mx-auto px-4 py-4">
    <!-- Card -->
    <div class="bg-white rounded-lg shadow-sm">
      
      <!-- Tabs -->
      <div class="border-b border-gray-200">
        <div class="flex gap-8 px-6">
          <button class="py-3 text-sm font-medium border-b-2 transition-colors border-orange-500 text-orange-600">
            Edit profile
            <span class="tab-indicator {{ $activeTab === 'profile' ? '' : 'hidden' }}"></span>
          </button>
          <button class="py-3 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900">
            Notifications &amp; alerts
                        <span class="tab-indicator {{ $activeTab === 'notifications' ? '' : 'hidden' }}"></span>

          </button>
          <button class="py-3 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900">
            Settings
          </button>
          <button class="py-3 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900">
            Credit history
          </button>
          <button class="py-3 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900">
            Invoices
          </button>
        </div>
      </div>

      <!-- Card content -->
      <div class="p-6">
        <!-- Quick Tip Banner -->
        <div class="mb-4 relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-400 to-amber-400 p-3 rounded-xl shadow-lg">
          <!-- Decorative circles -->
          <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
          <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full -ml-10 -mb-10"></div>

          <div class="relative flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
              <!-- Lightbulb icon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="lucide lucide-lightbulb w-4 h-4 text-white">
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                <path d="M9 18h6"></path>
                <path d="M10 22h4"></path>
              </svg>
            </div>

            <div class="flex-1">
              <div class="flex items-center justify-between gap-4">
                <div>
                  <h4 class="font-bold text-white mb-0.5 text-sm">Quick Tip</h4>
                  <p class="text-white/95 text-xs leading-relaxed">
                    Customise your genres to personalise your Repostchaine experience.
                  </p>
                </div>

                <button class="flex-shrink-0 p-1 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all"
                        aria-label="dismiss quick tip">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-x w-4 h-4">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Form / Inputs -->
        <div class="space-y-4">
          <!-- Email -->
          <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Email Address</label>
            <input type="email"
                   class="w-full px-3 py-2.5 bg-white border-2 border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-gray-400"
                   placeholder="your.email@example.com"
                   value="dilip.udaya1219@gmail.com">
          </div>

          <!-- Genres (tags + input) -->
          <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Your Music Genres</label>
            <div class="relative">
              <div
                class="flex flex-wrap gap-2 p-3 bg-white border-2 border-gray-200 rounded-lg min-h-[48px] focus-within:ring-4 focus-within:ring-orange-500/20 focus-within:border-orange-500 transition-all">
                
                <!-- Example tag -->
                <span class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                  Hip-hop &amp; Rap
                  <button class="p-0.5 hover:bg-white/20 rounded-md transition-all" aria-label="remove tag">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-x w-3 h-3">
                      <path d="M18 6 6 18"></path>
                      <path d="m6 6 12 12"></path>
                    </svg>
                  </button>
                </span>

                <!-- More example tags -->
                <span class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                  Pop
                  <button class="p-0.5 hover:bg-white/20 rounded-md transition-all" aria-label="remove tag">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-x w-3 h-3">
                      <path d="M18 6 6 18"></path>
                      <path d="m6 6 12 12"></path>
                    </svg>
                  </button>
                </span>

                <span class="group inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
                  Rock
                  <button class="p-0.5 hover:bg-white/20 rounded-md transition-all" aria-label="remove tag">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-x w-3 h-3">
                      <path d="M18 6 6 18"></path>
                      <path d="m6 6 12 12"></path>
                    </svg>
                  </button>
                </span>

                <!-- Input for adding tags -->
                <input type="text"
                       class="flex-1 min-w-[140px] outline-none text-gray-900 placeholder:text-gray-400 text-xs font-medium"
                       placeholder="Type and press Enter..."
                       value="">
              </div>

              <!-- Search icon (decorative) -->
              <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-search w-4 h-4 text-gray-400">
                  <circle cx="11" cy="11" r="8"></circle>
                  <path d="m21 21-4.3-4.3"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Social Media Accounts -->
          <div class="bg-gradient-to-br from-gray-50 to-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="mb-3">
              <label class="block text-sm font-bold text-gray-900 mb-0.5">Social Media Accounts</label>
              <p class="text-xs text-gray-600 leading-relaxed">
                Connect your social profiles to get promoted when someone reposts your tracks (Pro Plan feature)
              </p>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
              <!-- Instagram -->
              <div class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-pink-300 transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                  <!-- instagram icon -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-instagram w-4 h-4 text-white">
                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                  </svg>
                </div>
                <input type="text" class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                       placeholder="@username" value="">
              </div>

              <!-- Twitter -->
              <div class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-blue-300 transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-twitter w-4 h-4 text-white">
                    <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                  </svg>
                </div>
                <input type="text" class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                       placeholder="@username" value="">
              </div>

              <!-- Facebook -->
              <div class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-blue-400 transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-facebook w-4 h-4 text-white">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                  </svg>
                </div>
                <input type="text" class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                       placeholder="/pagelink" value="">
              </div>

              <!-- YouTube -->
              <div class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-red-300 transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-youtube w-4 h-4 text-white">
                    <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path>
                    <path d="m10 15 5-3-5-3z"></path>
                  </svg>
                </div>
                <input type="text" class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                       placeholder="Channel ID" value="">
              </div>

              <!-- Music (generic) -->
              <div class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-gray-400 transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-gray-800 to-black flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-music w-4 h-4 text-white">
                    <path d="M9 18V5l12-2v13"></path>
                    <circle cx="6" cy="18" r="3"></circle>
                    <circle cx="18" cy="16" r="3"></circle>
                  </svg>
                </div>
                <input type="text" class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                       placeholder="@username" value="">
              </div>

              <!-- Spotify / artist link (span 2 columns) -->
              <div class="group relative bg-white rounded-lg border-2 border-gray-200 overflow-hidden hover:border-green-300 transition-all col-span-2">
                <div class="absolute left-0 top-0 bottom-0 w-10 bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="lucide lucide-music w-4 h-4 text-white">
                    <path d="M9 18V5l12-2v13"></path>
                    <circle cx="6" cy="18" r="3"></circle>
                    <circle cx="18" cy="16" r="3"></circle>
                  </svg>
                </div>
                <input type="text" class="w-full pl-12 pr-3 py-2.5 bg-transparent focus:outline-none text-gray-900 placeholder:text-gray-400 font-medium text-xs"
                       placeholder="Artist link (https://open.spotify.com/artist/...)" value="">
              </div>
            </div>
          </div>
        </div>

        <!-- Footer actions -->
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
          <div class="text-xs text-gray-600">
            Looking to delete your account?
            <button class="text-red-600 hover:text-red-700 font-semibold hover:underline transition-all">Click here</button>.
          </div>

          <div class="flex gap-2">
            <button class="px-5 py-2 border-2 border-gray-200 text-gray-700 font-semibold rounded-lg text-sm hover:bg-gray-50 hover:border-gray-300 transition-all">
              Cancel
            </button>
            <button class="px-5 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg text-sm hover:from-orange-600 hover:to-orange-700 shadow-sm hover:shadow-md transition-all">
              Save Profile
            </button>
          </div>
        </div>

      </div> <!-- end card content -->
    </div> <!-- end card -->
  </div> <!-- end container -->
</div> <!-- end page wrapper -->

</section>

</div>