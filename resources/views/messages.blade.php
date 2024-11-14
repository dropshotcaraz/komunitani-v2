<x-app-layout>
    <div class="flex min-h-screen">
        <!-- Sidebar (if you're using one) -->
        <aside class="w-64 bg-gray-800 text-white hidden md:block">
            <!-- Sidebar content goes here -->
        </aside>
        
        <!-- Main Content -->
        <div class="flex-grow p-6">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <main class="w-full">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Page One') }}
                </h2>
            </x-slot>

            <!-- Ensure the main content has padding to prevent clashing with sidebar -->
            <div class="py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ __("This is page one using normal controller") }}
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
