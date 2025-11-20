<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            <a href="{{ route('projects.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Project
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

            <x-card>
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
                                                class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
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
                                            <a href="{{ route('projects.edit', $project) }}"
                                                class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No projects found. <a href="{{ route('projects.create') }}"
                                            class="text-blue-600 hover:text-blue-900">Create your first project</a>
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