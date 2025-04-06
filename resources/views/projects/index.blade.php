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
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                        $statusText = match($project->status) {
                                            1 => 'Active',
                                            3 => 'Completed',
                                            4 => 'Rejected',
                                            default => 'Not Active',
                                        };
                                
                                        $badgeClass = match($project->status) {
                                            1 => 'bg-green-100 text-green-800',
                                            3 => 'bg-blue-100 text-blue-800',
                                            4 => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $project->start_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $project->end_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <strong>{{ $project->projectUser?->assigned->name }}</strong>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <strong>{{ $project->projectUser?->creator->name }}</strong>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('projects.show', $project->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                    @if($project->projectUser->creator->id == auth()->id() && $project->status !== 3)
                                    <a href="{{ route('projects.edit', $project->id) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" id="delete-form-{{ $project->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900" onclick="confirmDelete('{{ $project->id }}')">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($projects->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No projects found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mt-4 p-4">
                    {{ $projects->links() }}
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
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
