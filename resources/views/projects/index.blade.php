<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <a href="{{ route('projects.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                    + Create New Project
                </a>
                <a target="_blank" href="{{ route('export.projects') }}"
                   class="ml-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                    Export Project
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                <table id="projects-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Assigned To</th>
                            <th>Creator</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

                <div class="mt-4 p-4">
                    {{ $projects->links() }}
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        #projects-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        #projects-table thead th {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: none;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }
        
        #projects-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            transition: background-color 0.2s ease;
        }
        
        #projects-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        
        #projects-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .dataTables_wrapper .dataTables_length select {
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
            background-color: white;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            appearance: none;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
            margin-left: 0.5rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .dataTables_wrapper .dataTables_info {
            color: #64748b;
            padding-top: 1rem;
            font-size: 0.875rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
            background-color: white;
            color: #475569;
            transition: all 0.2s ease;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
            font-weight: 600;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Custom scrollbar for the table */
        .dataTables_scrollBody::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        $(document).ready(function () {
            $('#projects-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("projects.index") }}',
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
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[1, 'asc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                    { data: 'name', name: 'name', width: '20%' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false, width: '10%' },
                    { data: 'start_date', name: 'start_date', width: '15%' },
                    { data: 'end_date', name: 'end_date', width: '15%' },
                    { data: 'assigned_to', name: 'assigned_to', width: '15%' },
                    { data: 'creator', name: 'creator', width: '10%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '10%' },
                ],
                responsive: true,
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                drawCallback: function() {
                    $('.dataTables_paginate .pagination').addClass('flex space-x-2');
                    $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 rounded border border-gray-300 hover:bg-blue-600 hover:text-white transition-colors duration-200');
                    $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-600 text-white border-blue-600');
                }
            });
        });
        function confirmDelete(projectId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + projectId).submit();
                }
            })
        }
    </script>
@endpush
</x-app-layout>
