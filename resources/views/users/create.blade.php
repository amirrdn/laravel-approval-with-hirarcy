<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="border-b pb-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Informasi User Baru</h3>
                    <p class="mt-1 text-sm text-gray-600">Silakan isi semua informasi yang diperlukan untuk membuat user baru.</p>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" required>
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" required>
                            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="position">Position</label>
                            <div class="relative">
                                <input type="text" 
                                    class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                    transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                    focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" 
                                    name="position" value="{{ old('position', $user->position ?? '') }}">
                            </div>
                            @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="hire_date">Hire Date</label>
                            <input type="date" name="hire_date" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" 
                                value="{{ old('hire_date', $user->hire_date ?? '') }}">
                            @error('hire_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="is_active">Status</label>
                            <select name="is_active" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100">
                                <option value="1" {{ (old('is_active', $user->is_active ?? '') == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('is_active', $user->is_active ?? '') == 0) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2 border-t pt-6 mt-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Security Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" 
                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                        transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                        focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" required>
                                    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" 
                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                        transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                        focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" required>
                                    @error('password_confirmation') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100">
                                <option value="0">Tidak ada (Root Role)</option>
                                @php $classess->renderRoleOptions($roles, '', ''); @endphp
                            </select>
                            @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('users.index') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm 
                            text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 
                            transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg
                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                            transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Buat User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>