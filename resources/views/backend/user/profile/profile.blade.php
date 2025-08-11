<x-user::layout>

    <x-slot name="page_slug">profile</x-slot>
    <div id="content-profile" class="page-content">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
                <p class="text-gray-600">Manage your account and view your activity</p>
            </div>
            <a href="#"
                class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span class="text-gray-700">Edit Profile</span>
            </a>
        </div>
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                    <div class="relative">
                        <img src="{{ auth_storage_url(user()->avatar) }}" alt="{{ user()->name }}"
                            class="w-20 h-20 rounded-full">
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white">
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ user()->name }}</h2>
                        {{-- <p class="text-gray-600">Email Not Provided from SoundCloud Profile... </p> --}}
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                {{ __('Joined') }} {{ user()->created_at->diffForHumans() }}
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                                {{ $user->followers_count ?? 0 }} {{ __('Followers') }}
                            </div>
                            <a href="{{ $user->soundcloud_permalink_url ?? '#' }}" target="_blank"
                                class="text-orange-600 hover:underline">SoundCloud Profile</a>
                        </div>
                        <div class="flex space-x-2 mt-4">
                            <span class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs">Top
                                Reposter</span>
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">Quality
                                Artist</span>
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Credibility Score -->
                <div class="text-center">
                    <div class="relative w-24 h-24 mx-auto">
                        <svg class="progress-circle w-24 h-24" viewBox="0 0 36 36">
                            <path class="text-gray-200" stroke="currentColor" stroke-width="2" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                            </path>
                            <path class="text-orange-600" stroke="currentColor" stroke-width="2" fill="none"
                                stroke-dasharray="87, 100" stroke-dashoffset="0"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                            </path>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-bold text-orange-600">87%</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="font-semibold text-gray-900">Credibility Score</div>
                        <a href="#" class="text-orange-600 text-sm hover:underline">Show details ></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="shadow-sm">
                <nav class="flex space-x-8 px-6">
                    <a href="#" class="tab-btn py-4 px-1 border-b-2 border-orange-600 text-orange-600 font-medium"
                        data-tab="overview">Overview</a>
                    <a href="#"
                        class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                        data-tab="transactions">Credit Transactions</a>
                    <a href="#"
                        class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                        data-tab="history">Repost History</a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div id="overview" class="tab-pane p-6 block">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-orange-50 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-orange-100 p-3 rounded-lg mr-4">
                                <x-lucide-music class="w-6 h-6 text-orange-600"></x-lucide-music>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">0</div>
                                <div class="text-gray-600">Track Submissions</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 shadow-sm rounded-lg mr-4">
                                <x-lucide-refresh-cw class="w-6 h-6 text-blue-600"></x-lucide-refresh-cw>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $gevened_repostRequests }}</div>
                                <div class="text-gray-600">Reposts Given</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-green-50 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <x-lucide-users class="w-6 h-6 text-green-600"></x-lucide-users>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $received_repostRequests }}</div>
                                <div class="text-gray-600">Reposts Received</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-yellow-50 p-6 shadow-sm rounded-lg hover:-translate-y-2 transition-all duration-500 ease-in-out">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                                <x-lucide-trending-up class="w-6 h-6 text-yellow-600"></x-lucide-trending-up>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $total_erned_credits }}</div>
                                <div class="text-gray-600">Total Credits Earned</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Genres -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Genres</h3>
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-orange-100 text-orange-800 px-4 py-2 rounded-full">Electronic</span>
                        <span class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">Hip-hop</span>
                        <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full">Indie</span>
                        <span class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full">Classical</span>
                        <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full">Rock</span>
                    </div>
                </div>

                <!-- Achievements -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Achievements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow dark:bg-gray-800">
                            <div class="flex items-center mb-3">
                                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="star" class="w-5 h-5 text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Rising Star</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Completed
                                        {{ $completed_reposts }} reposts</div>
                                </div>
                            </div>
                        </div>

                        <div class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow dark:bg-gray-800">
                            <div class="flex items-center mb-3">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="refresh-cw" class="w-5 h-5 text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Repost Champion</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Reposted
                                        {{ $reposted_genres }} different genres</div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow opacity-100 dark:bg-gray-800">
                            <div class="flex items-center mb-3">
                                <div class="bg-gray-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="music" class="w-5 h-5 text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">Track Master</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Submit 5 tracks (2/5)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="transactions" class="tab-pane p-6 hidden">
                <!-- Transaction Tab -->
                <div class="w-full overflow-x-auto">
                    <table
                        class="min-w-[900px] w-full table-fixed text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white text-xs sm:text-sm">
                            <tr>
                                <th class="w-10 px-2 py-3">ID</th>
                                <th class="w-28 px-2 py-3">Sender Name</th>
                                <th class="w-20 px-2 py-3">Amount</th>
                                <th class="w-20 px-2 py-3">Credit</th>
                                <th class="w-24 px-2 py-3">Type</th>
                                <th class="w-20 px-2 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @foreach ($credit_transactions as $transaction)
                                <tr class="odd:bg-gray-800 even:bg-gray-900">
                                    <td class="px-4 py-3 font-semibold">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $transaction->sender->name ?? 'System' }}</td>
                                    <td class="px-4 py-3">{{ $transaction->amount }}</td>
                                    <td class="px-4 py-3">{{ $transaction->credits }}</td>
                                    <td class="px-4 py-3">{{ $transaction->type_name }}</td>
                                    <td class="px-4 py-3">{{ $transaction->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($credit_transactions->isEmpty())
                    <div class="text-center py-12">
                        <i data-lucide="credit-card"
                            class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Credit Transactions
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">Not found</p>
                    </div>
                @endif
            </div>
            <div id="history" class="tab-pane p-6 hidden">
                @forelse ($repost_requests as $repostRequest)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
                        <div class="flex flex-col lg:flex-row" wire:key="request-{{ $repostRequest->id }}">
                            <!-- Left Column - Track Info -->
                            <div
                                class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <!-- Track Details -->
                                    <div class="flex-1 flex flex-col justify-between relative">
                                        <!-- SoundCloud Player with Audio Events -->
                                        <div id="soundcloud-player-{{ $repostRequest->id }}"
                                            data-request-id="{{ $repostRequest->id }}" wire:ignore>
                                            <x-sound-cloud.sound-cloud-player :track="$repostRequest->track" :visual="false" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Request Info -->
                            <div class="w-full lg:w-1/2 p-3">
                                <div class="flex justify-between mb-2">
                                    <div class="w-1/2 relative">
                                        <div class="flex flex-col items-start gap-0">
                                            <div class="flex items-center gap-2">
                                                <img class="w-12 h-12 rounded-full object-cover"
                                                    src="{{ auth_storage_url($repostRequest->targetUser->avatar) }}"
                                                    alt="{{ $repostRequest->targetUser->name }} avatar">
                                                <div x-data="{ open: false }" class="inline-block text-left">
                                                    <div @click="open = !open" @click.outside="open = false"
                                                        class="flex items-center gap-1 cursor-pointer">
                                                        <span
                                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $repostRequest->targetUser->name }}</span>
                                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>

                                                    <!-- Rating Stars -->
                                                    <div class="flex items-center mt-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= ($repostRequest->targetUser->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                            </svg>
                                                        @endfor
                                                    </div>

                                                    <!-- Dropdown Menu -->
                                                    <div x-show="open" x-transition.opacity
                                                        class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                                        x-cloak>
                                                        <a href="{{ $repostRequest->targetUser->soundcloud_url ?? '#' }}"
                                                            target="_blank"
                                                            class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                            SoundCloud Profile</a>
                                                        <a href="{{ route('user.profile', $repostRequest->targetUser->username ?? $repostRequest->targetUser->id) }}"
                                                            wire:navigate
                                                            class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                            RepostChain Profile</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Track Info -->
                                            <div class="mb-2">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                                    {{ $repostRequest->track->title ?? 'Unknown Track' }}
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($repostRequest->track->description ?? 'No description available', 100) }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                                    {{ $repostRequest->track->genre ?? 'Unknown Genre' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-1/2">
                                        <div class="flex flex-col items-end gap-2 h-full">
                                            <div class="flex flex-col justify-between h-full">
                                                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                                    {{ strtoupper($repostRequest->created_at->diffForHumans()) }}
                                                </div>
                                                <!-- Status Badge -->
                                                <div class="text-right">
                                                    <span @class([
                                                        'inline-block text-xs font-medium px-2 py-1 rounded-full',
                                                        'bg-yellow-100 text-yellow-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                                        'bg-green-100 text-green-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                                        'bg-blue-100 text-blue-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                                        // 'bg-red-100 text-red-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_REJECTED,
                                                        'bg-gray-100 text-gray-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_EXPIRED,
                                                    ])>
                                                        {{ $repostRequest->status_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <x-lucide-history class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" />
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Repost History</h3>
                        <p class="text-gray-600 dark:text-gray-400">Not found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @push('js')
        <script>
            // Tab functionality (only for profile page)
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabId = btn.getAttribute('data-tab');

                    // Update tab buttons
                    tabBtns.forEach(b => {
                        b.classList.remove('border-orange-600', 'text-orange-600');
                        b.classList.add('border-transparent', 'text-gray-500');
                    });
                    btn.classList.remove('border-transparent', 'text-gray-500');
                    btn.classList.add('border-orange-600', 'text-orange-600');

                    // Update tab content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });

            // // Animate progress circle on load
            // window.addEventListener('load', () => {
            //     const progressCircle = document.querySelector('.progress-circle path:last-child');
            //     if (progressCircle) {
            //         progressCircle.style.strokeDashoffset = '13';
            //         setTimeout(() => {
            //             progressCircle.style.strokeDashoffset = '0';
            //         }, 500);
            //     }
            // });
        </script>
    @endpush
</x-user::layout>
