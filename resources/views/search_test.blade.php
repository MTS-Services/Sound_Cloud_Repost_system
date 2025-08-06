<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Track;
use Illuminate\Support\Collection;

class TrackTagsSearch extends Component
{
    public $search = '';
    public $selectedTags = [];
    public $suggestedTags = [];
    public $showSuggestions = false;
    public $tracks = [];
    public $isLoading = false;

    protected $listeners = ['tagSelected' => 'addTag'];

    public function mount()
    {
        $this->loadInitialTracks();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->loadTagSuggestions();
            $this->showSuggestions = true;
        } else {
            $this->suggestedTags = [];
            $this->showSuggestions = false;
        }
    }

    public function loadTagSuggestions()
    {
        // Get unique tags from tracks table that match the search query
        $this->suggestedTags = Track::whereNotNull('tags')
            ->where('tags', '!=', '')
            ->get()
            ->pluck('tags')
            ->flatMap(function ($tagString) {
                return collect(explode(',', $tagString))->map(function ($tag) {
                    return trim($tag);
                });
            }) 
            ->unique()
            ->filter(function ($tag) {
                return stripos($tag, $this->search) !== false && !in_array($tag, $this->selectedTags);
            })
            ->take(10)
            ->values()
            ->toArray();
    }

    public function selectTag($tag)
    {
        if (!in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
            $this->search = '';
            $this->suggestedTags = [];
            $this->showSuggestions = false;
            $this->searchTracks();
        }
    }

    public function removeTag($index)
    {
        unset($this->selectedTags[$index]);
        $this->selectedTags = array_values($this->selectedTags);
        $this->searchTracks();
    }

    public function clearAllTags()
    {
        $this->selectedTags = [];
        $this->search = '';
        $this->loadInitialTracks();
    }

    public function searchTracks()
    {
        $this->isLoading = true;

        if (empty($this->selectedTags)) {
            $this->loadInitialTracks();
        } else {
            $query = Track::query();

            foreach ($this->selectedTags as $tag) {
                $query->where('tags', 'LIKE', '%' . $tag . '%');
            }

            $this->tracks = $query->orderBy('created_at', 'desc')->limit(50)->get()->toArray();
        }

        $this->isLoading = false;
    }

    public function loadInitialTracks()
    {
        $this->tracks = Track::orderBy('created_at', 'desc')->limit(20)->get()->toArray();
    }

    public function hideSuggestions()
    {
        // Delay hiding to allow click events on suggestions
        $this->dispatchBrowserEvent('hide-suggestions-delayed');
    }

    public function render()
    {
        return view('livewire.track-tags-search');
    }
}
?>


<div class="track-tags-search-container">
    <!-- Search Header -->
    <div class="search-header bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Search Tracks by Tags</h2>

        <!-- Search Input with Suggestions -->
        <div class="relative">
            <div
                class="flex flex-wrap items-center gap-2 p-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 bg-white">
                <!-- Selected Tags -->
                @foreach ($selectedTags as $index => $tag)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        {{ $tag }}
                        <button type="button" wire:click="removeTag({{ $index }})"
                            class="ml-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                @endforeach

                <!-- Search Input -->
                <input type="text" wire:model.debounce.300ms="search" wire:focus="$set('showSuggestions', true)"
                    wire:blur="hideSuggestions" placeholder="Type to search tags..."
                    class="flex-1 min-w-0 border-0 outline-none focus:ring-0 p-1" autocomplete="off">
            </div>

            <!-- Suggestions Dropdown -->
            @if ($showSuggestions && !empty($suggestedTags))
                <div
                    class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    @foreach ($suggestedTags as $tag)
                        <button type="button" wire:click="selectTag('{{ $tag }}')"
                            class="w-full text-left px-4 py-3 hover:bg-blue-50 focus:bg-blue-50 focus:outline-none border-b border-gray-100 last:border-b-0 transition-colors duration-150">
                            <span class="text-gray-800 font-medium">{{ $tag }}</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-4">
            <div class="text-sm text-gray-600">
                @if (!empty($selectedTags))
                    <span class="font-medium">{{ count($selectedTags) }}</span> tag(s) selected
                @endif
            </div>

            @if (!empty($selectedTags))
                <button wire:click="clearAllTags"
                    class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                    Clear All
                </button>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    @if ($isLoading)
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading tracks...</span>
        </div>
    @endif

    <!-- Results -->
    <div class="results-section">
        <!-- Results Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                Tracks
                @if (!empty($selectedTags))
                    <span class="text-blue-600">({{ count($tracks) }} found)</span>
                @endif
            </h3>
        </div>

        <!-- Tracks Grid -->
        @if (!empty($tracks))
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($tracks as $track)
                    <div
                        class="track-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                        <!-- Track Info -->
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-800 truncate">
                                {{ $track['title'] ?? 'Untitled Track' }}
                            </h4>
                            @if (isset($track['artist']))
                                <p class="text-sm text-gray-600 truncate">{{ $track['artist'] }}</p>
                            @endif
                        </div>

                        <!-- Track Tags -->
                        @if (isset($track['tags']) && !empty($track['tags']))
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach (array_slice(explode(',', $track['tags']), 0, 3) as $tag)
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-md">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                                @if (count(explode(',', $track['tags'])) > 3)
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium bg-gray-200 text-gray-600 rounded-md">
                                        +{{ count(explode(',', $track['tags'])) - 3 }} more
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Track Meta -->
                        <div class="text-xs text-gray-500">
                            @if (isset($track['duration']))
                                <span>{{ gmdate('i:s', $track['duration']) }}</span>
                            @endif
                            @if (isset($track['created_at']))
                                <span
                                    class="ml-2">{{ \Carbon\Carbon::parse($track['created_at'])->format('M j, Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19V6l12-3v13M9 19c0 1.105-1.895 2-4 2s-4-.895-4-2 1.895-2 4-2 4 .895 4 2zm12-3c0 1.105-1.895 2-4 2s-4-.895-4-2 1.895-2 4-2 4 .895 4 2zM9 10l12-3">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No tracks found</h3>
                <p class="mt-2 text-gray-500">
                    @if (!empty($selectedTags))
                        Try different tags or remove some filters.
                    @else
                        Start typing to search for tracks by tags.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Custom Styles -->
<style>
    .track-tags-search-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .track-card {
        transition: all 0.2s ease-in-out;
    }

    .track-card:hover {
        transform: translateY(-2px);
    }

    /* Scrollbar for suggestions */
    .suggestions-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    .suggestions-dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .suggestions-dropdown::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .suggestions-dropdown::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<!-- JavaScript for handling suggestions -->
<script>
    document.addEventListener('livewire:load', function() {
        window.addEventListener('hide-suggestions-delayed', function() {
            setTimeout(function() {
                @this.set('showSuggestions', false);
            }, 200);
        });
    });
    Livewire.on('hide-suggestions-delayed', () => {
        window.addEventListener('hide-suggestions-delayed', function() {
            setTimeout(function() {
                @this.set('showSuggestions', false);
            }, 200);
        });
    });
</script>
