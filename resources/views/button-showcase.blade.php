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
                        <x-gbutton variant="primary">Primary Button</x-gbutton>
                        <x-gbutton variant="secondary">Secondary Button</x-gbutton>
                        <x-gbutton variant="outline">Outline Button</x-gbutton>
                        <x-gbutton variant="text">Text Button</x-gbutton>
                    </div>
                </section>
                <!-- Button Sizes -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Button Sizes</h2>
                    <div class="flex flex-wrap items-center gap-4">
                        <x-gbutton size="sm">Small Button</x-gbutton>
                        <x-gbutton size="md">Medium Button</x-gbutton>
                        <x-gbutton size="lg">Large Button</x-gbutton>
                    </div>
                </section>

                <!-- With Icons -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">With Icons</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-gbutton>
                            <span class="flex items-center gap-2">
                                Continue
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </x-gbutton>
                        <x-gbutton variant="secondary">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Download
                            </span>
                        </x-gbutton>
                        <x-gbutton variant="outline">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Completed
                            </span>
                        </x-gbutton>
                    </div>
                </section>

                <!-- Full Width -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Full Width</h2>
                    <div class="space-y-4 max-w-md">
                        <x-gbutton :full-width="true">Full Width Button</x-gbutton>
                        <x-gbutton variant="secondary" :full-width="true">Full Width Secondary</x-gbutton>
                    </div>
                </section>

                <!-- States -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">States</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-gbutton :disabled="true">Disabled Button</x-gbutton>
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <x-gbutton>Button with Focus Ring</x-gbutton>
                        </div>
                    </div>
                </section>

                <!-- Interactive Examples -->
                <section>
                    <h2 class="text-2xl font-bold mb-4">Interactive Examples</h2>
                    <div class="flex flex-wrap gap-4">
                        <x-gbutton onclick="alert('Primary button clicked!')">Click Me</x-gbutton>
                        <x-gbutton variant="secondary" onclick="console.log('Secondary button clicked')">Log to
                            Console</x-gbutton>
                        <x-gbutton variant="outline" type="submit">Submit Form</x-gbutton>
                    </div>
                </section>
            </div>
            <h2 class="text-2xl font-bold my-4">Button Used Here</h2>
            <div class="flex gap-2.5">
                <x-gbutton variant="primary">Primary</x-gbutton>
                <x-gbutton variant="secondary">Secondary</x-gbutton>
                <x-gbutton variant="outline">Outline</x-gbutton>
                <x-gbutton variant="text">Text</x-gbutton>
            </div>
        </div>
    </main>
</body>

</html>
