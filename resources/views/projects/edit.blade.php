<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project: ') . $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                    Date</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Logo -->
                        @if($project->logo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Logo</label>
                                <img src="{{ asset('storage/' . $project->logo) }}" alt="Current Logo"
                                    class="mt-2 w-24 h-24 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- Logo Upload -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">
                                {{ $project->logo ? 'Update Logo' : 'Project Logo' }}
                            </label>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('projects.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <x-button type="submit">
                                Update Project
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>