<!DOCTYPE html>
<html lang="en" class="bg-gray-900 text-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Campaign Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">

    <div class="lg:w-3/5 mx-auto px-4 py-8">
        <!-- Track Info -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Artwork -->
            <div class="w-full md:w-1/3">
                <img class="rounded-xl w-full h-auto object-cover shadow-lg"
                    src="https://plus.unsplash.com/premium_photo-1679513691474-73102089c117?q=80&w=1413&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Track Artwork">
            </div>

            <!-- Metadata -->
            <div class="flex-1">
                <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">Track Title</h2>
                <p class="text-orange-500 mb-2">by @artist_username</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Genre: <span class="text-black dark:text-white">Hip-Hop</span> • BPM:
                    <span class="text-black dark:text-white">120</span> • Key:
                    <span class="text-black dark:text-white">C#m</span>
                </p>
                <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                    "This is a sample description of the track. You can promote this track using reposts with a target
                    budget and reach."
                </p>

                <!-- Audio Player Placeholder -->
                <div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg">
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Stream preview:</p>
                    <div class="w-full h-3 bg-gray-300 dark:bg-gray-700 rounded-full">
                        <div class="h-3 bg-orange-500 w-1/2 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Stats -->
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                <h4 class="text-gray-600 dark:text-gray-400 text-sm">Target Reposts</h4>
                <p class="text-xl font-bold text-black dark:text-white">1,000</p>
            </div>
            <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                <h4 class="text-gray-600 dark:text-gray-400 text-sm">Completed Reposts</h4>
                <p class="text-xl font-bold text-black dark:text-white">437</p>
            </div>
            <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playback Count</h4>
                <p class="text-xl font-bold text-black dark:text-white">23,412</p>
            </div>
            <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                <h4 class="text-gray-600 dark:text-gray-400 text-sm">Budget</h4>
                <p class="text-xl font-bold text-black dark:text-white">৳ 1,500.00</p>
            </div>
            <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Spent</h4>
                <p class="text-xl font-bold text-black dark:text-white">৳ 620.00</p>
            </div>
            <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                <h4 class="text-gray-600 dark:text-gray-400 text-sm">Cost / Repost</h4>
                <p class="text-xl font-bold text-black dark:text-white">৳ 1.50</p>
            </div>
        </div>

        <!-- Campaign Info -->
        <div class="mt-10 bg-gray-100 dark:bg-slate-800 p-6 rounded-xl shadow">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Campaign Settings</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                <p><span class="font-medium text-black dark:text-white">Status:</span> Active</p>
                <p><span class="font-medium text-black dark:text-white">Min Followers:</span> 100</p>
                <p><span class="font-medium text-black dark:text-white">Max Followers:</span> 5,000</p>
                <p><span class="font-medium text-black dark:text-white">Start Date:</span> 2025-07-01</p>
                <p><span class="font-medium text-black dark:text-white">End Date:</span> 2025-08-01</p>
                <p><span class="font-medium text-black dark:text-white">Featured:</span> No</p>
            </div>
        </div>

        <!-- Author Info -->
        <div
            class="mt-10 bg-gray-100 dark:bg-slate-800 p-6 rounded-xl shadow flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <img class="w-20 h-20 rounded-full object-cover"
                src='https://i1.sndcdn.com/avatars-9fsT8Xl7WRk03kYC-Jmhs2g-t200x200.jpg' alt="Author">
            <div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">@artist_username</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">SoundCloud ID: 123456789</p>
                <a href="#" class="text-orange-500 text-sm hover:underline">Visit Profile</a>
            </div>
        </div>

        <!-- Mini Player -->
        <div
            class="mt-10 w-full mx-auto bg-gray-100 dark:bg-gradient-to-r dark:from-zinc-700 dark:to-zinc-900 p-4 rounded-lg flex flex-wrap gap-4 items-start">
            <!-- Play Button -->
            <button
                class="w-12 h-12 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" class="w-6 h-6">
                    <path d="M5 3v18l15-9L5 3z" />
                </svg>
            </button>

            <!-- Info and Waveform -->
            <div class="flex-1 min-w-[250px]">
                <!-- Title + Meta -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-gray-900 dark:text-white text-base sm:text-lg font-semibold">Global Dance
                            Tracks: Fête</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Vibrations: Global Rhythm <span
                                class="text-blue-600 dark:text-blue-400">✔</span></p>
                    </div>
                    <div class="text-left sm:text-right space-y-1">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Updated 2 years ago</p>
                        <span
                            class="bg-gray-300 dark:bg-gray-600 text-black dark:text-white text-xs px-2 py-1 rounded-full">#
                            Global Dance</span>
                    </div>
                </div>

                <!-- Waveform -->
                <div class="mt-4 relative h-14 overflow-hidden">
                    <div class="absolute bottom-0 left-0 flex gap-[1px] h-full">
                        <div class="w-[2px] h-1/2 bg-orange-500"></div>
                        <div class="w-[2px] h-3/5 bg-orange-500"></div>
                        <div class="w-[2px] h-2/3 bg-orange-500"></div>
                        <div class="w-[2px] h-1/3 bg-orange-500"></div>
                        <div class="w-[2px] h-1/4 bg-orange-500"></div>
                        <div class="w-[2px] h-2/5 bg-gray-400 dark:bg-gray-500"></div>
                        <div class="w-[2px] h-3/5 bg-gray-400 dark:bg-gray-500"></div>
                        <div class="w-[2px] h-1/2 bg-gray-400 dark:bg-gray-500"></div>
                    </div>

                    <!-- Timestamps -->
                    <div class="absolute bottom-0 left-0 text-[10px] sm:text-xs text-gray-700 dark:text-white">1:02
                    </div>
                    <div class="absolute bottom-0 right-0 text-[10px] sm:text-xs text-gray-700 dark:text-white">2:37
                    </div>
                </div>
            </div>

            <!-- Album Image -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden">
                <img src="https://imgs.search.brave.com/RKar7_NHyhfuWhmjdzvCGNJSZZAh6PXDzaxKgzExyKo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAxLzkyLzMyLzgw/LzM2MF9GXzE5MjMy/ODAxNV9QTXRCMEFv/cE5WR2hNM2hPTTRs/MjRUc2M2UGUydFU5/OC5qcGc"
                    alt="Album" class="w-full h-full object-cover" />
            </div>
        </div>
    </div>


</body>

</html>
