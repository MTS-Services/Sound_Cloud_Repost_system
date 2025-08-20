<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orange Button Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Orange Button Collection
            </h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-600 mb-8">
                A collection of customizable orange buttons with rounded corners for your Laravel applications.
            </p>

            <div class="space-y-12">
                <!-- Button Variants -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Button Variants</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-button variant="primary">Primary Button</x-button>
                        <x-button variant="secondary">Secondary Button</x-button>
                        <x-button variant="outline">Outline Button</x-button>
                        <x-button variant="text">Text Button</x-button>
                    </div>
                </section>
                <!-- Button Sizes -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Button Sizes</h2>
                    <div class="flex flex-wrap items-center gap-4">
                        <x-button size="sm">Small Button</x-button>
                        <x-button size="md">Medium Button</x-button>
                        <x-button size="lg">Large Button</x-button>
                    </div>
                </section>

                <!-- With Icons -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">With Icons</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-button>
                            <span class="flex items-center gap-2">
                                Continue
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </x-button>
                        <x-button variant="secondary">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Download
                            </span>
                        </x-button>
                        <x-button variant="outline">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Completed
                            </span>
                        </x-button>
                    </div>
                </section>

                <!-- Full Width -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Full Width</h2>
                    <div class="space-y-4 max-w-md">
                        <x-button :full-width="true">Full Width Button</x-button>
                        <x-button variant="secondary" :full-width="true">Full Width Secondary</x-button>
                    </div>
                </section>

                <!-- States -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">States</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-button :disabled="true">Disabled Button</x-button>
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <x-button>Button with Focus Ring</x-button>
                        </div>
                    </div>
                </section>

                <!-- Interactive Examples -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Interactive Examples</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-button onclick="alert('Primary button clicked!')">Click Me</x-button>
                        <x-button variant="secondary" onclick="console.log('Secondary button clicked')">Log to
                            Console</x-button>
                        <x-button variant="outline" type="submit">Submit Form</x-button>
                    </div>
                </section>
            </div>
            <h2 class="text-2xl font-bold my-4">Button Used Here</h2>
            <div class="flex gap-2.5">
                <x-button variant="primary">Primary</x-button>
                <x-button variant="secondary">Secondary</x-button>
                <x-button variant="outline">Outline</x-button>
                <x-button variant="text">Text</x-button>
            </div>
        </div>
    </main>
</body>

</html>
