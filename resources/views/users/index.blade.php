<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('manage users')
                <div class="flex items-center space-x-2 mb-4">
                    <a href="{{ route('users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-block transition duration-150 ease-in-out flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Tambah User</span>
                    </a>

                    @can('export users')
                        <a target="_blank" href="{{ route('export.users') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 inline-block transition duration-150 ease-in-out flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span>Export</span>
                        </a>
                    @endcan
                </div>
            @endcan

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mb-3 p-3">
                    @can('export users')
                        <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel</label>
                                    <div class="flex items-center space-x-4">
                                        <input type="file" name="file" required 
                                            class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100">
                                        <button type="submit" 
                                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 
                                            transition duration-150 ease-in-out flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Import</span>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Format file yang didukung: .xlsx, .xls</p>
                                </div>
                            </div>
                        </form>
                    @endcan
                </div>

                <div class="p-4">
                    <table id="user-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                console.log("DOM ready, trying DataTables...");
                $('#user-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("datatable.users") }}',
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'roles', name: 'roles', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data yang ditampilkan",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center mt-4"<"flex items-center"i><"flex items-center"p>>',
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    order: [[0, 'asc']],
                    responsive: true,
                    autoWidth: false,
                    drawCallback: function() {
                        $('.dataTables_wrapper .dataTables_length select').addClass('border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm');
                        $('.dataTables_wrapper .dataTables_filter input').addClass('border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm');
                        $('.dataTables_wrapper .dataTables_paginate .paginate_button').addClass('px-3 py-1 rounded-md hover:bg-indigo-50');
                        $('.dataTables_wrapper .dataTables_paginate .paginate_button.current').addClass('bg-indigo-600 text-white');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>


