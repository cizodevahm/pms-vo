<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <a href="{{ route('tasks.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Task
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Your Tasks Section -->
            <x-card title="Your Tasks" class="mb-6">
                <!-- Your Tasks Filter Form -->
                <form method="GET" action="{{ route('tasks.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="user_project_id" class="block text-sm font-medium text-gray-700">Filter by
                            Project</label>
                        <select name="user_project_id" id="user_project_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Your Projects</option>
                            @foreach($userProjects as $project)
                                <option value="{{ $project->id }}" {{ request('user_project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="user_status" class="block text-sm font-medium text-gray-700">Filter by
                            Status</label>
                        <select name="user_status" id="user_status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('user_status') == 'Pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="In Progress" {{ request('user_status') == 'In Progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="Completed" {{ request('user_status') == 'Completed' ? 'selected' : '' }}>
                                Completed</option>
                        </select>
                    </div>

                    <div class="flex items-end mt-3">
                        <x-button type="submit" class="mr-2">Filter</x-button>
                        <a href="{{ route('tasks.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    </div>
                </form>

                <!-- Your Tasks Table -->
                <div class="overflow-x-auto overflow-y-auto max-h-[600px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($userTasks as $task)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $task->due_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="text-indigo-600 hover:text-indigo-900 px-2">View</a>
                                            @if($task->canBeEditedBy(auth()->user()))
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                    class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No tasks assigned to you yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- All Tasks Section -->
            <x-card title="All Tasks" class="mb-6">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('tasks.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700">Filter by
                            Project</label>
                        <select name="project_id" id="project_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end mt-3">
                        <x-button type="submit" class="mr-2">Filter</x-button>
                        <a href="{{ route('tasks.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    </div>
                </form>

                <!-- All Tasks Table -->
                <div class="overflow-x-auto overflow-y-auto max-h-[600px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assigned To</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tasks as $task)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $task->user->role }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $task->due_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="text-indigo-600 hover:text-indigo-900 px-2">View</a>
                                            @if($task->canBeEditedBy(auth()->user()))
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                    class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            @endif
                                            @if(auth()->user()->hasRole(['Project Manager', 'Super Admin']))
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 px-2">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No tasks found. <a href="{{ route('tasks.create') }}"
                                            class="text-blue-600 hover:text-blue-900">Create your first task</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tasks->hasPages())
                    <div class="mt-4">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>