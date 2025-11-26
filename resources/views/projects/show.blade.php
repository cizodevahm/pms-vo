<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.edit', $project) }}"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Project
                </a>
                <a href="{{ route('projects.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Project Details -->
                <div class="lg:col-span-2">
                    <x-card title="Project Details">
                        <div class="space-y-6">
                            <!-- Project Info -->
                            <div class="flex flex-col items-start space-x-4">
                                @if($project->logo)
                                    <img width="300" src="{{ asset('storage/' . $project->logo) }}" alt="{{ $project->name }}"
                                        class="rounded-lg object-cover">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-500 text-sm">No Logo</span>
                                    </div>
                                @endif
                                <div class="flex-1 mt-3">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $project->name }}</h3>
                                    <p class="text-gray-700">{{ $project->description }}</p>
                                </div>
                            </div>

                            <!-- Project Timeline -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                                <div class="flex items-center ">
                                    <label class="block text-sm  font-semibold  text-gray-700 ">Start Date: </label>
                                    <p class="mt-1 text-sm text-gray-900 px-2">{{ $project->start_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="flex items-center ">
                                    <label class="block text-sm font-semibold text-gray-700">End Date: </label>
                                    <p class="mt-1 text-sm text-gray-900 px-2">{{ $project->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <!-- Project Stats -->
                            <div class="flex flex-row flex-wrap  justify-between items-center  pt-6 border-t border-gray-200">
                                @php
                                    $totalTasks = $project->tasks->count();
                                    $pendingTasks = $project->tasks->where('status', 'Pending')->count();
                                    $inProgressTasks = $project->tasks->where('status', 'In Progress')->count();
                                    $completedTasks = $project->tasks->where('status', 'Completed')->count();
                                @endphp

                                <div class="text-center">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $totalTasks }}</div>
                                    <div class="text-sm text-gray-500">Total Tasks</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-semibold text-yellow-600">{{ $pendingTasks }}</div>
                                    <div class="text-sm text-gray-500">Pending</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-semibold text-blue-600">{{ $inProgressTasks }}</div>
                                    <div class="text-sm text-gray-500">In Progress</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-semibold text-green-600">{{ $completedTasks }}</div>
                                    <div class="text-sm text-gray-500">Completed</div>
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <!-- Project Tasks -->
                    <div class="mt-6">
                        <x-card title="Project Tasks">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">All Tasks</h3>
                                <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                                    Add Task
                                </a>
                            </div>

                            @if($project->tasks->count() > 0)
                                <div class="space-y-4">
                                    @foreach($project->tasks as $task)
                                        <div class="border border-gray-200 rounded-lg p-4 mt-3
                                        ">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                                    <p class="text-sm text-gray-600">Assigned to: {{ $task->teamMember->name }}
                                                    </p>
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
                                    <p class="text-gray-500">No tasks created yet.</p>
                                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}"
                                        class="text-indigo-600 hover:text-indigo-900 mt-2 inline-block">
                                        Create the first task for this project
                                    </a>
                                </div>
                            @endif
                        </x-card>
                    </div>
                </div>

                <!-- Project Sidebar -->
                <div class="space-y-6">
                    <!-- Project Meta -->
                    <x-card title="Project Information">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Created:</span>
                                <span class="text-gray-900">{{ $project->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Updated:</span>
                                <span class="text-gray-900">{{ $project->updated_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Duration:</span>
                                <span class="text-gray-900">{{ $project->start_date->diffInDays($project->end_date) }}
                                    days</span>
                            </div>
                            @php
                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-500">Progress:</span>
                                    <span class="text-gray-900">{{ $progress }}%</span>
                                </div>
                                <!-- <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-black h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div> -->
                            </div>
                        </div>
                    </x-card>

                    <!-- Team Members Working on This Project -->
                    <x-card title="Team Members">
                        @php
                            $teamMembers = $project->tasks->map(function ($task) {
                                return $task->teamMember;
                            })->unique('id');
                        @endphp

                        @if($teamMembers->count() > 0)
                            <div class="space-y-3">
                                @foreach($teamMembers as $member)
                                    <div class="flex items-center space-x-3">
                                        @if($member->profile_photo)
                                            <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}"
                                                class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <!-- <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-gray-500 text-xs">{{ substr($member->name, 0, 2) }}</span>
                                            </div> -->
                                        @endif
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $member->role }}</p>
                                        </div>
                                        <a href="{{ route('team-members.show', $member) }}"
                                            class="text-indigo-600 hover:text-indigo-900 text-xs">
                                            View →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No team members assigned yet.</p>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>