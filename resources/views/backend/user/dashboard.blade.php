@extends('backend.user.layouts.app',['page_slug' => 'dashboard'])
@section('title', 'Dashboard')
@section('content')
    <!-- Dashboard Content (Default) -->
    <div id="content-dashboard" class="page-content ">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-center py-12">
                <i data-lucide="home" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Dashboard</h3>
                <p class="text-gray-600">Welcome to your dashboard. Overview of your activities will appear here.</p>
            </div>
        </div>
    </div>
@endsection