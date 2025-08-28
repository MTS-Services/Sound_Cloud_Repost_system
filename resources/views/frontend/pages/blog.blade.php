<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog – Coming Soon</title>
    <!-- Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <!-- Heading -->
        <h1
            class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 mb-6">
            🚀 Blog Coming Soon
        </h1>

        <!-- Description -->
        <p class="text-lg text-gray-300 max-w-xl mx-auto mb-10">
            We’re crafting something exciting for you!
            Soon, you’ll have access to fresh articles, insights, and updates tailored to your journey.
            Stay tuned while we put the finishing touches in place.
        </p>

        <!-- Loader -->
        <div class="flex justify-center items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-7 w-7 text-purple-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z">
                </path>
            </svg>
            <span class="text-gray-400 text-lg">Preparing your experience...</span>
        </div>

        <!-- Optional CTA -->
        <div class="mt-10">
            <a href="{{ route('user.dashboard') }}"
                class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-pink-500 text-white rounded-full shadow-lg hover:opacity-90 transition">
                Back to Home
            </a>
        </div>
    </div>
</body>

</html>
