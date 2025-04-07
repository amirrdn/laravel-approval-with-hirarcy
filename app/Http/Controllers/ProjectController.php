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

use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProjectService $projectService, UserService $userService)
    {
        return Inertia::render('Project/Index',[
            'creator' => auth()->user()->id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ProjectService $projectService)
    {
        $users = $projectService->usersHirarcy();
        return Inertia::render('Project/Create', [
            'users' => $users,
        ]);
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
            'assigned_to' => 'required',
            'users.*' => 'exists:users,id',
            'attachment' => 'nullable|file|mimes:pdf|between:100,500',
        ]);
        $request->merge([
            'assigned_to' => [$request->input('assigned_to')]
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
        $activities = $project->activitiesWithUser()->get();
        $lastActivity = $project->activities()->latest()->first();
        $users = $projectService->usersHirarcy();
        
        return Inertia::render('Project/Show', [
            'project' => $project,
            'users' => $users,
            'activities' => $activities,
            'lastActivity' => $lastActivity,
            'userid' => auth()->user()->id,
            'csrf_token' => csrf_token(),
        ]);
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

        return Inertia::render('Project/Edit', [
            'project' => $project,
            'users' => $users,
            'selectedUsers' => $selectedUsers,
        ]);
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
            'id' => $request->id
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
        return Inertia::render('Project/Index');
    }
    public function approval(Request $request, ProjectActions $projectActions)
    {
        $request->validate([
            'description' => 'nullable|string',
            'assigned_to' => 'required',
        ]);
        $request->merge([
            'id' => $request->id,
            'assigned_to' => [$request->assigned_to]
        ]);
        $projectActions->approvalProject($request);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }
    public function export(ProjectService $projectService)
    {
        return Excel::download(new ProjectExport($projectService), 'projects.xlsx');
    }
    public function datatable(Request $request, ProjectService $projectService, UserService $userService)
    {
        $users = $userService->AllUsers();
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
            
            ->addColumn('action', function ($v) {
                return route('projects.edit', $v->id);
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }
}
