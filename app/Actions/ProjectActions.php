<?php
namespace App\Actions;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;

use Spatie\Activitylog\Models\Activity;
class ProjectActions{

    public function storeProject(object $data)
    {
        $project = new Project();

        $project->id = (string) \Str::uuid();
        $project->name = $data->name;
        $project->description = $data->description;
        $project->start_date = $data->start_date;
        $project->end_date = $data->end_date;
        $project->status = $data->status;

        if ($data->hasFile('attachment')) {
            $project->attachment = $data->file('attachment')->store('attachments', 'public');
        }

        $project->save();
        $assignedTo = $data->input('assigned_to', []);
        $syncData = [];
        foreach ($assignedTo as $userId) {
            $syncData[$userId] = [
                'assigned_to' => $userId,
                'user_id' => auth()->id()
            ];
        }
        
        $project->users()->sync($syncData);

        return $project;
    }
    
    public function updateProject(object $data)
    {
        $project = Project::find($data->id);

        $project->name = $data->name;
        $project->description = $data->description;
        $project->start_date = $data->start_date;
        $project->end_date = $data->end_date;
        $project->status = $data->status;

        if ($data->hasFile('attachment')) {
            $file = $data->file('attachment');
            
            $data->validate([
                'attachment' => 'file|mimes:pdf|between:100,500',
            ]);
            
            $path = $file->store('attachments', 'public');
            
            $project->attachment = $path;
        }
        $project->save();
        
        $assignedTo = $data->input('assigned_to', []);
        $syncData = [];
        foreach ($assignedTo as $userId) {
            $syncData[$userId] = [
                'assigned_to' => $userId,
                'user_id' => $project->users[0]->id
            ];
        }
        $project->users()->sync($syncData);

        return $project;
    }

    public function delete($id)
    {
        $project = Project::find($id);
        $project->delete();

        return 'success';
    }

    public function approvalProject(Request $request)
    {
        $project = Project::with('users')->findOrFail($request->id);

        $project->fill([
            'name' => $project->name,
            'description' => $project->description,
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
            'status' => $request->status,
            'attachment' => $project->attachment,
            'assigned_to' => $request->assigned_to[0]
        ]);

        if ($request->hasFile('attachment')) {
            if ($project->attachment && Storage::disk('public')->exists($project->attachment)) {
                Storage::disk('public')->delete($project->attachment);
            }
            $path = $request->file('attachment')->store('attachments', 'public');
            $project->attachment = $path;
        }

        $project->save();
        
        $syncData = [];
        foreach ($request->assigned_to as $index => $userId) {
            $syncData[$userId] = [
                'assigned_to' => $request->assigned_to[$index] ?? null,
                'user_id' => $project->users[0]->id
            ];
        }

        $project->users()->sync($syncData);

        $changedAssigned = [];
        foreach ($syncData as $userId => $data) {
            $old = $data['user_id'] ?? null;
            $new = $data['assigned_to'];
            $usernew = User::find($new);
            $changedAssigned['old']['assigned_to'] = auth()->user()->name;
            $changedAssigned['attributes']['assigned_to'] = $usernew->name;
        }
        
        $textstatus = match ((int) $request->status) {
            3 => 'Approved',
            2 => 'Pending',
            1 => 'Active',
            0 => 'Not Active',
            default => 'Unknown',
        };
        if (!empty($changedAssigned)) {
            activity('project')
                ->event('change assigned '.$textstatus)
                ->causedBy(auth()->user())
                ->performedOn($project)
                ->withProperties($changedAssigned)
                ->log('Changed assigned_to of project users with status: ' . $textstatus);

            $activity = Activity::all()->last();
            $activity->description = $request->description;
            $activity->save();
        }
        return $project;
    }
}