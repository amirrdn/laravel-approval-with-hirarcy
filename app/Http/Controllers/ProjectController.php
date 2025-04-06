<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

use App\Services\ProjectService;
use App\Services\UserService;

use App\Actions\ProjectActions;

use App\Exports\ProjectExport;
use Maatwebsite\Excel\Facades\Excel;

use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProjectService $projectService, UserService $userService)
    {
        if ($request->ajax()) {
            $projects = $projectService->getAll();
            
            return DataTables::of($projects)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($project) {
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
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->addColumn('assigned_to', fn($p) => optional(optional($p->projectUser)->assigned)->name)
                ->addColumn('creator', fn($p) => optional(optional($p->projectUser)->creator)->name)
                ->addColumn('action', function ($project) {
                    $buttons = '<a href="' . route('projects.show', $project->id) . '" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>';
    
                    if (optional($project->projectUser->creator)->id == auth()->id() && $project->status !== 3) {
                        $buttons .= '<a href="' . route('projects.edit', $project->id) . '" class="text-green-600 hover:text-green-900 mr-2">Edit</a>';
                        $buttons .= '
                            <form action="' . route('projects.destroy', $project->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Are you sure?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>';
                    }
    
                    return $buttons;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }
    
        $projects = $projectService->getAll(10);
        $users = $userService->AllUsers();
        return view('projects.index', compact('projects', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ProjectService $projectService)
    {
        $users = $projectService->usersHirarcy();
        return view('projects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProjectActions $projectActions)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'assigned_to' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'attachment' => 'nullable|file|mimes:pdf|between:100,500',
        ]);

        $projectActions->storeProject($request);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ProjectService $projectService)
    {
        $project = $projectService->projectByID($id);
        $activities = $project->activities()->get();
        $lastActivity = $project->activities()->latest()->first();

        $users = $projectService->usersHirarcy();
        
        return view('projects.show', compact('project', 'activities', 'users', 'lastActivity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, ProjectService $projectService)
    {
        $project = $projectService->projectByID($id);
        $currentUser = auth()->user();
        $currentRole = auth()->user()->roles->first();
        $users = $projectService->usersHirarcy();
        $selectedUsers = $project->users->pluck('id')->toArray();
        return view('projects.edit', compact('project', 'users', 'selectedUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ProjectActions $projectActions)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required',
            'assigned_to' => 'required|array',
            'users.*' => 'exists:users,id',
            'attachment' => 'nullable|file|mimes:pdf|between:100,500',
        ]);
        $request->merge([
            'id' => $id
        ]);
        $projectActions->updateProject($request);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ProjectActions $projectActions)
    {
        $projectActions->delete($id);
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
    public function approval(Request $request, string $id, ProjectActions $projectActions)
    {
        $request->validate([
            'description' => 'nullable|string',
            'assigned_to' => 'required|array',
        ]);

        $projectActions->approvalProject($request);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }
    public function export(ProjectService $projectService)
    {
        return Excel::download(new ProjectExport($projectService), 'projects.xlsx');
    }
}
