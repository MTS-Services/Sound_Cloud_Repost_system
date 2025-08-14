<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .text-orange-500 {
            color: #F26724;
        }

        .bg-orange-500 {
            background-color: #F26724;
        }

        .focus\:ring-orange-500:focus {
            --tw-ring-color: #F26724;
        }

        .hover\:border-gray-400:hover {
            border-color: #9CA3AF;
        }

        /* Styles for disabled cards */
        .card-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-[#F8F9FA] flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-7xl mx-auto">
        <div class="flex items-center space-x-4 mb-6 justify-between">
            <img class="h-20 w-20 rounded-full object-cover" src="{{ $user->avatar }}" alt="User Avatar">
            <div>
                <h1 class="text-3xl font-bold text-[#1F2937]">{{ $user->name }}</h1>
            </div>
            <div class="">
                <p class="text-sm font-medium text-gray-700">City</p>
                <p class="text-base font-semibold text-[#1F2937]">{{ $user->userInfo?->city }}</p>
            </div>
            <div class="">
                <p class="text-sm font-medium text-gray-700">Country</p>
                <p class="text-base font-semibold text-[#1F2937]">{{ $user->userInfo?->country }}</p>
            </div>
            <div class="">
                <p class="text-sm font-medium text-gray-700">Followers</p>
                <p class="text-base font-semibold text-[#1F2937]">{{ number_format($user->userInfo?->followers_count) }}
                </p>
            </div>
            <div class="">
                <p class="text-sm font-medium text-gray-700">Tracks</p>
                <p class="text-base font-semibold text-[#1F2937]">{{ number_format($user->userInfo?->tracks_count) }}
                </p>
            </div>
        </div>


        <form class="space-y-4" action="{{ route('user.email.store') }}" method="POST">
            @csrf
            <hr class="border-gray-200 my-6">
            @error('genres')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            @error('genres.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <div class="mb-6">
                <h2 class="text-xl font-bold text-[#1F2937] mb-4">Select your genres</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach (AllGenres() as $genre => $logo)
                        <label for="{{ Str::slug($genre) }}"
                            class="flex flex-col items-center justify-center p-4 rounded-lg border-2 border-gray-300 cursor-pointer transition-all duration-200 hover:border-orange-500 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-100 genre-card">
                            <input type="checkbox" id="{{ Str::slug($genre) }}" value="{{ $genre }}"
                                name="genres[]" class="sr-only peer genre-checkbox">
                            <div class="mb-2">
                                {!! $logo !!}
                            </div>
                            <span
                                class="text-sm font-semibold text-gray-700 has-[:checked]:text-orange-500">{{ $genre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-gray-200 my-6">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="john.doe@example.com"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:border-gray-400" />
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" id="submitButton"
                class="w-full bg-orange-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                {{ __('Submit') }}
            </button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            var maxGenres = 5;
            var submitButton = $('#submitButton');
            var genreCheckboxes = $('.genre-checkbox');
            var genreLabels = $('.genre-card');

            function updateSubmitButtonState() {
                var checkedCount = genreCheckboxes.filter(':checked').length;
                if (checkedCount === maxGenres) {
                    submitButton.prop('disabled', false);
                } else {
                    submitButton.prop('disabled', true);
                }
            }

            genreCheckboxes.on('change', function() {
                var checkedCount = genreCheckboxes.filter(':checked').length;
                var currentCheckbox = $(this);

                if (checkedCount >= maxGenres) {
                    // Disable all unchecked checkboxes
                    genreCheckboxes.not(':checked').each(function() {
                        var uncheckedCheckbox = $(this);
                        var label = uncheckedCheckbox.closest('.genre-card');
                        uncheckedCheckbox.prop('disabled', true);
                        label.addClass('card-disabled');
                    });
                } else {
                    // Re-enable all checkboxes if less than maxGenres are checked
                    genreCheckboxes.prop('disabled', false);
                    genreLabels.removeClass('card-disabled');
                }

                // If a user tries to check more than maxGenres, prevent it
                if (checkedCount > maxGenres) {
                    currentCheckbox.prop('checked', false);
                    alert("You can select a maximum of " + maxGenres + " genres.");
                }

                updateSubmitButtonState();
            });

            // Initial check on page load to set button state
            updateSubmitButtonState();
        });
    </script>

</body>

</html>
