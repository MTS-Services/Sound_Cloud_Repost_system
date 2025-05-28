 @props(['placeholder' => 'Search'])

 <div class="relative">
     <div class="join w-full" x-show="!mobile && searchbar_expanded"
         :class="{
             'absolute top-0 left-0 w-full max-w-full': mobile && searchbar_expanded,
         }">
         <input x-ref="search" x-model="value" @input="fetchData()"
             class="input input-search join-item focus:outline-0 pl-6 border-border-light-tertiary dark:border-border-dark-tertiary focus-within:outline-0 focus:ring-0 focus:border-border-active focus-within:border-border-active w-full rounded-l-full transition-all duration-200 ease-linear bg-bg-light-primary dark:bg-bg-dark-primary"
             placeholder="{{ $placeholder }}" @focus="focus = true" @blur="focus = false" />

         <button type="submit"
             class="btn join-item rounded-r-full border-border-light-tertiary dark:border-border-dark-tertiary bg-bg-light-primary dark:bg-bg-dark-primary hover:bg-bg-light-secondary dark:hover:bg-bg-dark-secondary focus:outline-none pl-2 group"
             :class="focus ? '!border-border-active bg-bg-light-secondary dark:bg-bg-dark-secondary' : ''">
             <i data-lucide="search" class="group-hover:text-text-active transition-all duration-200 ease-in-out"
                 :class="focus ? 'animate-scalePulse' : ''"></i>
         </button>
     </div>

     <button type="button" @click="searchbar_expanded = !searchbar_expanded" x-show="mobile && !searchbar_expanded"
         class="btn rounded-full border-border-light-tertiary dark:border-border-dark-tertiary bg-bg-light-primary dark:bg-bg-dark-primary hover:bg-bg-light-secondary dark:hover:bg-bg-dark-secondary focus:outline-none group"
         :class="focus ? '!border-border-active bg-bg-light-secondary dark:bg-bg-dark-secondary' : ''">
         <i data-lucide="search" class="group-hover:text-text-active transition-all duration-200 ease-in-out"
             :class="focus ? 'animate-scalePulse' : ''"></i>
     </button>

     <div class="absolute top-[130%] left-0 w-full max-w-full bg-bg-light-tertiary dark:bg-bg-dark-tertiary z-10 rounded-2xl overflow-hidden shadow-lg transition-all duration-300 ease-in-out"
         x-show="focus && (searchResults.length > 0 || value.length > 0)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-3">
         <div class="text-center relative h-fit min-h-24 max-h-56 overflow-auto">
             <div class="sticky top-0 z-20 bg-bg-light-tertiary dark:bg-bg-dark-tertiary">
                 <div class="p-2">
                     <h4 class="font-medium">{{ __('Search Results for') }} "<span class="font-normal"
                             x-text="value"></span>"</h4>
                     <div class="divider m-0"></div>
                 </div>
             </div>

             <div class="p-4 pt-0">
                 <!-- Displaying search results -->
                 <ul class="space-y-2">
                     <template x-for="result in searchResults" :key="result.id">
                         <li>
                             <a href="#"
                                 class="block py-2 px-3 hover:bg-bg-active dark:hover:bg-gray-700 rounded">
                                 <span x-text="result.name"></span>
                             </a>
                         </li>
                     </template>
                 </ul>

                 <!-- No results message -->
                 <div x-show="searchResults.length === 0" class="text-sm text-gray-500 mt-2">
                     {{ __('No results found.') }}
                 </div>
             </div>
         </div>
     </div>
 </div>




 {{-- Search Form  --}}
 @props(['url' => null, 'method' => '', 'placeholder' => 'Search'])
<form action="{{ $url }}" method="{{ $method }}" class="w-full">
    <div x-data="{
        focus: false,
        mobile: false,
        searchbar_expanded: false,
        value: '',
        searchResults: [],
        init() {
            window.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    this.$refs.search.focus();
                }
            });
    
            // Check the initial width and update `mobile` state
            this.updateMobileState();
    
            // Listen to window resize events
            window.addEventListener('resize', () => {
                this.updateMobileState();
            });
        },
        updateMobileState() {
            this.mobile = window.innerWidth < 640;
        },
        fetchData() {
            if (this.value.length >= 3) {
                fetch(`http://localhost:8000/temp/search.json?q=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        this.searchResults = data; // Use data directly instead of data.results
                    })
                    .catch(error => {
                        console.error('Error fetching search data:', error);
                    });
            } else {
                this.searchResults = []; // Clear search results if the input is too short
            }
        }
    }" x-init="init()"
        class="searchForm transition-all duration-300 ease-in-out scale-95 max-w-[500px] min-w-64 lg:min-w-96 z-50">
        <x-admin.search-input :placeholder="$placeholder" />
    </div>
</form>

