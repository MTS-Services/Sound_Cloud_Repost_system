<div>
    @push('cs')
        <link rel="stylesheet" href="{{ asset('assets/landing/css/landing.css') }}">
    @endpush
    {{-- <slot name="title">Landing Page</slot> --}}

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
</div>
