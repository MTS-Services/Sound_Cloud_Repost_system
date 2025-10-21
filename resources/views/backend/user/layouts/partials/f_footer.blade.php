<footer class="border-t border-zinc-800/50 py-16 px-8 bg-black">
    <div class="max-w-[1400px] mx-auto">
        <div class="grid md:grid-cols-4 gap-12 mb-12">
            <div>
                <div class="flex items-center mb-4">
                    @if (app_setting('app_logo') && app_setting('app_logo_dark'))
                        {{-- <img src="{{ storage_url(app_setting('app_logo')) }}" alt="{{ config('app.name') }}"
                        class="w-36 lg:w-48 object-contain dark:hidden" /> --}}
                        <img src="{{ storage_url(app_setting('app_logo_dark')) }}" alt="{{ config('app.name') }}"
                            class="w-36 lg:w-48 object-contain" />
                    @else
                        {{-- <img src="{{ asset('assets/logos/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                        class="w-36 lg:w-48 object-contain dark:hidden" /> --}}
                        <img src="{{ asset('assets/logo/rc-logo-white.png') }}" alt="{{ config('app.name') }}"
                            class="w-36 lg:w-48 object-contain" />
                    @endif
                </div>
                <p class="text-zinc-400 text-sm mb-6 leading-relaxed">The collaborative music promotion platform for
                    SoundCloud artists.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-zinc-400 hover:text-[#ff5500] transition-colors" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-400 hover:text-[#ff5500] transition-colors" aria-label="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-400 hover:text-[#ff5500] transition-colors"
                        aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" class="text-zinc-400 hover:text-[#ff5500] transition-colors"
                        aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-base">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="#how-repostchain-works"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">How It Works</a>
                    </li>
                    <li><a wire:navigate href="{{ route('f.features') }}"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Features</a>
                    </li>
                    <li><a href="https://repostchain.com/blog"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Blog</a>
                    </li>
                    <li><a wire:navigate href="{{ route('f.faq') }}"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-base">Legal</h4>
                <ul class="space-y-3">
                    <li><a wire:navigate href="{{ route('f.terms-and-conditions') }}"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Terms of
                            Service</a></li>
                    <li><a wire:navigate href="{{ route('f.privacy-policy') }}"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Privacy
                            Policy</a></li>
                    <li><a wire:navigate href="{{ route('f.refund-policy') }}" class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Refund
                            Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-base">Contact</h4>
                <ul class="space-y-3">
                    <li><a href="mailto:support@repostchain.com"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">support@repostchain.com</a>
                    </li>
                    <li><a wire:navigate href="{{ route('f.contact-us') }}"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Contact
                            Us</a></li>
                    <li><a wire:navigate href="{{ route('f.help') }}"
                            class="text-zinc-400 hover:text-zinc-200 transition-colors text-sm">Help
                            Center</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-zinc-800/50 pt-8 text-center">
            <p class="text-zinc-500 text-sm">&copy; 2025 RepostChain. All rights reserved.</p>
        </div>
    </div>
</footer>
