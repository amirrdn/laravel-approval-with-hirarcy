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

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectService $projectService, UserService $userService)
    {
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
}
