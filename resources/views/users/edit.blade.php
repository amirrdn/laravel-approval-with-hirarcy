<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="border-b pb-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Informasi User</h3>
                    <p class="mt-1 text-sm text-gray-600">Silakan edit informasi user yang diperlukan.</p>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ $user->name }}" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" required>
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ $user->email }}" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100" required>
                            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="position">Position</label>
                            <div class="relative">
                                <input type="text" name="position" value="{{ old('position', $user->position ?? '') }}"
                                    class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                    transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                    focus:ring-blue-500 focus:border-transparent hover:bg-gray-100">
                            </div>
                            @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="hire_date">Hire Date</label>
                            <input type="date" name="hire_date" 
                                value="{{ old('hire_date', isset($user->hire_date) ? \Carbon\Carbon::parse($user->hire_date)->format('Y-m-d') : '') }}"
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100">
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

                        <div class="col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" 
                                class="select2 w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 bg-gray-50 
                                transition duration-150 ease-in-out focus:outline-none focus:ring-2 
                                focus:ring-blue-500 focus:border-transparent hover:bg-gray-100">
                                <option value="0">Tidak ada (Root Role)</option>
                                @php $classess->renderRoleOptionsUser($roles, $user->role_id, ''); @endphp
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#role').select2({
                theme: 'default',
                width: '100%',
                dropdownCssClass: 'bg-gray-50 text-gray-700',
                selectionCssClass: 'bg-gray-50 text-gray-700',
                containerCssClass: 'border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent hover:bg-gray-100',
            });

            $('.select2-container--default .select2-selection--single').css({
                'height': '41px',
                'padding': '6px',
                'background-color': 'rgb(249 250 251)',
                'border-color': 'rgb(209 213 219)',
                'border-radius': '0.5rem'
            });

            $('.select2-container--default .select2-selection--single .select2-selection__arrow').css({
                'height': '41px'
            });

            $('.select2-dropdown').css({
                'border-color': 'rgb(209 213 219)',
                'border-radius': '0.5rem',
                'margin-top': '4px'
            });

            $('.select2-container--default .select2-results__option--highlighted[aria-selected]').css({
                'background-color': '#2563eb',
                'color': 'white'
            });

            $('.select2-container--default .select2-search--dropdown .select2-search__field').css({
                'border-color': 'rgb(209 213 219)',
                'border-radius': '0.375rem',
                'padding': '0.5rem'
            });
        });
    </script>
    @endpush
</x-app-layout>
