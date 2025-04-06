<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

use App\Services\ProjectService;
use App\Services\UserService;
class DashboardController extends Controller
{
    public function index(ProjectService $projectService, UserService $userService)
    {
        $projects = $projectService->getAll(10);

        return view('dashboard', compact('projects'));
    }
}
