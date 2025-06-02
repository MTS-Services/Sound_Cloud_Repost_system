@props(['url' => null, 'method' => '', 'placeholder' => 'Search'])
<form action="{{ $url }}" method="{{ $method }}" class="w-full">
    <div x-data="{
        focus: false,
        searchbar_show: false,
        value: '',
        searchResults: [],
        init() {
            window.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    this.$refs.search.focus();
                    this.searchbar_show = true; // Show search bar on Ctrl+K
                }
            });
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
        x-on:click.away="tablet && (searchbar_show = false)">
        <div class="relative w-full">
            <!-- Search bar with smooth transitions -->
            <div class="join !w-full" x-show="!tablet || searchbar_show"
                x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                :class="{
                    'absolute top-0 left-0 w-full max-w-full': tablet,
                }">
                <input x-ref="search" x-model="value" @input="fetchData()"
                    class="input input-search join-item focus:outline-0 pl-6 border-border-light-tertiary dark:border-border-dark-tertiary focus-within:outline-0 w-full focus:ring-0 focus:border-border-active focus-within:border-border-active rounded-l-full transition-all duration-200 ease-linear bg-bg-light-primary dark:bg-bg-dark-primary"
                    :class="{
                        'min-w-40 xs:min-w-full xs:max-w-full': tablet,
                        'max-w-full': !tablet,
                    }"
                    placeholder="{{ $placeholder }}" name="search" @focus="focus = true" @blur="focus = false" />

                <button type="submit"
                    class="btn join-item rounded-r-full border-border-light-tertiary dark:border-border-dark-tertiary bg-bg-light-primary dark:bg-bg-dark-primary hover:bg-bg-light-secondary dark:hover:bg-bg-dark-secondary focus:outline-none pl-2 group"
                    :class="focus ? '!border-border-active bg-bg-light-secondary dark:bg-bg-dark-secondary' : ''">
                    <i data-lucide="search" class="group-hover:text-text-active transition-all duration-200 ease-in-out"
                        :class="focus ? 'animate-scalePulse' : ''"></i>
                </button>
            </div>

            <!-- Toggle button to show/hide search bar on tablet -->
            <button type="button" @click="searchbar_show = !searchbar_show" x-show="tablet"
                class="btn rounded-full border-border-light-tertiary dark:border-border-dark-tertiary bg-bg-light-primary dark:bg-bg-dark-primary hover:bg-bg-light-secondary dark:hover:bg-bg-dark-secondary focus:outline-none group"
                :class="{
                    '!border-border-active bg-bg-light-secondary dark:bg-bg-dark-secondary': focus,
                    'opacity-0 invisible': searchbar_show
                }">
                <i data-lucide="search" class="group-hover:text-text-active transition-all duration-200 ease-in-out"
                    :class="focus ? 'animate-scalePulse' : ''"></i>
                    
            </button>

            <!-- Search Results Dropdown with smooth transition -->
            <div class="absolute top-[130%] w-full max-w-full bg-bg-light-primary dark:bg-bg-dark-tertiary z-10 rounded-2xl overflow-hidden shadow-lg"
                x-show="focus && (searchResults.length > 0 || value.length > 0)"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-3"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-3"
                :class="{
                    '-left-10 sm:left-0 min-w-60 xs:min-w-96 max-w-full': tablet,
                    'left-0': !tablet,
                }">
                <div class="text-center relative h-fit min-h-24 max-h-56 overflow-auto">
                    <div class="sticky top-0 z-20 bg-bg-light-secondary dark:bg-bg-dark-tertiary">
                        <div class="p-2">
                            <h4 class="font-medium">{{ __('Search Results for') }} "<span class="font-normal break-all"
                                    x-text="value"></span>"</h4>
                            <div class="divider m-0"></div>
                        </div>
                    </div>

                    <div class="py-4 pt-0">
                        <!-- Displaying search results -->
                        <ul class="space-y-2">
                            <template x-for="result in searchResults" :key="result.id">
                                <li>
                                    <a href="#"
                                        class="block py-2 px-3 hover:bg-bg-active dark:hover:bg-bg-active/10 rounded">
                                        <span x-text="result.name"></span>
                                    </a>
                                </li>
                            </template>
                        </ul>

                        <!-- No results message -->
                        <div x-show="searchResults.length === 0" class="text-sm text-text-light-muted dark:text-text-dark-muted mt-2">
                            {{ __('No results found.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
