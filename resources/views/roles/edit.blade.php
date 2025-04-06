<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Role</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 shadow-lg rounded-lg">

            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf 
                @method('PUT')

                <!-- Role Name -->
                <div class="mb-6">
                    <x-input-label for="name" :value="'Nama Role'" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        value="{{ old('name', $role->name) }}" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <!-- Parent Role -->
                <div class="mb-6">
                    <x-input-label for="parent" :value="'Parent Role (opsional)'" />                    
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
                    <select name="parent" id="parent" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="0">Tidak ada (Root Role)</option>
                        @php renderRoleOptions($allRoles, $role->parent, $role->id); @endphp
                    </select>
                    <x-input-error :messages="$errors->get('parent')" class="mt-2" />
                </div>
                
                <!-- Permissions -->
                <div class="mb-6">
                    <x-input-label :value="'Permissions'" />
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-3">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center text-sm text-gray-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="mr-2 rounded text-indigo-600"
                                    {{ $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                {{ $permission->name }}
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        Batal
                    </a>
                    <div>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-sm">
                            Update
                        </x-primary-button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
