<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Role</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg rounded-lg transition duration-300 hover:shadow-xl">
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf 
                    @method('PUT')

                    <div class="mb-8">
                        <x-input-label for="name" :value="'Nama Role'" class="text-lg font-medium"/>
                        <x-text-input id="name" name="name" type="text" 
                            class="mt-2 block w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 ease-in-out hover:border-indigo-400"
                            value="{{ old('name', $role->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-8">
                        <x-input-label for="parent" :value="'Parent Role (opsional)'" class="text-lg font-medium"/>
                        @php
                            function renderRoleOptions($roles, $selectedId, $currentId = null, $level = 0) {
                                foreach ($roles as $role) {
                                    if ($role->id === $currentId) continue;

                                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . ($level > 0 ? '↳ ' : '');
                                    $selected = $role->id == $selectedId ? 'selected' : '';

                                    echo "<option value=\"{$role->id}\" {$selected}>{$indent}{$role->name}</option>";

                                    if ($role->children && $role->children->count()) {
                                        renderRoleOptions($role->children, $selectedId, $currentId, $level + 1);
                                    }
                                }
                            }
                        @endphp
                        <select name="parent" id="parent" 
                            class="mt-2 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-200 ease-in-out hover:border-indigo-400">
                            <option value="0">Tidak ada (Root Role)</option>
                            @php renderRoleOptions($allRoles, $role->parent, $role->id); @endphp
                        </select>
                        <x-input-error :messages="$errors->get('parent')" class="mt-2" />
                    </div>
                    
                    <div class="mb-8">
                        <x-input-label :value="'Permissions'" class="text-lg font-medium mb-3"/>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-100 transition duration-200">
                                        <input type="checkbox" name="permissions[]" 
                                            value="{{ $permission->name }}" 
                                            class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                            {{ $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                    </div>

                    <div class="flex justify-between items-center mt-10 pt-6 border-t border-gray-200">
                        <a href="{{ route('roles.index') }}" 
                            class="px-4 py-2 rounded-lg text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition duration-200">
                            ← Kembali
                        </a>
                        <div>
                            <x-primary-button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 transition duration-200 text-base font-medium">
                                Simpan Perubahan
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
