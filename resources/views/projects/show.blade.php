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
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Informasi Proyek</h3>
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($project->status == 1) bg-green-100 text-green-800
                        @elseif($project->status == 3) bg-blue-100 text-blue-800
                        @elseif($project->status == 4) bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @php
                            $statusText = match($project->status) {
                                1 => 'Aktif',
                                3 => 'Selesai',
                                4 => 'Ditolak',
                                default => 'Tidak Aktif',
                            };
                        @endphp
                        {{ $statusText }}
                    </span>
                </div>
                @php
                    $gridCols = $project->status == 3 || $project->projectUser->user_id == auth()->user()->id  ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2';
                @endphp
                <div class="grid {{ $gridCols }} gap-x-8 gap-y-6">
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Dasar</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-600 w-1/2">Nama Proyek</label>
                                    <span class="text-sm text-gray-800 w-1/2">{{ $project->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-600 w-1/2">Pembuat</label>
                                    <span class="text-sm text-gray-800 w-1/2">{{ $project->users[0]->name }} ({{ $project->users[0]->role->name }})</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-600 w-1/2">Tanggal Mulai</label>
                                    <span class="text-sm text-gray-800 w-1/2">{{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('d F Y, H:i') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-gray-600 w-1/2">Tanggal Selesai</label>
                                    <span class="text-sm text-gray-800 w-1/2">{{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d F Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Deskripsi</h4>
                            <p class="text-sm text-gray-800">{{ $project->description }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Lampiran</h4>
                            @if ($project->attachment)
                                <a href="{{ asset('storage/' . $project->attachment) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                   target="_blank">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </a>
                            @else
                                <span class="text-sm text-gray-500">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    @if(($project->projectUser->user_id !== auth()->user()->id || $project->status === 4) && $project->status !== 3)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Tindakan</h4>
                        <form method="POST" action="{{ route('users.assign.form', $project->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="name" value="{{$project->name}}" />
                            <input type="hidden" name="description" value="{{$project->description}}" />
                            <input type="hidden" name="start_date" value="{{$project->start_date}}" />
                            <input type="hidden" name="end_date" value="{{$project->end_date}}" />
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tugaskan Pengguna</label>
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
                                    @error('assigned_to') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status"
                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @if($project->status === 4)
                                            <option value="">Pilih Opsi</option>
                                            <option value="5">Revisi</option>
                                        @else
                                            @if(count($users) > 0 )
                                                <option value="2">Terima</option>
                                            @else
                                                <option value="3">Selesai</option>
                                            @endif
                                            <option value="4">Tolak</option>
                                        @endif
                                    </select>
                                    @error('status') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Perbarui Proyek
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Riwayat Perubahan</h3>
                @if ($activities->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500">Belum ada riwayat perubahan yang tercatat.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($activities as $activity)
                            <div class="border-l-4 border-indigo-500 pl-4 py-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($activity->event === 'created') bg-green-100 text-green-800
                                            @elseif($activity->event === 'updated') bg-blue-100 text-blue-800
                                            @elseif($activity->event === 'deleted') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($activity->event) }}
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            oleh {{ optional($activity->causer)->name ?? 'System' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $activity->created_at->translatedFormat('d F Y, H:i') }}
                                    </span>
                                </div>
                                
                                @if(isset($activity->properties['old']) || isset($activity->properties['attributes']))
                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if(isset($activity->properties['old']))
                                            <div class="bg-gray-50 p-3 rounded">
                                                <h4 class="text-xs font-semibold text-gray-500 mb-1">Nilai Sebelumnya</h4>
                                                <div class="text-sm">
                                                    @foreach($activity->properties['old'] as $key => $value)
                                                        <div class="mb-1">
                                                            <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                            @if($key === 'status')
                                                                @php
                                                                    $statusLabel = match($value) {
                                                                        1 => 'Aktif',
                                                                        2 => 'Diterima',
                                                                        3 => 'Selesai',
                                                                        4 => 'Ditolak',
                                                                        5 => 'Revisi',
                                                                        default => 'Tidak Aktif',
                                                                    };
                                                                @endphp
                                                                <span class="text-gray-600">{{ $statusLabel }}</span>
                                                            @elseif(in_array($key, ['start_date', 'end_date', 'created_at', 'updated_at']))
                                                                <span class="text-gray-600">{{ \Carbon\Carbon::parse($value)->translatedFormat('d F Y, H:i') }}</span>
                                                            @else
                                                                <span class="text-gray-600">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(isset($activity->properties['attributes']))
                                            <div class="bg-gray-50 p-3 rounded">
                                                <h4 class="text-xs font-semibold text-gray-500 mb-1">Nilai Baru</h4>
                                                <div class="text-sm">
                                                    @foreach($activity->properties['attributes'] as $key => $value)
                                                        <div class="mb-1">
                                                            <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                            @if($key === 'status')
                                                                @php
                                                                    $statusLabel = match($value) {
                                                                        1 => 'Aktif',
                                                                        2 => 'Diterima',
                                                                        3 => 'Selesai',
                                                                        4 => 'Ditolak',
                                                                        5 => 'Revisi',
                                                                        default => 'Tidak Aktif',
                                                                    };
                                                                @endphp
                                                                <span class="text-gray-600">{{ $statusLabel }}</span>
                                                            @elseif(in_array($key, ['start_date', 'end_date', 'created_at', 'updated_at']))
                                                                <span class="text-gray-600">{{ \Carbon\Carbon::parse($value)->translatedFormat('d F Y, H:i') }}</span>
                                                            @else
                                                                <span class="text-gray-600">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
