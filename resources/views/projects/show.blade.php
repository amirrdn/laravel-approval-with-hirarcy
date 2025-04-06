<x-app-layout>
    @push('styles')
    <style>
        label::after{
            content: ":";
            float: right;
        }
    </style>
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project Details - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Detail Project -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Project Information</h3>
                @php
                    $gridCols = $project->status == 3 || $project->projectUser->user_id == auth()->user()->id  ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2';
                @endphp
                <div class="grid {{ $gridCols }} gap-x-8 gap-y-4 text-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Name</label>
                            <input type="text" name="name_v" id="name_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                                    value="{{ $project->name }}" disabled>
                        </div>
                    
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Status</label>
                                @php
                                    $statusText = match($project->status) {
                                        1 => 'Active',
                                        3 => 'Completed',
                                        4 => 'Rejected',
                                        default => 'Not Active',
                                    };
                                @endphp
                                <input type="text" name="status_v" id="status_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                                    value="{{ $statusText }}" disabled>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Creator</label>
                            <input type="text" name="name_v" id="name_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                                    value="{{ $project->users[0]->name. ' ('.$project->users[0]->role->name.')' }}" disabled>
                        </div>
                        @php
                            $properties = json_decode($lastActivity?->properties, true);
                            $oldAssigned = $properties['old']['assigned_to'] ?? '-';
                        @endphp
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Current Task</label>
                            <input type="text" name="name_v" id="name_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                                    value="{{ $oldAssigned }}" disabled>
                        </div>
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Start Date</label>
                            <input type="text" name="name_v" id="name_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                                    value="{{ $project->start_date }}" disabled>
                        </div>
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">End Date</label>
                            <input type="text" name="name_v" id="name_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                                    value="{{ $project->end_date }}" disabled>
                        </div>
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Description</label>
                            <textarea type="text" name="name_v" id="name_v"
                                    class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800"
                            disabled>{{$project->description}}</textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <label for="status" class="text-sm font-medium text-gray-700 w-1/2">Attachment</label>
                            @if ($project->attachment)
                                <a href="{{ asset('storage/' . $project->attachment) }}" class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-blue-600 underline" target="_blank">Download PDF</a>
                            @else
                                <span class="pl-0 ml-1 w-1/2 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:border-indigo-500 text-sm text-gray-800">No file</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if(($project->projectUser->user_id !== auth()->user()->id || $project->status === 4) && $project->status !== 3)
                        <form method="POST" action="{{ route('users.assign.form', $project->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="name" value="{{$project->name}}" />
                            <input type="hidden" name="description" value="{{$project->description}}" />
                            <input type="hidden" name="start_date" value="{{$project->start_date}}" />
                            <input type="hidden" name="end_date" value="{{$project->end_date}}" />
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Assign Users</label>
                                <select name="assigned_to[]" multiple
                                class="select2 mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @if(count($users) > 0)
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->role->name }})
                                        </option>
                                    @endforeach
                                @else
                                @foreach($project->users as $user)
                                
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                                @endif
                                </select>
                                @error('assigned_to') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status"
                                class="select2 mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @if($project->status === 4)
                                <option value="">Chose Option</option>
                                <option value="5">Revisi</option>
                                @else
                                    @if(count($users) > 0 )
                                    <option value="2">Accept</option>
                                    @else
                                    <option value="3">Complated</option>
                                    @endif
                                    <option value="4">Reject</option>
                                @endif
                                </select>
                                @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <x-primary-button>Update Project</x-primary-button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            

            <!-- Audit Trail -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Histories</h3>
                @if ($activities->isEmpty())
                    <p class="text-gray-500">No changes recorded yet.</p>
                @else
                    <div class="overflow-auto">
                        <table class="table-auto w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-100 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-2">Event</th>
                                    <th class="px-4 py-2">User</th>
                                    <th class="px-4 py-2">Old Values</th>
                                    <th class="px-4 py-2">New Values</th>
                                    <th class="px-4 py-2">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $activity)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ ucfirst($activity->event) }}</td>
                                        <td class="px-4 py-2">
                                            {{ optional($activity->causer)->name ?? 'System' }}
                                        </td>
                                        <td class="px-4 py-2 text-xs">
                                            <pre class="whitespace-pre-wrap">
                                                {{ json_encode($activity->properties['old'] ?? [], JSON_PRETTY_PRINT) }}
                                            </pre>
                                        </td>
                                        <td class="px-4 py-2 text-xs">
                                            <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties['attributes'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                                        </td>
                                        <td class="px-4 py-2">{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
