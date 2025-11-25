<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Super Admin Dashboard') }}
            </h2>
            <span class="text-sm text-gray-600">
                System Overview
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            <!-- Overall Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $totalProjects }}</div>
                        <div class="text-sm text-gray-600">Total Projects</div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $totalTasks }}</div>
                        <div class="text-sm text-gray-600">Total Tasks</div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $totalUsers }}</div>
                        <div class="text-sm text-gray-600">Total Users</div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-orange-600 mb-2">{{ $completedTasks }}</div>
                        <div class="text-sm text-gray-600">Completed Tasks</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <x-card title="Quick Actions">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ route('projects.index') }}"
                        class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <div class="bg-green-500 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">All Projects</h3>
                            <p class="text-sm text-gray-600">View and manage all projects</p>
                        </div>
                    </a>

                    <a href="{{ route('team-members.index') }}"
                        class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <div class="bg-purple-500 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 01-3 0m3 0V9A1.5 1.5 0 0118 7.5M12 4.354a4 4 0 110 5.292m0-5.292a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Team Members</h3>
                            <p class="text-sm text-gray-600">Manage all team members</p>
                        </div>
                    </a>

                </div>
            </x-card>

            <!-- Project Managers Summary -->
            <x-card title="Project Managers Summary">
                @if($projectManagers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Project Manager
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Projects Created
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($projectManagers as $manager)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-white text-sm font-bold">
                                                        {{ substr($manager->name, 0, 2) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $manager->name }}</div>
                                                    <div class="text-sm text-gray-500">Project Manager</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $manager->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($manager->managedProjects && $manager->managedProjects->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach($manager->managedProjects as $project)
                                                        <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                                                <div class="text-xs text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 italic">No projects created</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Project Managers</h3>
                        <p class="text-gray-600">No users with Project Manager role found in the system.</p>
                    </div>
                @endif
            </x-card>

        </div>
    </div>
</x-app-layout>