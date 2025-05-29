<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    @push('css')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
        {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet"> --}}
        <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
    @endpush

    <x-admin.dynamic-datatable :config="$tableConfig" />

</x-admin::layout>
