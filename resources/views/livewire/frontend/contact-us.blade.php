@push('cs')
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .success-message {
            display: none;
        }

        .success-message.show {
            display: block;
        }

        .contact-form.hide {
            display: none;
        }
    </style>
@endpush
<div class="bg-black">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-black/95 backdrop-blur-md border-b border-zinc-800/50 z-50">
        <div class="mx-auto px-6 py-4">
            <div class="flex items-center justify-center relative">
                <a wire:navigate href="{{ route('f.landing') }}"
                    class="absolute left-0 flex items-center gap-2 text-zinc-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="group-hover:stroke-white transition-colors">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    <span>Back</span>
                </a>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo/logo-1.png') }}" alt="Repostchain" class="w-10 h-10">
                    <span class="text-2xl font-bold text-white">RepostChain</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 pt-32 pb-20 max-w-6xl">

        <!-- Header -->
        <div class="text-center mb-16">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-orange-500/10 to-orange-400/10 border border-orange-500/20 mb-6">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium text-orange-500">Get in Touch</span>
            </div>

            <h1 class="text-6xl font-bold text-white mb-4">
                Contact Us
            </h1>
            <p class="text-xl text-zinc-400 max-w-2xl mx-auto">
                Have questions or need support? We're here to help you succeed on your musical journey.
            </p>
        </div>

        <!-- Content Grid -->
        <div class="grid lg:grid-cols-3 gap-8 mb-12">

            <!-- Contact Form Section -->
            <div class="lg:col-span-2">
                <div class="rounded-2xl bg-zinc-900/50 border border-zinc-800/50 p-8 backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Send us a message</h2>
                    </div>

                    <!-- Success Message (Hidden by default) -->
                    <div id="successMessage" class="success-message py-12 text-center">
                        <div
                            class="w-20 h-20 rounded-full bg-green-500/10 border-2 border-green-500 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">Message Sent!</h3>
                        <p class="text-zinc-400">We'll get back to you within 24-48 hours.</p>
                    </div>

                    <!-- Contact Form -->
                    <form id="contactForm" class="contact-form space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-zinc-300 mb-2">
                                    Your Name
                                </label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-3 rounded-xl bg-black/50 border border-zinc-800 text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors"
                                    placeholder="John Doe" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-300 mb-2">
                                    Email Address
                                </label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 rounded-xl bg-black/50 border border-zinc-800 text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors"
                                    placeholder="john@example.com" />
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-zinc-300 mb-2">
                                Subject
                            </label>
                            <select id="subject" name="subject" required
                                class="w-full px-4 py-3 rounded-xl bg-black/50 border border-zinc-800 text-white focus:outline-none focus:border-orange-500 transition-colors">
                                <option value="">Select a topic</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="billing">Billing & Payments</option>
                                <option value="account">Account Issues</option>
                                <option value="partnership">Partnership Opportunities</option>
                                <option value="feedback">Feedback & Suggestions</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-zinc-300 mb-2">
                                Message
                            </label>
                            <textarea id="message" name="message" required rows="6"
                                class="w-full px-4 py-3 rounded-xl bg-black/50 border border-zinc-800 text-white placeholder-zinc-500 focus:outline-none focus:border-orange-500 transition-colors resize-none"
                                placeholder="Tell us how we can help you..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full px-8 py-4 rounded-xl bg-gradient-to-r from-orange-500 to-orange-400 text-white font-semibold hover:shadow-lg hover:shadow-orange-500/20 transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info Cards -->
            <div class="space-y-6">

                <!-- Email Card -->
                <div
                    class="rounded-2xl bg-gradient-to-br from-zinc-900/50 to-zinc-800/30 border border-zinc-800/50 p-6 backdrop-blur-sm">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Email Us</h3>
                    <p class="text-zinc-400 text-sm mb-3">
                        Get in touch with our support team
                    </p>
                    <a href="mailto:support@repostchain.com"
                        class="text-orange-500 hover:text-orange-400 transition-colors text-sm font-medium">
                        support@repostchain.com
                    </a>
                </div>

                <!-- Response Time Card -->
                <div
                    class="rounded-2xl bg-gradient-to-br from-zinc-900/50 to-zinc-800/30 border border-zinc-800/50 p-6 backdrop-blur-sm">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Response Time</h3>
                    <p class="text-zinc-400 text-sm">
                        We typically respond within 24-48 hours on business days
                    </p>
                </div>

                <!-- Worldwide Support Card -->
                <div
                    class="rounded-2xl bg-gradient-to-br from-zinc-900/50 to-zinc-800/30 border border-zinc-800/50 p-6 backdrop-blur-sm">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-400 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2v20M2 12h20" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Worldwide Support</h3>
                    <p class="text-zinc-400 text-sm">
                        We support artists from all around the globe
                    </p>
                </div>

                <!-- Company Info Card -->
                <div
                    class="rounded-2xl bg-gradient-to-br from-orange-500/10 to-orange-400/5 border border-orange-500/20 p-6">
                    <h3 class="text-lg font-semibold text-white mb-3">Company Info</h3>
                    <div class="space-y-2 text-sm">
                        <p class="text-zinc-400">
                            <span class="text-zinc-500">Company:</span> Tunexa Limited
                        </p>
                        <p class="text-zinc-400">
                            <span class="text-zinc-500">Website:</span>
                            <a href="https://repostchain.com" class="text-orange-500 hover:text-orange-400 ml-1">
                                repostchain.com
                            </a>
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- FAQ Section -->
        <div
            class="rounded-2xl bg-gradient-to-r from-orange-500/5 via-orange-400/5 to-orange-500/5 border border-orange-500/10 p-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-white mb-3">Before you reach out...</h3>
                <p class="text-zinc-400 mb-4">
                    Check our FAQ section for quick answers to common questions
                </p>
                <a href="#faq"
                    class="inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-zinc-900 text-white hover:bg-zinc-800 transition-colors">
                    View FAQ
                </a>
            </div>
        </div>

    </div>
</div>
