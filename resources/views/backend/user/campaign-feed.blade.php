<x-user::layout>
    <x-slot name="page_slug">campaign-feed</x-slot>
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('Repost Feed') }}</h2>
            <p class="text-gray-600 dark:text-gray-400">{{ __('Repost tracks to earn credits') }}</p>
        </div>
        <div
            class="shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-2 px-3 py-2 rounded-md w-24 h-10 dark:bg-gray-800">
            <i data-lucide="filter" class="w-4 h-4"></i>
            <span>{{ __('Filter') }}</span>
        </div>
    </div>
    {{-- @dd($campaigns) --}}

    <h2 class="text-lg font-semibold text-gray-800 ">Featured campaign</h2>
    <div class="my-6 bg-white border border-gray-200  shadow-sm">
        <div class="flex flex-col md:flex-row w-full gap-4 items-start">
            <!-- Left Album + Info + Waveform -->
            <div class="flex w-full md:w-1/2 gap-4 border border-gray-200 bg-gray-100 pr-2   h-48">
                <div class="relative w-full md:w-48 h-48 shrink-0">
                    <img src="{{ asset('default_img/other.png') }}" alt="Album Cover for Sinphony by Terrik"
                        class="w-full h-full object-cover" />
                    <div
                        class="absolute top-2 left-2 bg-cyan-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                        FEATURED
                    </div>
                </div>

                <div class="flex flex-col flex-1 justify-between">

                    <div class="flex justify-between items-start pt-1.5">
                        <div class="flex items-center gap-3">
                            <button
                                class="w-11 h-11 bg-orange-500 text-white rounded-full flex items-center justify-center shadow transition hover:bg-orange-600 focus:outline-none flex-shrink-0"
                                aria-label="Play/Pause" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M4.018 15.394C3.42 15.76 3 15.303 3 14.65V5.35C3 4.697 3.42 4.24 4.018 4.606l8.638 4.65c.596.32.596.868 0 1.188l-8.638 4.95z" />
                                </svg>
                            </button>
                            <div>
                                <a href="#" class="text-sm text-gray-500 underline hover:text-black">Terrik</a>
                                <h3 class="text-lg font-bold text-gray-800 leading-tight">Sinphony</h3>
                            </div>
                        </div>
                        <div class="items-center text-gray-500  text-sm mt-1">
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" class="hover:text-orange-600!" width="100"
                                    height="14">

                                    <path class="logo__path"
                                        d="M10.517 3.742c-.323 0-.49.363-.49.582 0 0-.244 3.591-.244 4.641 0 1.602.15 2.621.15 2.621 0 .222.261.401.584.401.321 0 .519-.179.519-.401 0 0 .398-1.038.398-2.639 0-1.837-.153-4.127-.284-4.592-.112-.395-.313-.613-.633-.613zm-1.996.268c-.323 0-.49.363-.49.582 0 0-.244 3.322-.244 4.372 0 1.602.119 2.621.119 2.621 0 .222.26.401.584.401.321 0 .581-.179.581-.401 0 0 .081-1.007.081-2.608 0-1.837-.206-4.386-.206-4.386 0-.218-.104-.581-.425-.581zm-2.021 1.729c-.324 0-.49.362-.49.582 0 0-.272 1.594-.272 2.644 0 1.602.179 2.559.179 2.559 0 .222.229.463.552.463.321 0 .519-.241.519-.463 0 0 .19-.944.19-2.546 0-1.837-.253-2.657-.253-2.657 0-.22-.104-.582-.425-.582zm-2.046-.358c-.323 0-.49.363-.49.582 0 0-.162 1.92-.162 2.97 0 1.602.069 2.496.069 2.496 0 .222.26.557.584.557.321 0 .581-.304.581-.526 0 0 .143-.936.143-2.538 0-1.837-.206-2.96-.206-2.96 0-.218-.198-.581-.519-.581zm-2.169 1.482c-.272 0-.232.218-.232.218v3.982s-.04.335.232.335c.351 0 .716-.832.716-2.348 0-1.245-.436-2.187-.716-2.187zm18.715-.976c-.289 0-.567.042-.832.116-.417-2.266-2.806-3.989-5.263-3.989-1.127 0-2.095.705-2.931 1.316v8.16s0 .484.5.484h8.526c1.655 0 3-1.55 3-3.155 0-1.607-1.346-2.932-3-2.932zm10.17.857c-1.077-.253-1.368-.389-1.368-.815 0-.3.242-.611.97-.611.621 0 1.106.253 1.542.699l.981-.951c-.641-.669-1.417-1.067-2.474-1.067-1.339 0-2.425.757-2.425 1.99 0 1.338.873 1.736 2.124 2.026 1.281.291 1.513.486 1.513.923 0 .514-.379.738-1.184.738-.65 0-1.26-.223-1.736-.777l-.98.873c.514.757 1.504 1.232 2.639 1.232 1.853 0 2.668-.873 2.668-2.163 0-1.477-1.193-1.845-2.27-2.097zm6.803-2.745c-1.853 0-2.949 1.435-2.949 3.502s1.096 3.501 2.949 3.501c1.852 0 2.949-1.434 2.949-3.501s-1.096-3.502-2.949-3.502zm0 5.655c-1.097 0-1.553-.941-1.553-2.153 0-1.213.456-2.153 1.553-2.153 1.096 0 1.551.94 1.551 2.153.001 1.213-.454 2.153-1.551 2.153zm8.939-1.736c0 1.086-.533 1.756-1.396 1.756-.864 0-1.388-.689-1.388-1.775v-3.897h-1.358v3.916c0 1.978 1.106 3.084 2.746 3.084 1.726 0 2.754-1.136 2.754-3.103v-3.897h-1.358v3.916zm8.142-.89l.019 1.485c-.087-.174-.31-.515-.475-.768l-2.703-3.692h-1.362v6.894h1.401v-2.988l-.02-1.484c.088.175.311.514.475.767l2.79 3.705h1.213v-6.894h-1.339v2.975zm5.895-2.923h-2.124v6.791h2.027c1.746 0 3.474-1.01 3.474-3.395 0-2.484-1.437-3.396-3.377-3.396zm-.097 5.472h-.67v-4.152h.719c1.436 0 2.028.688 2.028 2.076 0 1.242-.651 2.076-2.077 2.076zm7.909-4.229c.611 0 1 .271 1.242.737l1.26-.582c-.426-.883-1.202-1.503-2.483-1.503-1.775 0-3.016 1.435-3.016 3.502 0 2.143 1.191 3.501 2.968 3.501 1.232 0 2.047-.572 2.513-1.533l-1.145-.68c-.358.602-.718.864-1.329.864-1.019 0-1.611-.932-1.611-2.153-.001-1.261.583-2.153 1.601-2.153zm5.17-1.192h-1.359v6.791h4.083v-1.338h-2.724v-5.453zm6.396-.157c-1.854 0-2.949 1.435-2.949 3.502s1.095 3.501 2.949 3.501c1.853 0 2.95-1.434 2.95-3.501s-1.097-3.502-2.95-3.502zm0 5.655c-1.097 0-1.553-.941-1.553-2.153 0-1.213.456-2.153 1.553-2.153 1.095 0 1.55.94 1.55 2.153.001 1.213-.454 2.153-1.55 2.153zm8.557-1.736c0 1.086-.532 1.756-1.396 1.756-.864 0-1.388-.689-1.388-1.775v-3.794h-1.358v3.813c0 1.978 1.106 3.084 2.746 3.084 1.726 0 2.755-1.136 2.755-3.103v-3.794h-1.36v3.813zm5.449-3.907h-2.318v6.978h2.211c1.908 0 3.789-1.037 3.789-3.489 0-2.552-1.565-3.489-3.682-3.489zm-.108 5.623h-.729v-4.266h.783c1.565 0 2.21.706 2.21 2.133.001 1.276-.707 2.133-2.264 2.133z">
                                    </path>
                                </svg>
                            </a>
                            <div class="flex items-center gap-1.5 mt-1">
                                <button class="hover:text-gray-900 border p-1 rounded border-gray-300"
                                    aria-label="Like">
                                    <svg class="w-3 h-3 text-black" fill="none" stroke="currentColor"
                                        stroke-width="1.8" viewBox="0 0 24 24">
                                        <path d="M6 6h15l-1.5 9h-13.5l-1.5-9z" />
                                        <circle cx="9" cy="20" r="1.2" />
                                        <circle cx="18" cy="20" r="1.2" />
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center text-xs gap-1.5 border border-gray-300 rounded px-2.5 py-0.5 hover:bg-gray-50 hover:text-gray-900">
                                    <svg class="w-3 h-3 text-black" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                    <span>Share</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full h-[65px] cursor-pointer group mt-2">
                        <div class="absolute inset-0 flex items-center gap-px overflow-hidden">
                            @php
                                $total_bars = 190;
                                // Calculate played bars based on screenshot timestamp (53s / 198s)
                                $played_percentage = 53 / (3 * 60 + 18);
                                $played_bars = floor($total_bars * $played_percentage);
                            @endphp
                            @for ($i = 0; $i < $total_bars; $i++)
                                <div class="w-[3px] {{ $i < $played_bars ? 'bg-orange-500' : 'bg-gray-400' }}"
                                    style="height: {{ rand(15, 95) }}%"></div>
                            @endfor
                        </div>

                        <div class="absolute inset-0 w-full h-full">
                            <span
                                class="absolute left-2 top-2 bg-black/70 text-white text-xs px-1.5 py-0.5 rounded-sm font-mono">0:53</span>
                            <span
                                class="absolute right-2 bottom-0 text-white text-xs font-mono mix-blend-difference">3:18</span>

                            <img src="https://i.pravatar.cc/16?u=user1"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 32%; bottom: 6px;">
                            <img src="https://i.pravatar.cc/16?u=user2"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 45%; bottom: 12px;">
                            <img src="https://i.pravatar.cc/16?u=user3"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 51%; bottom: 4px;">
                            <img src="https://i.pravatar.cc/16?u=user4"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 68%; bottom: 10px;">
                            <img src="https://i.pravatar.cc/16?u=user5"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 76%; bottom: 8px;">
                            <img src="https://i.pravatar.cc/16?u=user6"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 80%; bottom: 14px;">
                            <img src="https://i.pravatar.cc/16?u=user7"
                                class="absolute w-4 h-4 rounded-full border border-white"
                                style="left: 92%; bottom: 10px;">

                            <div class="absolute top-1 right-2 text-white">
                                <svg role="img" viewBox="0 0 24 24" class="w-9 h-9 opacity-60" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <title>SoundCloud</title>
                                    <path
                                        d="M7.243 17.332c0 1.47-1.19 2.668-2.657 2.668S1.93 18.802 1.93 17.332s1.19-2.668 2.656-2.668c1.467 0 2.657 1.2 2.657 2.668zm0-10.668V0H0v10.664h1.93v-3.996c0-2.58 2.11-4.668 4.71-4.668h.602zm1.205 10.668c0 1.47-1.19 2.668-2.657 2.668S3.134 18.802 3.134 17.332s1.19-2.668 2.657-2.668c1.466 0 2.657 1.2 2.657 2.668zm1.21-10.668V4h-1.21v2.664h1.21zm1.205 10.668c0 1.47-1.19 2.668-2.657 2.668s-2.657-1.198-2.657-2.668s1.19-2.668 2.657-2.668c1.467 0 2.657 1.2 2.657 2.668zm1.21-10.668V4h-1.21v2.664h1.21zm1.205 10.668c0 1.47-1.19 2.668-2.657 2.668s-2.657-1.198-2.657-2.668s1.19-2.668 2.657-2.668c1.467 0 2.657 1.2 2.657 2.668zm1.208-10.668V4h-1.208v2.664h1.208zm1.206 10.668c0 1.47-1.19 2.668-2.656 2.668s-2.657-1.198-2.657-2.668s1.19-2.668 2.657-2.668c1.466 0 2.656 1.2 2.656 2.668zm1.208-10.668V4h-1.208v2.664h1.208zm3.02 0V4h-1.207v2.664h1.207zm-1.812 14.664V12h1.207v9.332h5.428V12h1.206v9.332H24V12h-1.18v10.668h-4.812z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500 -mt-1">
                        <a href="#" class="hover:underline">Privacy policy</a>
                        <span class="flex items-center gap-1.5 pb-2">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z">
                                </path>
                            </svg>
                            123
                        </span>
                    </div>

                </div>
            </div>
            <!-- Right section (optional/empty) -->
            <div class="flex w-full md:w-1/2  gap-3 h-48 ">
                <div class="flex flex-col justify-between h-full w-full py-3">
                    <!-- Avatar + Title + Icon -->
                    <div class="flex items-center gap-3 justify-between w-full">
                        <div class="flex items-center gap-3">
                            <img class="w-14 h-14 rounded-full object-cover"
                                src="{{ asset('default_img/other.png') }}" alt="Audio Cure avatar">
                            <div>
                                <div class="flex items-center gap-1">
                                    <span class=" text-slate-700">Audio Cure</span>
                                    <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center gap-8 px-3">
                            <div class="flex flex-col items-left text-gray-600">
                                <div class="flex  gap-1.5 text-left">
                                    <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="1" width="24" height="16" rx="3"
                                            fill="none" stroke="#BDBDBD" stroke-width="2" />
                                        <circle cx="8" cy="9" r="3" fill="none" stroke="#BDBDBD"
                                            stroke-width="2" />
                                    </svg>

                                    <span class="text-gray-600 text-sm">103</span>
                                </div>
                                <span class="text-xs text-gray-400 mt-1">REMAINING</span>
                            </div>

                            <button
                                class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-400  py-2 px-5 pl-8  shadow-sm">
                                <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                        stroke="#BDBDBD" stroke-width="2" />
                                    <circle cx="8" cy="9" r="3" fill="none" stroke="#BDBDBD"
                                        stroke-width="2" />
                                </svg>

                                <span>1 Repost</span>
                            </button>
                        </div>
                    </div>
                    <!-- Genre Badge -->
                    <div>
                        <span
                            class="inline-block bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1.5 mt-2 shadow-sm">
                            Dance & EDM
                        </span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-user::layout>
{{-- <body class="bg-white flex items-center justify-center min-h-screen p-4">

    <div class="max-w-xl w-full bg-white p-4 border rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-start gap-3">
                <img class="w-12 h-12 rounded-full object-cover" src="https://i.imgur.com/uG9gG62.png" alt="Audio Cure avatar">
                
                <div class="flex flex-col">
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-gray-800">Audio Cure</span>
                        <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <div class="mt-3">
                        <span class="inline-block bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                            Dance & EDM
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-8">
                <div class="flex flex-col items-center text-gray-600">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="font-semibold text-lg">103</span>
                    </div>
                    <span class="text-xs font-bold tracking-wider text-gray-500">REMAINING</span>
                </div>

                <button class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-md shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span>1 Repost</span>
                </button>
            </div>
        </div>
    </div>
    </body> --}}
