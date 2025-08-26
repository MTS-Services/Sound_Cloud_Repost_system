<x-user::layout>
    <x-slot name="page_slug"></x-slot>

    <div class=" text-gray-800">

        <div class="container mx-auto px-6 py-10">

            <!-- Header -->
            <header class="mb-10">
                <h1 class="text-2xl font-bold dark:text-white text-gray-800">RepostChain Blog</h1>
                <p class="dark:text-gray-400 text-gray-800 mt-1">Stay updated with the latest music marketing trends,
                    tips, and industry
                    insights</p>
            </header>

            <!-- Featured Post -->
            <section class="dark:bg-gray-800 p-6 rounded-sm shadow mb-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-medium">Featured</span>
                    <span class="text-xs text-gray-500">Marketing</span>
                </div>
                <h2 class="text-xl font-semibold dark:text-white text-gray-800">The Ultimate Guide to Music Marketing
                    in 2024</h2>
                <p class="dark:text-gray-400 text-gray-800 mt-2">Discover the latest strategies and tactics that
                    successful artists are
                    using to grow their fanbase and increase their streaming numbers. From social media campaigns to
                    playlist placements, we cover everything you need to know.</p>

                <div class="flex items-center justify-between mt-4 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8  rounded-full bg-gray-200"></span>
                        <span>Sarah Johnson • Marketing Director</span>
                    </div>
                    <div>
                        <span>Dec 15, 2024</span>
                        <span class="mx-2">•</span>
                        <span>8 min read</span>
                        <a href="#" class="ml-2 text-orange-500 hover:underline">Read More</a>
                    </div>
                </div>
            </section>

            <!-- Categories -->
            <div class="flex flex-wrap gap-3 mb-8">
                <button class="bg-orange-500 text-white px-4 py-2 rounded-sm text-sm">All Posts</button>
                <button class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-sm text-sm">Marketing</button>
                <button class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-sm text-sm">Industry News</button>
                <button class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-sm text-sm">Tips & Tricks</button>
                <button class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-sm text-sm">Case Studies</button>
            </div>

            <!-- Blog Grid -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="dark:bg-gray-800 rounded-sm shadow overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-orange-500 to-orange-600"></div>
                    <div class="p-8">
                        <div class="text-xs dark:bg-gray-800 dark:text-gray-300 mb-1">Tips • Dec 12, 2024</div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">5 Ways to Increase Your
                            Spotify Streams</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 mb-3 ">Learn proven strategies to boost your
                            streaming numbers
                            and reach new audiences on Spotify.</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Mike Chen</span>
                            <span>5 min read</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="dark:bg-gray-800 rounded-sm shadow overflow-hidden">
                    <div class="h-32 bg-gradient-to-r to-blue-800 from-blue-500"></div>
                    <div class="p-8">
                        <div class="text-xs dark:bg-gray-800 dark:text-gray-300 mb-1">Case Study • Dec 10, 2024</div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">How Artist X Gained 100K
                            Followers in 3 Months</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 mb-3">A detailed breakdown of the campaign
                            strategy that led to
                            explosive growth for an emerging artist.</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Emma Davis</span>
                            <span>12 min read</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="dark:bg-gray-800 rounded-sm shadow overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-purple-500 to-purple-600"></div>
                    <div class="p-8">
                        <div class="text-xs dark:bg-gray-800 dark:text-gray-300 mb-1">Industry • Dec 8, 2024</div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">The Future of Music
                            Distribution</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 mb-3">Exploring emerging trends and
                            technologies that are
                            reshaping how music reaches audiences.</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Alex Rodriguez</span>
                            <span>7 min read</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="dark:bg-gray-800 rounded-sm shadow overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-green-500 to-green-600"></div>
                    <div class="p-8">
                        <div class="text-xs dark:bg-gray-800 dark:text-gray-300 mb-1">Marketing • Dec 5, 2024</div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Building Your Brand as
                            an Independent Artist</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 mb-3">Essential steps to create a strong,
                            recognizable brand
                            that resonates with your target audience.</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Lisa Park</span>
                            <span>9 min read</span>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="dark:bg-gray-800 rounded-sm shadow overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-red-500 to-red-600"></div>
                    <div class="p-8">
                        <div class="text-xs dark:bg-gray-800 dark:text-gray-300 mb-1">Analytics • Dec 3, 2024</div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Understanding Your Music
                            Analytics</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 mb-3">Learn how to interpret streaming data
                            and use insights to
                            improve your marketing strategy.</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>David Kim</span>
                            <span>6 min read</span>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="dark:bg-gray-800 rounded-sm shadow overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-orange-500 to-orange-600"></div>
                    <div class="p-8">
                        <div class="text-xs dark:bg-gray-800 dark:text-gray-300 mb-1">Tools • Dec 1, 2024</div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">Top 10 Music Marketing
                            Tools for 2024
                        </h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300 mb-3">A comprehensive review of the best
                            tools and platforms to
                            supercharge your music marketing efforts.</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Rachel Green</span>
                            <span>11 min read</span>
                        </div>
                    </div>
                </div>

            </section>

            <!-- Load More -->
            <div class="flex justify-center mt-10">
                <button class="px-6 py-2 bg-orange-500 text-white rounded-sm hover:bg-orange-600">Load More
                    Articles</button>
            </div>
        </div>

    </div>

</x-user::layout>
