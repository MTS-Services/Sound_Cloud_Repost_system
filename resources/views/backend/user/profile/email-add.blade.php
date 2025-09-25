{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RepostChain Registration Flow</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link id="favicon" rel="icon" href="{{ storage_url(app_setting('favicon')) }}" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316', // ← same as Tailwind's bg-orange-500
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                    },
                    animation: {
                        'slide-in': 'slideIn 0.3s ease-out',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
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
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .animate-delay-100 {
            animation-delay: 100ms;
        }

        .animate-delay-200 {
            animation-delay: 200ms;
        }

        .animate-delay-300 {
            animation-delay: 300ms;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Smooth transitions */
        .transition-slow {
            transition: all 0.5s ease;
        }

        /* Custom checkbox */
        .custom-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            outline: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .custom-checkbox:checked {
            background-color: #f97316;
            border-color: #f97316;
        }

        .custom-checkbox:checked::after {
            content: "✓";
            position: absolute;
            color: white;
            font-size: 14px;
            font-weight: bold;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body class="font-sans text-gray-800 leading-relaxed bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen"> --}}
<x-user::layout>
    <x-slot name="title">Registration Flow</x-slot>
    <x-slot name="page_slug">registration-flow</x-slot>
    <div x-data="registrationForm()" x-init="prefillFromServer()" x-cloak>
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">

                    <a href="{{ route('f.landing') }}">
                        <img src="{{ app_setting('app_logo_dark') ? asset('storage/' . app_setting('app_logo')) : asset('assets/logo/rc-logo-black.png') }}"
                            alt="{{ config('app.name') }}" class="w-36 lg:w-48 object-contain">
                    </a>
                    <div class="hidden sm:flex items-center gap-2" aria-label="Steps">
                        <template x-for="step in 3" :key="step">
                            <div class="flex items-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-200"
                                    :class="{
                                        'bg-orange-500 text-white shadow-md': step === currentStep,
                                        'bg-orange-500 text-white border-2 border-orange-500': step < currentStep,
                                        'bg-gray-200 text-gray-600': step > currentStep
                                    
                                    }">
                                    <template x-if="step < currentStep">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                    <template x-if="step >= currentStep">
                                        <span x-text="step"></span>
                                    </template>
                                </div>

                                <div x-show="step < 3" class="w-12 h-1 mx-2 transition-all duration-200"
                                    :class="step < currentStep ? 'bg-orange-500' : 'bg-gray-200'"></div>
                            </div>
                        </template>
                    </div>
                    <div class="sm:hidden text-sm font-medium text-gray-600">
                        Step <span x-text="currentStep"></span>/3
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto p-4 sm:p-6">
            <div class="relative">
                <!-- Step 1: Email Registration -->
                <section x-show="currentStep === 1" x-transition:enter="animate-slide-in"
                    class="bg-white rounded-xl shadow-lg overflow-hidden" aria-labelledby="s1h">
                    <div class="p-6 sm:p-8 md:p-10">
                        <div class="text-center mb-8 animate-fade-in">
                            <h2 id="s1h" class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                                Hi there <span class="text-primary-600">{{ $user->name }}</span>!
                            </h2>
                            <p class="text-lg text-gray-600 max-w-md mx-auto">
                                Confirm your email and start growing your reach.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Form Section -->
                            <div class="animate-slide-in animate-delay-100">
                                <!-- User Profile -->
                                <div
                                    class="flex gap-4 p-4 bg-gray-50 rounded-xl mb-6 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                                    <img class="h-20 w-20 rounded-full object-cover shadow-md border-2 border-white"
                                        src="{{ soundcloud_image($user->avatar) }}" alt="{{ $user->name }}" />
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $user->name }}
                                            {{-- <span class="text-gray-500">({{ $user->nickname ?? 'N/A' }})</span> --}}
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-1">{{ $user->email }}</p>
                                        <p class="text-gray-500 text-sm mb-4">{{ $user->userInfo?->city ?? 'N/A' }},
                                            {{ $user->userInfo?->country ?? 'N/A' }}</p>
                                        <div class="flex gap-6">
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ number_format($user->userInfo?->followers_count) }}</div>
                                                <div class="text-xs text-gray-500 uppercase tracking-wider">Followers
                                                </div>
                                            </div>
                                            <div class="border-l border-gray-200"></div>
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ number_format($user->userInfo?->track_count) }}</div>
                                                <div class="text-xs text-gray-500 uppercase tracking-wider">Tracks</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Input -->
                                <div class="mb-6">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                        Address</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                        <input type="email" id="email" x-model.trim="formData.email"
                                            placeholder="example@you.com" autocomplete="email"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm" />
                                    </div>
                                    <p x-show="formData.email && !isValidEmail(formData.email)"
                                        class="mt-2 text-sm text-red-600 animate-fade-in">Please enter a valid email
                                        address.</p>
                                </div>

                                <!-- Referral Section -->
                                <div class="mb-6">
                                    <button type="button" @click="showReferral = !showReferral"
                                        class="text-primary-600 text-sm font-medium hover:text-primary-700 transition-colors flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        I have a referral code
                                    </button>
                                    <div x-show="showReferral" x-transition class="mt-3 animate-fade-in">
                                        <input type="text" x-model.trim="formData.referralCode"
                                            placeholder="Enter referral code"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm" />
                                    </div>
                                </div>

                                <!-- Terms Section -->
                                <div class="flex items-start gap-3 mb-6">
                                    <input type="checkbox" id="terms" x-model="formData.termsAccepted"
                                        class="custom-checkbox mt-0.5" />
                                    <label for="terms" class="text-sm text-gray-600 leading-relaxed">
                                        I agree to the <a href="#"
                                            class="text-primary-600 font-medium hover:text-primary-700 hover:underline">Terms
                                            of Use</a>,
                                        <a href="#"
                                            class="text-primary-600 font-medium hover:text-primary-700 hover:underline">Privacy
                                            Policy</a> &
                                        <a href="#"
                                            class="text-primary-600 font-medium hover:text-primary-700 hover:underline">Cookie
                                            Policy</a>
                                    </label>
                                </div>

                                <!-- Next Button -->
                                <button @click="goToStep(2)" :disabled="!isStep1Valid" :aria-disabled="!isStep1Valid"
                                    class="w-full font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg px-4 !py-2 text-base inline-flex items-center justify-center bg-orange-600 text-white hover:bg-orange-500 active:bg-orange-700 disabled:bg-orange-500 disabled:text-gray-50 disabled:cursor-not-allowed h-auto">
                                    <span>Continue</span>
                                    <x-heroicon-o-arrow-right class="h-4 w-4" />
                                </button>
                            </div>

                            <!-- How It Works Section -->
                            <aside class="hidden lg:block animate-slide-in animate-delay-200">
                                <div class=" bg-gray-50 rounded-xl p-8 h-full border border-primary-100 shadow-sm">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        How it works
                                    </h3>
                                    <div class="space-y-6">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                                                1
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-1">Create your account</h4>
                                                <p class="text-gray-600 text-sm">Sign up and connect your SoundCloud
                                                    profile to get started</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                                                2
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-1">Set up your campaign</h4>
                                                <p class="text-gray-600 text-sm">Configure your repost campaign and set
                                                    your preferences</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                                                3
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-1">Get reposted & grow</h4>
                                                <p class="text-gray-600 text-sm">Watch your reach expand as others
                                                    share your music</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-6 border-t border-primary-100">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-primary-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                Your information is securely encrypted and protected.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </section>

                <!-- Step 2: Genre Selection -->
                <section x-show="currentStep === 2" x-transition:enter="animate-slide-in"
                    class="bg-white rounded-xl shadow-lg overflow-hidden" aria-labelledby="s2h">
                    <div class="p-6 sm:p-8 md:p-10">
                        <div class="text-center mb-8">
                            <h2 id="s2h" class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                                What music do you produce or like?
                            </h2>
                            <p class="text-lg text-gray-600 mb-4 max-w-md mx-auto">
                                Select up to 5 genres. Don't worry, you can edit these later.
                            </p>
                            <div
                                class="inline-flex items-center px-4 py-2 bg-primary-50 text-primary-700 rounded-full text-sm font-medium border border-primary-100 shadow-sm">
                                <span x-text="selectedGenres.length" class="font-bold"></span>/5 selected
                            </div>
                        </div>

                        <!-- Genre Grid -->
                        <div
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7 gap-3 mb-8">
                            @foreach (AllGenres() as $genre)
                                <div @click="toggleGenre('{{ addslashes($genre) }}')"
                                    :class="{
                                        'border-primary-500 bg-primary-50 text-primary-700 shadow-md': selectedGenres
                                            .includes('{{ addslashes($genre) }}'),
                                        'border-gray-200 bg-white text-gray-600 hover:border-primary-300 hover:bg-primary-50 hover:text-primary-600 shadow-sm':
                                            !selectedGenres.includes('{{ addslashes($genre) }}') && selectedGenres
                                            .length < maxGenres,
                                        'opacity-50 cursor-not-allowed': selectedGenres.length >= maxGenres && !
                                            selectedGenres.includes('{{ addslashes($genre) }}')
                                    }"
                                    class="relative p-3 border-2 rounded-lg text-center cursor-pointer transition-all duration-200 font-medium group"
                                    role="checkbox"
                                    :aria-checked="selectedGenres.includes('{{ addslashes($genre) }}')">
                                    <label class="flex flex-col items-center justify-center  rounded-lg">
                                        <div
                                            class="text-sm font-semibold text-gray-700 group-hover:text-primary-600 transition-colors">
                                            {{ $genre }}</div>
                                        {{-- <span
                                            class="text-sm font-semibold text-gray-700 group-hover:text-primary-600 transition-colors">
                                            {{ $genre }}
                                        </span> --}}
                                    </label>
                                    <div x-show="selectedGenres.includes('{{ addslashes($genre) }}')"
                                        class="absolute top-2 right-2 w-5 h-5 bg-primary-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-sm">
                                        ✓
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex flex-row gap-3 justify-between">
                            <div class="flex items-center">
                                <x-gbutton variant="text" @click="goToStep(1)">
                                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                                    {{ __('Back') }}
                                </x-gbutton>
                            </div>
                            <div>
                                <button @click="goToStep(3)" :disabled="!canContinueGenres"
                                    :aria-disabled="!canContinueGenres" {{-- disabled condition --}}
                                    class="font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg px-4 py-2 text-base inline-flex items-center justify-center bg-orange-600 text-white hover:bg-orange-500 active:bg-orange-700 disabled:bg-orange-500 disabled:text-gray-50 disabled:cursor-not-allowed h-auto">
                                    <span>Continue</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Step 3: Completion -->
                <section x-show="currentStep === 3" x-transition:enter="animate-slide-in"
                    class="bg-white rounded-xl shadow-lg overflow-hidden" aria-labelledby="s3h">
                    <div class="p-6 sm:p-6 md:p-10">
                        <div class="text-center max-w-2xl mx-auto">
                            <!-- Success Icon -->
                            <div
                                class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-check w-8 h-8 text-green-600">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>

                            <div class="mb-8 animate-fade-in">
                                <h2 id="s3h" class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                                    You're all set!
                                </h2>
                                <p class="text-lg text-gray-600">
                                    Review your information and complete your registration.
                                </p>
                            </div>

                            <!-- User Summary -->
                            <div
                                class="text-left mb-8 p-6  animate-slide-in bg-gray-50 border-gray-100 rounded-xl  border  shadow-sm animate-delay-100">
                                <!-- Profile Summary -->
                                <div class="flex items-center gap-4 p-1  ">
                                    <img class="h-16 w-16 rounded-full object-cover shadow-md border-2 border-white"
                                        src="{{ soundcloud_image($user->avatar) }}" alt="User Avatar" />
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $user->name }}
                                            {{-- <span class="text-gray-500">({{ $user->nickname ?? 'N/A' }})</span> --}}
                                        </h3>
                                        <p class="text-gray-600 text-sm" x-text="formData.email"></p>
                                    </div>
                                </div>

                                <!-- Selected Genres -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Selected Genres:</h4>
                                    <div class="flex flex-wrap gap-2 ">
                                        <template x-for="genre in selectedGenres" :key="genre">
                                            <div
                                                class="flex items-center gap-2 px-3 py-1.5 bg-primary-50 text-primary-700 rounded-full text-sm font-medium border border-primary-100 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" />
                                                </svg>
                                                <span x-text="genre"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Final Actions -->
                            <div
                                class="flex flex-col justify-between sm:flex-row gap-3 animate-slide-in animate-delay-200">
                                <x-gbutton variant="secondary" @click="goToStep(2)"><svg
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg> Back</x-gbutton>


                                <!-- Submit form -->
                                <form x-ref="finalForm" data-action="{{ route('user.email.store') }}" method="POST"
                                    class="justify-end">
                                    @csrf
                                    {{-- <button type="button" @click="submitForm()"
                                        class="w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-primary-600 text-white font-medium rounded-lg hover:shadow-md transition-all duration-200 shadow-sm transform hover:-translate-y-0.5">
                                        Complete Registration

                                    </button> --}}
                                    <x-gbutton type="submit" variant="primary" @click="submitForm()">Complete
                                        Registration <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg></x-gbutton>
                                </form>
                            </div>
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

                    // If we have pre-selected genres from server
                    @if (isset($preSelectedGenres) && is_array($preSelectedGenres))
                        this.selectedGenres = @json($preSelectedGenres);
                    @endif
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

                    const form = this.$refs.finalForm;

                    // Set action from data-attribute
                    form.action = form.dataset.action;

                    // Clear old hidden inputs
                    [...form.querySelectorAll('input[type=hidden].js-added')].forEach(el => el.remove());

                    const addHidden = (name, value) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = value;
                        input.classList.add('js-added');
                        form.appendChild(input);
                    };

                    addHidden('email', this.formData.email);
                    addHidden('referralCode', this.formData.referralCode);
                    this.selectedGenres.forEach(g => addHidden('genres[]', g));
                    addHidden('termsAccepted', this.formData.termsAccepted ? '1' : '0');

                    // Add loading state
                    const submitBtn = form.querySelector('button[type="button"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;

                    form.submit();
                }
            }
        }
    </script>
</x-user::layout>
{{-- </body>

</html> --}}
