<x-admin::layout>
    <x-slot name="title">{{ __('Detail User') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Detail User') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mt-8">
            <div class="bg-gradient-to-r from-soundcloud dark:bg-gray-800 p-6 text-white">
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="relative">
                        <img src="/placeholder.svg?height=80&width=80"
                             alt="User Avatar"
                             class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                        <div class="absolute -bottom-1 -right-1 bg-green-500 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        </div>
                    </div>

                    {{-- @dd($user->userInfo?->avatar) --}}
                    <div class="flex-1 text-center sm:text-left">
                        <h1 class="text-3xl font-bold text-black dark:text-white mt-2 sm:mt-0">{{ $user->name }}</h1>
                        <p class="text-lg text-black dark:text-white">@johndoe</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start mt-2 space-y-2 sm:space-y-0 sm:space-x-3">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">Active</span>
                            <span class="text-orange-500">Pro Member</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-white">150</div>
                        <div class="text-sm text-gray-600 dark:text-white">Tracks</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-white">10K</div>
                        <div class="text-sm text-gray-600 dark:text-white">Followers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-white">8K</div>
                        <div class="text-sm text-gray-600 dark:text-white">Likes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-white">25</div>
                        <div class="text-sm text-gray-600 dark:text-white">Playlists</div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 pb-2">Personal Info</h2>

                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">User Name</span>
                                <span class="text-gray-800 dark:text-white">Johnny</span>
                            </div>
                             <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">User Nickname</span>
                                <span class="text-gray-800 dark:text-white">Jony</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">First Name</span>
                                <span class="text-gray-800 dark:text-white">khan</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Last Name</span>
                                <span class="text-gray-800 dark:text-white">Shaheb</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Full Name</span>
                                <span class="text-gray-800 dark:text-white">John Doe</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Country</span>
                                <span class="text-gray-800 dark:text-white"> USA</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">City</span>
                                <span class="text-gray-800 dark:text-white"> New York</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">avatar</span>
                                <span class="text-gray-800 dark:text-white"> Null</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Email </span>
                                <span class="text-black dark:text-white  rounded-full text-right sm:text-left">jhonnyshaheb@gmail.com</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Email Verified</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Yes</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">MySpace Name</span>
                                <span class="text-gray-800 dark:text-white">JohnDoeMusic</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Discogs Name</span>
                                <span class="text-gray-800 dark:text-white">JohnDoeDiscogs</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Website</span>
                                <a href="https://johndoe.com" class="text-blue-500 underline text-right sm:text-left break-all">https://johndoe.com</a>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Website Title</span>
                                <span class="text-gray-800 dark:text-white">My Official Website</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 mt-8 md:mt-0">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 pb-2">Platform Info</h2>
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">SoundCloud ID</span>
                                <span class="text-gray-800 dark:text-white font-mono text-sm">123456789</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">SoundCloud URN</span>
                                <span class="text-gray-800 dark:text-white text-right sm:text-left">soundcloud:users:123456789</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">SoundCloud Kind</span>
                                <span class="text-gray-800 dark:text-white">user</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Permalink URL</span>
                                <a href="https://soundcloud.com/johndoe" class="text-blue-500 underline text-right sm:text-left break-all">soundcloud.com/johndoe</a>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Permalink</span>
                                <span class="text-gray-800 dark:text-white">johndoe</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Expires In </span>
                                <span class="text-gray-800 dark:text-white"> gtr</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">SoundCloud URI</span>
                                <span class="text-gray-800 dark:text-white text-right sm:text-left break-all">https://api.soundcloud.com/users/123456789</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">SoundCloud Created</span>
                                <span class="text-gray-800 dark:text-white">2015-01-01</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Last Modified</span>
                                <span class="text-gray-800 dark:text-white">2023-10-25</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 dark:text-white font-medium">Last Synced</span>
                                <span class="text-gray-800 dark:text-white">Oct 26, 2023</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Private Playlists</span>
                                <span class="text-gray-800 dark:text-white">5</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Private Tracks</span>
                                <span class="text-gray-800 dark:text-white">10</span>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Plan</span>
                                <span class="text-gray-800 dark:text-white">Pro</span>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 dark:text-white">Upload Seconds Left</span>
                                <span class="text-gray-800 dark:text-white">3600</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 pb-2 mb-4">Activity Overview</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 dark:bg-gray-800 p-4 rounded-lg text-center">
                            <div class="text-black dark:text-white font-semibold">Reposts</div>
                            <div class="text-2xl font-bold text-black dark:text-white">200</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-gray-800 p-4 rounded-lg text-center">
                            <div class="text-black dark:text-white font-semibold">Following</div>
                            <div class="text-2xl font-bold text-black dark:text-white">500</div>
                        </div>
                        <div class="bg-green-50 dark:bg-gray-800 p-4 rounded-lg text-center">
                            <div class="text-black dark:text-white font-semibold">Comments</div>
                            <div class="text-2xl font-bold text-black dark:text-white">1,200</div>
                        </div>
                        <div class="bg-orange-50 dark:bg-gray-800 p-4 rounded-lg text-center">
                            <div class="text-black dark:text-white font-semibold">Favorites</div>
                            <div class="text-2xl font-bold text-black dark:text-white">500</div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 pb-2 mb-4">Description</h2>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-gray-700 dark:text-white leading-relaxed">
                            I am a passionate music producer and DJ, always looking for new sounds and collaborations.
                            My journey in music started years ago, and I've been honing my craft ever since. I love
                            experimenting with different genres and creating unique sonic experiences for my listeners.
                            When I'm not in the studio, you can find me exploring new places or enjoying a good book.
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row flex-wrap gap-3">
                    <a href="https://johndoe.com"
                       class="bg-soundcloud text-white px-6 py-2 rounded-lg bg-orange-600 hover:bg-orange-500 transition-colors font-medium text-center">
                        Visit Website
                    </a>
                    <a href="https://soundcloud.com/johndoe"
                       class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors font-medium text-center">
                        SoundCloud Profile
                    </a>
                    <button class="border border-gray-300 text-gray-700 dark:text-white px-6 py-2 rounded-lg hover:bg-gray-50 transition-colors font-medium text-center">
                        Follow
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin::layout>