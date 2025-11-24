<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Team Members') }}
            </h2>
            @if($canManage)
                <a href="{{ route('team-members.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Team Member
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

            <x-card>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($users as $user)
                        <div
                            class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                            <div class="flex items-center space-x-4">
                                @if($user->teamMember && $user->teamMember->profile_photo)
                                    <img src="{{ asset('storage/' . $user->teamMember->profile_photo) }}" alt="{{ $user->name }}"
                                        class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-500 text-lg font-medium">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        {{ $user->getRole() }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium">{{ $user->tasks_count }}</span> tasks assigned
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('team-members.show', $user) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                    @if($canManage && $user->teamMember)
                                        <a href="{{ route('team-members.edit', $user->teamMember) }}"
                                            class="text-yellow-600 hover:text-yellow-900 text-sm">Edit</a>
                                        <form action="{{ route('team-members.destroy', $user->teamMember) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure? This will also delete all assigned tasks.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-500 mb-4">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                    </path>
                                </svg>
                                No team members found.
                            </div>
                            @if($canManage)
                                <a href="{{ route('team-members.create') }}" class="text-blue-600 hover:text-blue-900">Add your
                                    first team member</a>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>