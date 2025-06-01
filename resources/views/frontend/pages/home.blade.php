<x-frontend::layout>

    <x-slot name="title">Home</x-slot>
    <x-slot name="page_slug">home</x-slot>

    <main>
        {{-- Sections  --}}
        @include('frontend.pages.sections.hero')
        @include('frontend.pages.sections.about')
    </main>

</x-frontend::layout>
