<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RepostChain - Grow Your Music Reach</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="shortcut icon" href="{{ asset('default_img/logo.svg') }}" type="image/x-icon">
    
    @vite(['resources/css/frontend.css'])
    <link rel="stylesheet" href="{{ asset('assets/landing/css/landing.css') }}">
</head>
<body>
    <!-- Header -->
   @include('frontend.pages.landing-page.includes.header')
    <main>
        <!-- Hero Section -->
       @include('frontend.pages.landing-page.includes.hero')

        <!-- About Section -->
     @include('frontend.pages.landing-page.includes.about')
        <!-- How It Works Section -->
       @include('frontend.pages.landing-page.includes.how-it-works')
        <!-- Features Section -->
       @include('frontend.pages.landing-page.includes.features')

        <!-- Statistics Section -->
     @include('frontend.pages.landing-page.includes.statistics')

        <!-- Testimonials Section -->
       @include('frontend.pages.landing-page.includes.testimonials')

        <!-- FAQ Section -->
       {{-- @include('frontend.pages.landing-page.includes.faq') --}}
        <!-- CTA Section -->
        @include('frontend.pages.landing-page.includes.cta')
    </main>

    <!-- Footer -->
    @include('frontend.pages.landing-page.includes.footer')

    <script src="{{ asset('assets/landing/js/landing.js') }}"></script>

</body>
</html>