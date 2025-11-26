<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex space-x-2">
                @if($task->canBeEditedBy(auth()->user()))
                    <a href="{{ route('tasks.edit', $task) }}"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit Task
                    </a>
                @endif
                <a href="{{ route('tasks.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Task Details -->
                <div class="lg:col-span-2">
                    <x-card title="Task Details">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Title</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $task->title }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $task->description }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    @php
                                        $statusColors = [
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'In Progress' => 'bg-blue-100 text-blue-800',
                                            'Completed' => 'bg-green-100 text-green-800'
                                        ];
                                    @endphp
                                    <span
                                        class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$task->status] }}">
                                        {{ $task->status }}
                                    </span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $task->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Project & Team Info -->
                <div class="space-y-6">
                    <!-- Project Info -->
                    <x-card title="Project">
                        <div class="flex flex-col  items-start space-x-3">
                            @if($task->project->logo)
                                <img width="300" src="{{ asset('storage/' . $task->project->logo) }}" alt="{{ $task->project->name }}"
                                    class="rounded-lg object-cover">
                            @endif
                            <div class="mt-3
                            ">
                                <h3 class="text-sm font-medium text-gray-900">{{ $task->project->name }}</h3>
                                <p class="text-sm text-gray-500">{{ Str::limit($task->project->description, 100) }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('projects.show', $task->project) }}"
                                class="text-indigo-600 hover:text-indigo-900 text-sm">
                                View Project →
                            </a>
                        </div>
                    </x-card>

                    <!-- User Info -->
                    <x-card title="Assigned To">
                        <div class="flex items-center space-x-3">
                            <!-- <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span
                                    class="text-gray-500 text-sm m-3 font-medium">{{ substr($task->user->name, 0, 2) }}</span>
                            </div> -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $task->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $task->user->role }}</p>
                                <p class="text-sm text-gray-500">{{ $task->user->email }}</p>
                            </div>
                        </div>
                        <!-- <div class="mt-3">
                            <span class="text-gray-500 text-sm">
                                User Profile
                            </span>
                        </div> -->
                    </x-card>

                    <!-- Task Meta -->
                    <x-card title="Task Information">
                        <div class="space-y-2 text-sm">
                            @if($task->creator)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Created by:</span>
                                    <span class="text-gray-900">{{ $task->creator->name }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-500">Created:</span>
                                <span class="text-gray-900">{{ $task->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Updated:</span>
                                <span class="text-gray-900">{{ $task->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>