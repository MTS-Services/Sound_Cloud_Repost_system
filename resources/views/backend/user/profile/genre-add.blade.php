<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RepostChain Registration Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'slide-in': 'slideIn 0.3s ease-out',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(8px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-gray-800 leading-relaxed">
    <div x-data="registrationForm()" x-init="prefillFromServer()" class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo Section -->
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-white">
                                <path d="M9 18V5l12-2v13" />
                                <circle cx="6" cy="18" r="3" />
                                <circle cx="18" cy="16" r="3" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">RepostChain</h1>
                    </div>

                    <!-- Step Indicators -->
                    <div class="flex items-center gap-2" aria-label="Steps">
                        <template x-for="step in 3" :key="step">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-200"
                                    :class="{
                                        'bg-orange-500 text-white': step === currentStep,
                                        'bg-green-500 text-white': step < currentStep,
                                        'bg-gray-200 text-gray-600': step > currentStep
                                    }"
                                    x-text="step">
                                </div>
                                <div x-show="step < 3" class="w-12 h-0.5 mx-2 transition-all duration-200"
                                    :class="step < currentStep ? 'bg-green-500' : 'bg-gray-200'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto p-6">
            <div class="relative">
                <!-- Step 1: Email Registration -->
                <section x-show="currentStep === 1" x-transition:enter="animate-slide-in"
                    class="bg-white rounded-2xl shadow-xl p-8 md:p-12" aria-labelledby="s1h">
                    <div class="text-center mb-8">
                        <h2 id="s1h" class="text-3xl font-bold text-gray-900 mb-2">Hi there {{ $user->name }}!
                        </h2>
                        <p class="text-lg text-gray-600">Confirm your email and start growing your reach.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Form Section -->
                        <div>
                            <!-- User Profile -->
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl mb-6">
                                <img class="h-20 w-20 rounded-full object-cover" src="{{ $user->avatar }}"
                                    alt="User Avatar" />
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $user->name }}
                                        ({{ $user->nickname }})</h3>
                                    <p class="text-gray-600 text-sm mb-1">{{ $user->email }}</p>
                                    <p class="text-gray-500 text-sm mb-4">{{ $user->userInfo?->city }},
                                        {{ $user->userInfo?->country }}</p>
                                    <div class="flex gap-8">
                                        <div class="text-center">
                                            <div class="text-lg font-semibold text-gray-900">
                                                {{ number_format($user->userInfo?->followers_count) }}</div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">Followers</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-semibold text-gray-900">
                                                {{ number_format($user->userInfo?->tracks_count) }}</div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">Tracks</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    <input type="email" id="email" x-model.trim="formData.email"
                                        placeholder="example@you.com" autocomplete="email"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-base transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                                </div>
                                <p x-show="formData.email && !isValidEmail(formData.email)"
                                    class="mt-2 text-sm text-red-600">Please enter a valid email.</p>
                            </div>

                            <!-- Referral Section -->
                            <div class="mb-6">
                                <button type="button" @click="showReferral = !showReferral"
                                    class="text-orange-600 text-sm font-medium hover:text-orange-700 transition-colors">
                                    I have a referral code
                                </button>
                                <div x-show="showReferral" x-transition class="mt-2">
                                    <input type="text" x-model.trim="formData.referralCode"
                                        placeholder="Enter referral code"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-base transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" />
                                </div>
                            </div>

                            <!-- Terms Section -->
                            <div class="flex items-start gap-3 mb-6">
                                <input type="checkbox" id="terms" x-model="formData.termsAccepted"
                                    class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500" />
                                <label for="terms" class="text-sm text-gray-600 leading-relaxed">
                                    I agree to the <a href="#"
                                        class="text-orange-600 font-medium hover:text-orange-700">Terms of Use</a>,
                                    <a href="#"
                                        class="text-orange-600 font-medium hover:text-orange-700">Privacy
                                        Policy</a> &
                                    <a href="#" class="text-orange-600 font-medium hover:text-orange-700">Cookie
                                        Policy</a>
                                </label>
                            </div>

                            <!-- Next Button -->
                            <button @click="goToStep(2)" :disabled="!isStep1Valid" :aria-disabled="!isStep1Valid"
                                :class="isStep1Valid ? 'bg-orange-500 hover:bg-orange-600' : 'bg-gray-300 cursor-not-allowed'"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 text-white font-medium rounded-xl transition-all duration-200">
                                <span>Next</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <!-- How It Works Section -->
                        <aside class="hidden lg:block">
                            <div class="bg-gray-50 rounded-2xl p-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">How it works</h3>
                                <div class="space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                            1</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Create your account</h4>
                                            <p class="text-gray-600 text-sm">Sign up and connect your SoundCloud
                                                profile to get started</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                            2</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Set up your campaign</h4>
                                            <p class="text-gray-600 text-sm">Configure your repost campaign and set
                                                your preferences</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                            3</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1">Get reposted & grow</h4>
                                            <p class="text-gray-600 text-sm">Watch your reach expand as others share
                                                your music</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </section>

                <!-- Step 2: Genre Selection -->
                <section x-show="currentStep === 2" x-transition:enter="animate-slide-in"
                    class="bg-white rounded-2xl shadow-xl p-8 md:p-12" aria-labelledby="s2h">
                    <div class="text-center mb-8">
                        <h2 id="s2h" class="text-3xl font-bold text-gray-900 mb-2">What music do you produce or
                            like?</h2>
                        <p class="text-lg text-gray-600 mb-4">Select up to 5 genres. Don't worry, you can edit these
                            later.</p>
                        <div
                            class="inline-block px-4 py-2 bg-orange-50 text-orange-600 rounded-full text-sm font-medium">
                            <span x-text="selectedGenres.length"></span>/5 selected
                        </div>
                    </div>

                    <!-- Genre Grid (rendered by Blade, interactive with Alpine) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                        @foreach (AllGenres() as $genre => $logo)
                            <div @click="toggleGenre('{{ addslashes($genre) }}')"
                                :class="{
                                    'border-orange-500 bg-orange-50 text-orange-600': selectedGenres.includes(
                                        '{{ addslashes($genre) }}'),
                                    'border-gray-200 bg-white text-gray-600 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-600':
                                        !selectedGenres.includes('{{ addslashes($genre) }}') && selectedGenres.length <
                                        maxGenres,
                                    'opacity-40 cursor-not-allowed': selectedGenres.length >= maxGenres && !
                                        selectedGenres.includes('{{ addslashes($genre) }}')
                                }"
                                class="relative p-4 border-2 rounded-xl text-center cursor-pointer transition-all duration-200 font-medium"
                                role="checkbox" :aria-checked="selectedGenres.includes('{{ addslashes($genre) }}')">
                                <label class="flex flex-col items-center justify-center p-3 rounded-lg">
                                    <div class="mb-2">{!! $logo !!}</div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $genre }}</span>
                                </label>
                                <div x-show="selectedGenres.includes('{{ addslashes($genre) }}')"
                                    class="absolute top-2 right-2 text-orange-500 font-bold text-sm">✓</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex gap-4 justify-between">
                        <button @click="goToStep(1)"
                            class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5" />
                                <path d="m12 19-7-7 7-7" />
                            </svg>
                            Back
                        </button>
                        <button @click="goToStep(3)" :disabled="!canContinueGenres" :aria-disabled="!canContinueGenres"
                            :class="canContinueGenres ? '' : 'cursor-not-allowed'"
                            class="font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg px-3 py-2 text-base inline-flex items-center justify-center bg-orange-600 text-white hover:bg-orange-500 active:bg-orange-700 disabled:bg-orange-500 disabled:text-gray-50 disabled:cursor-not-allowed h-auto">
                            <span>Continue</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </section>

                <!-- Step 3: Completion -->
                <section x-show="currentStep === 3" x-transition:enter="animate-slide-in"
                    class="bg-white rounded-2xl shadow-xl p-8 md:p-12" aria-labelledby="s3h">
                    <div class="text-center">
                        <!-- Success Icon -->
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <polyline points="20,6 9,17 4,12" />
                            </svg>
                        </div>

                        <div class="mb-8">
                            <h2 id="s3h" class="text-3xl font-bold text-gray-900 mb-2">You're all set!</h2>
                            <p class="text-lg text-gray-600">Review your information and complete your registration.
                            </p>
                        </div>

                        <!-- User Summary -->
                        <div class="text-left max-w-md mx-auto mb-8">
                            <!-- Profile Summary -->
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-6">
                                <img class="h-20 w-20 rounded-full object-cover" src="{{ $user->avatar }}"
                                    alt="User Avatar" />
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $user->name }}
                                        ({{ $user->nickname }})</h3>
                                    <p class="text-gray-600 text-sm" x-text="formData.email"></p>
                                </div>
                            </div>

                            <!-- Selected Genres -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-4">Selected Genres:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="genre in selectedGenres" :key="genre">
                                        <div
                                            class="flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-600 rounded-full text-sm font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 18V5l12-2v13" />
                                                <circle cx="6" cy="18" r="3" />
                                                <circle cx="18" cy="16" r="3" />
                                            </svg>
                                            <span x-text="genre"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Final Actions -->
                        <div class="flex gap-4 max-w-md mx-auto">
                            <button @click="goToStep(2)"
                                class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5" />
                                    <path d="m12 19-7-7 7-7" />
                                </svg>
                                Back
                            </button>

                            <!-- Submit form -->
                            <form x-ref="finalForm" action="{{ route('user.email.store') }}" method="POST"
                                class="flex-1">
                                @csrf
                                <button type="submit" @click="submitForm()"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-orange-500 text-white font-medium rounded-xl hover:bg-orange-600 transition-all duration-200">
                                    Get Started
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        function registrationForm() {
            return {
                currentStep: 1,
                showReferral: false,
                maxGenres: 5,
                formData: {
                    email: '',
                    referralCode: '',
                    termsAccepted: false,
                },
                selectedGenres: [],

                prefillFromServer() {
                    // Pre-fill from Blade variables when available
                    this.formData.email = this.formData.email || "{{ $user->email }}";
                },

                get isStep1Valid() {
                    return this.isValidEmail(this.formData.email) && this.formData.termsAccepted;
                },

                get canContinueGenres() {
                    return this.selectedGenres.length >= 1 && this.selectedGenres.length <= this.maxGenres;
                },

                isValidEmail(email) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email || '');
                },

                goToStep(step) {
                    if (step >= 1 && step <= 3) {
                        this.currentStep = step;
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                },

                toggleGenre(genre) {
                    if (this.selectedGenres.includes(genre)) {
                        this.selectedGenres = this.selectedGenres.filter(g => g !== genre);
                    } else if (this.selectedGenres.length < this.maxGenres) {
                        this.selectedGenres.push(genre);
                    }
                },

                submitForm() {
                    if (!this.isStep1Valid) {
                        this.goToStep(1);
                        return;
                    }
                    if (!this.canContinueGenres) {
                        this.goToStep(2);
                        return;
                    }

                    // Build hidden inputs for the POST
                    const form = this.$refs.finalForm;
                    // Clear any previous hidden fields
                    [...form.querySelectorAll('input[type=hidden]')].forEach(el => el.remove());

                    const addHidden = (name, value) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = value;
                        form.appendChild(input);
                    };

                    addHidden('email', this.formData.email);
                    addHidden('referralCode', this.formData.referralCode);
                    // Send genres[] as multiple fields
                    this.selectedGenres.forEach(g => addHidden('genres[]', g));
                    addHidden('termsAccepted', this.formData.termsAccepted ? '1' : '0');

                    form.submit();
                }
            }
        }
    </script>
</body>

</html>
