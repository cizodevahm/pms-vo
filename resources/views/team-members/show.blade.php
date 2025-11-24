<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $user->name }}
            </h2>
            <div class="flex space-x-2">
                @if($canManage && $user->teamMember)
                    <a href="{{ route('team-members.edit', $user->teamMember) }}"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit Member
                    </a>
                @endif
                <a href="{{ route('team-members.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Team
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Info -->
                <div class="lg:col-span-1">
                    <x-card title="Profile">
                        <div class="text-center">
                            @if($user->teamMember && $user->teamMember->profile_photo)
                                <img src="{{ asset('storage/' . $user->teamMember->profile_photo) }}"
                                    alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                            @else
                                <div
                                    class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-gray-500 text-2xl font-medium">{{ substr($user->name, 0, 2) }}</span>
                                </div>
                            @endif

                            <h3 class="text-xl font-medium text-gray-900 mb-1">{{ $user->name }}</h3>
                            <p class="text-gray-600 mb-2">{{ $user->email }}</p>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $user->getRole() }}
                            </span>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-600 space-y-2">
                                <div class="flex justify-between">
                                    <span>Member since:</span>
                                    <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total tasks:</span>
                                    <span class="text-gray-900 font-medium">{{ $user->tasks_count }}</span>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Tasks -->
                <div class="lg:col-span-2">
                    <x-card title="Assigned Tasks">
                        @if($user->tasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($user->tasks as $task)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                                <p class="text-sm text-gray-600">{{ $task->project->name }}</p>
                                            </div>
                                            @php
                                                $statusColors = [
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    'In Progress' => 'bg-blue-100 text-blue-800',
                                                    'Completed' => 'bg-green-100 text-green-800'
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$task->status] }}">
                                                {{ $task->status }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-700 mb-3">{{ Str::limit($task->description, 100) }}</p>

                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-500">Due: {{ $task->due_date->format('M d, Y') }}</span>
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                View Task →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <p class="text-gray-500">No tasks assigned yet.</p>
                                @if($canManage)
                                    <a href="{{ route('tasks.create') }}"
                                        class="text-indigo-600 hover:text-indigo-900 mt-2 inline-block">
                                        Create a task for this member
                                    </a>
                                @endif
                            </div>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>