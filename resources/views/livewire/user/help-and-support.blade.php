<div>
    <x-slot name="page_slug">help-support</x-slot>

    <!----billing---->
    @if ($billing)
        <div class="mt-4 max-w-8xl mx-auto bg-gray-100 p-4 rounded shadow">
            <H1 class="text-gray-700 text-xl font-bold">Billing info shown here.</H1>
            <div class="mt-4 ">
              <form wire:submit.prevent="billings">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input type="text" id="name" wire:model="name" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" id="email" wire:model="email" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-gray-700 font-bold mb-2">Massage:</label>
                    <textarea type="message" id="message" wire:model="message" class="w-full p-2 border rounded"></textarea>
                </div>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded">Update Billing Info</button>
            </form>
            </div>
        </div>
    @endif

    <!---privacy---->
    @if ($privacys)
       <h1>Privacy</h1>
    @endif

   @if ($test)
        <section class="bg-orange-500 text-white py-16 sm:py-24">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">How can we help you?</h1>
            <p class="text-lg mb-8 text-gray-300">Find answers to common questions and get support for your account.</p>

        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10 dark:text-white">Popular Topics</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="javascript:void(0);" wire:click="billings"
                    class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                    <div class="text-5xl mb-4">&#x1F4B3;</div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">Account & Billing</h3>
                    <p class="text-gray-600 dark:text-white">Manage your subscription, payments, and invoices.</p>
                </a>
                <a href="#"
                    class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                    <div class="text-5xl mb-4">&#x1F50A;</div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">Uploading & Playback</h3>
                    <p class="text-gray-600 dark:text-white">Solutions for uploading music and playback issues.</p>
                </a>
                <a href="javascript:void(0);" wire:click="privacy"
                    class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                    <div class="text-5xl mb-4">&#x1F4E6;</div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">Privacy & Safety</h3>
                    <p class="text-gray-600 dark:text-white">Information about your data and safety online.</p>
                </a>
                <a href="#"
                    class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                    <div class="text-5xl mb-4">&#x1F4AC;</div>
                    <h3 class="text-xl font-semibold dark:text-white mb-2">Community Guidelines</h3>
                    <p class="text-gray-600 dark:text-white">Learn about our community rules and policies.</p>
                </a>
                <a href="#"
                    class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                    <div class="text-5xl mb-4">&#x1F4BB;</div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">Technical Support</h3>
                    <p class="text-gray-600 dark:text-white">Troubleshooting for app and website issues.</p>
                </a>
                <a href="#"
                    class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                    <div class="text-5xl mb-4">&#x1F3A4;</div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">Creator Tools</h3>
                    <p class="text-gray-600 dark:text-white">Guides for artists and creators using our platform.</p>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-gray-100 dark:bg-gray-800 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4 dark:text-white">Still can't find what you're looking for?</h2>
            <p class="text-lg text-gray-600 dark:text-white mb-8 max-w-2xl mx-auto">
                Our support team is always here to help. Get in touch with us and we'll get back to you as soon as
                possible.
            </p>
            <a href="#"
                class="inline-block bg-blue-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-blue-700 transition-colors duration-300">
                Contact Support
            </a>
        </div>
    </section>
   @endif


</div>
