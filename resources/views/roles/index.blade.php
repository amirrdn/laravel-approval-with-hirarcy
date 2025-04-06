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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @php
                function renderRoleTree($roles, $level = 0) {
                    echo '<ul class="'.($level === 0 ? 'space-y-3' : 'ml-6 mt-2 space-y-2').'">';
                    foreach ($roles as $role) {
                        echo '<li class="relative pl-5">';
                        echo '<div class="flex items-start group hover:bg-gray-50 rounded-lg p-2">';
                        if ($level > 0) {
                            echo '<div class="absolute left-0 -ml-px h-full w-0.5 bg-gray-200"></div>';
                        }
                        echo '<div class="absolute left-0 top-3 -ml-px h-3 w-3 rounded-full border-2 border-indigo-500 bg-white"></div>';
                        
                        echo '<div class="flex-1">';
                        echo '<div class="flex items-center space-x-3">';
                        echo '<span class="text-gray-900 font-medium">' . $role->name . '</span>';
                        echo '<div class="flex flex-wrap gap-1">';
                        foreach ($role->permissions as $permission) {
                            echo '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">'
                                . $permission->name . '</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                        
                        if (auth()->user()->can('manage roles')) {
                            echo '<div class="mt-2 flex space-x-3">';
                            echo '<a href="'.route('roles.edit', $role->id).'" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</a>';
                            echo '<form action="'.route('roles.destroy', $role->id).'" method="POST" class="inline-block" onsubmit="return confirm(\'Yakin ingin hapus role ini?\')">';
                            echo csrf_field() . method_field('DELETE');
                            echo '<button type="submit" class="text-sm text-red-600 hover:text-red-800">Hapus</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                        
                        if ($role->children && $role->children->count()) {
                            renderRoleTree($role->children, $level + 1);
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                @endphp
                @php renderRoleTree($roles); @endphp
            </div>
        </div>
    </div>
</x-app-layout>
