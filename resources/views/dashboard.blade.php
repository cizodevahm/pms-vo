<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ Auth::user()->role }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <!-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                </div>
            </div> -->

            <div class="grid grid-cols-4 gap-2 sm:gap-4 lg:gap-6 mb-6">

                <!-- Total Projects -->
                <a href="{{ route('projects.index') }}"
                    class="flex items-center bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md hover:bg-gray-50 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $stats['total_projects'] }}</h2>
                        <p class="text-sm text-gray-600">Total Projects</p>
                    </div>
                </a>

                <!-- Total Tasks -->
                <a href="{{ route('tasks.index') }}"
                    class="flex items-center bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md hover:bg-gray-50 transition-all duration-200 cursor-pointer">
                    <div class="p-3  bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</h2>
                        <p class="text-sm text-gray-600">Total Tasks</p>
                    </div>
                </a>

                <!-- Pending Tasks -->
                <a href="{{ route('tasks.index') }}"
                    class="flex items-center bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md hover:bg-gray-50 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $stats['pending_tasks'] }}</h2>
                        <p class="text-sm text-gray-600">Pending Tasks</p>
                    </div>
                </a>

                <!-- Completed Tasks -->
                <a href="{{ route('tasks.index') }}"
                    class="flex items-center bg-white rounded-lg p-6 shadow-sm border border-gray-200 hover:shadow-md hover:bg-gray-50 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $stats['completed_tasks'] }}</h2>
                        <p class="text-sm text-gray-600">Completed Tasks</p>
                    </div>
                </a>

            </div>

            <div class="grid grid-cols-1  gap-6">
                <x-card title="Quick Actions">
                    <div class="space-y-3">
                        @if(Auth::user()->canManageProjects())
                            <a href="{{ route('projects.create') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                Create New Project
                            </a>
                        @endif
                        <a href="{{ route('tasks.create') }}"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                            Add New Task
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>