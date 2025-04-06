<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Role Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('manage roles')
                <div class="mb-4">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">+ Tambah Role</a>
                </div>
            @endcan

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table-auto w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama Role</th>
                            <th class="px-4 py-2">Permissions</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        function renderRoleRows($roles, $level = 0) {
                            foreach ($roles as $role) {
                                echo '<tr class="border-b '.($level > 0 ? 'bg-gray-50' : '').'">';
                                echo '<td class="px-4 py-2 '.($level > 0 ? 'pl-'.($level * 6) : '').' text-sm text-gray-700">';
                                echo str_repeat('↳ ', $level) . $role->name . '</td>';

                                echo '<td class="px-4 py-2">';
                                foreach ($role->permissions as $permission) {
                                    echo '<span class="inline-block bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded mr-1 mb-1">'
                                        . $permission->name . '</span>';
                                }
                                echo '</td>';

                                echo '<td class="px-4 py-2">';
                                if (auth()->user()->can('manage roles')) {
                                    echo '<a href="'.route('roles.edit', $role->id).'" class="text-indigo-600 hover:text-indigo-900">Edit</a>';
                                    echo '<form action="'.route('roles.destroy', $role->id).'" method="POST" class="inline-block ml-2" onsubmit="return confirm(\'Yakin ingin hapus role ini?\')">';
                                    echo csrf_field() . method_field('DELETE');
                                    echo '<button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>';
                                    echo '</form>';
                                }
                                echo '</td>';
                                echo '</tr>';

                                if ($role->children && $role->children->count()) {
                                    renderRoleRows($role->children, $level + 1);
                                }
                            }
                        }
                    @endphp
                    @php renderRoleRows($roles); @endphp
                    </tbody>
                    
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
