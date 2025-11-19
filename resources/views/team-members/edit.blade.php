<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Team Member: ') . $teamMember->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('team-members.update', $teamMember) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $teamMember->name) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $teamMember->email) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Role</option>
                                <option value="Developer" {{ old('role', $teamMember->role) == 'Developer' ? 'selected' : '' }}>Developer</option>
                                <option value="Designer" {{ old('role', $teamMember->role) == 'Designer' ? 'selected' : '' }}>Designer</option>
                                <option value="QA" {{ old('role', $teamMember->role) == 'QA' ? 'selected' : '' }}>QA
                                </option>
                                <option value="Project Manager" {{ old('role', $teamMember->role) == 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                                <option value="Super Admin" {{ old('role', $teamMember->role) == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Profile Photo -->
                        @if($teamMember->profile_photo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Profile Photo</label>
                                <img src="{{ asset('storage/' . $teamMember->profile_photo) }}" alt="Current Photo"
                                    class="mt-2 w-24 h-24 object-cover rounded-full">
                            </div>
                        @endif

                        <!-- Profile Photo Upload -->
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700">
                                {{ $teamMember->profile_photo ? 'Update Profile Photo' : 'Profile Photo' }}
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Upload a profile photo (JPG, PNG, GIF - Max 2MB)</p>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('team-members.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <x-button type="submit">
                                Update Team Member
                            </x-button>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>