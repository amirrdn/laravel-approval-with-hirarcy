<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <!-- Grid layout with two columns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Name -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" class="w-full border border-gray-300 rounded-lg p-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Position -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700" for="position">Position</label>
                            <input type="text" class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="position" value="{{ old('position', $user->position ?? '') }}">
                            @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Hire Date -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700" for="hire_date">Hire Date</label>
                            <input type="date" name="hire_date" class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('hire_date', $user->hire_date ?? '') }}">
                            @error('hire_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700" for="is_active">Active</label>
                            <select name="is_active" class="w-full p-3 border border-gray-300 rounded-lg mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="1" {{ (old('is_active', $user->is_active ?? '') == 1) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ (old('is_active', $user->is_active ?? '') == 0) ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_active') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" class="w-full border border-gray-300 rounded-lg p-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg p-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('password_confirmation') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                <option value="0">Tidak ada (Root Role)</option>
                                @php $classess->renderRoleOptions($roles, '', ''); @endphp
                            </select>
                            @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-lg text-sm">
                            Back
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
