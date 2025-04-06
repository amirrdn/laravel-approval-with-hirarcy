<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Role Baru</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg rounded-lg border border-gray-100">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="'Nama Role'" class="text-gray-700 font-medium" />
                            <x-text-input id="name" name="name" type="text" 
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                required autofocus placeholder="Masukkan nama role" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label :value="'Permissions'" class="text-gray-700 font-medium mb-3" />
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-white transition duration-150">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <x-input-label for="parent" :value="'Parent Role (opsional)'" class="text-gray-700 font-medium" />
                            <select name="parent" id="parent" 
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="0">Tidak ada (Root Role)</option>
                                @php $classess->renderRoleOptions($role, '', ''); @endphp
                            </select>
                            <x-input-error :messages="$errors->get('parent')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('roles.index') }}" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </a>
                        <x-primary-button class="px-4 py-2">
                            Simpan Role
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
