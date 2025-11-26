<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            @if($canManageProjects)
                <a href="{{ route('projects.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Project
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(!$canManageProjects)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Limited Access</p>
                            <p class="text-sm">You don't have direct access to create or manage projects. You can only view
                                projects. Contact your Project Manager or Super Admin for project creation requests.</p>
                        </div>
                    </div>
                </div>
            @endif

            <x-card>
                <!-- Project Manager Filter -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4 items-start">
                    <div class="flex justify-end space-x-4 mb-6">
                        <form method="GET" action="{{ route('projects.index') }}" class="flex items-center space-x-3">
                            <label for="manager_filter" class="text-sm font-medium text-gray-700">Filter by Project
                                Manager:</label>
                            <select name="manager_id" id="manager_filter"
                                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                onchange="this.form.submit()">
                                <option value="">All Project Managers</option>
                                @foreach($projectManagers as $manager)
                                    <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(request('manager_id'))
                                <a href="{{ route('projects.index') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 underline">
                                    Clear Filter
                                </a>
                            @endif
                        </form>
                    </div>

                    @if(request('manager_id'))
                        @php
                            $selectedManager = $projectManagers->find(request('manager_id'));
                        @endphp
                        @if($selectedManager)
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span>Showing projects for:</span>
                                <div class="flex items-center space-x-2">
                                    <div
                                        class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">
                                            {{ substr($selectedManager->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $selectedManager->name }}</span>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Logo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project Manager</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tasks</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($projects as $project)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($project->logo)
                                            <img src="{{ asset('storage/' . $project->logo) }}" alt="{{ $project->name }}"
                                                class="w-16 h-16 rounded-lg object-cover">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-500 text-xs">No Logo</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ Str::limit($project->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($project->manager)
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-white text-xs font-bold">
                                                        {{ substr($project->manager->name, 0, 2) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $project->manager->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $project->manager->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 italic">No manager assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>Start: {{ $project->start_date->format('M d, Y') }}</div>
                                        <div>End: {{ $project->end_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $project->tasks_count }} tasks
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="text-indigo-600 hover:text-indigo-900">View</a>
                                            @if($canManageProjects)
                                                <a href="{{ route('projects.edit', $project) }}"
                                                    class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        @if($canManageProjects)
                                            No projects found. <a href="{{ route('projects.create') }}"
                                                class="text-blue-600 hover:text-blue-900">Create your first project</a>
                                        @else
                                            No projects available to view.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($projects->hasPages())
                    <div class="mt-4">
                        {{ $projects->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>