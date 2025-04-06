<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Role Baru</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow rounded-lg">

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                <div class="mb-4">
                    <x-input-label for="name" :value="'Nama Role'" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label :value="'Permissions'" />
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="mr-2">
                                {{ $permission->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="mb-6">
                    <x-input-label for="parent" :value="'Parent Role (opsional)'" />
                    <select name="parent" id="parent" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            <option value="0">Tidak ada (Root Role)</option>
                            @php $classess->renderRoleOptions($role, '', ''); @endphp
                        </select>
                    </select>
                    <x-input-error :messages="$errors->get('parent')" class="mt-2" />
                </div>
                
                <div class="flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-900 bg-red-700 text-white py-3 px-3 rounded-lg">Batal</a>
                    <x-primary-button>Simpan</x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
