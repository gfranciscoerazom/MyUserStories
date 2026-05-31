<?php

namespace App\Http\Controllers;

use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Authorize('viewAny', Project::class)]
    public function index()
    {
        $team_projects = Auth::user()
            ->currentTeam
            ->projects()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('projects/index', [
            'team_projects' => Inertia::scroll(fn () => $team_projects),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Authorize('create', Project::class)]
    public function create()
    {
        return Inertia::render('projects/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Authorize('create', Project::class)]
    public function store(StoreProjectRequest $request, Team $current_team)
    {
        DB::transaction(fn () => $current_team
            ->projects()
            ->create(
                $request
                    ->safe()
                    ->merge([
                        'user_id' => Auth::id(),
                    ])->all()
            )
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Project created successfully.',
        ]);

        return to_route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    #[Authorize('view', ['project'])]
    public function show(Team $current_team, Project $project)
    {
        $user_stories = $project
            ->userStories()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('projects/show', [
            'project' => $project,
            'user_stories' => Inertia::scroll(fn () => $user_stories),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Authorize('update', ['project'])]
    public function edit(Team $current_team, Project $project)
    {
        return Inertia::render('projects/edit', [
            'project' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', ['project'])]
    public function update(UpdateProjectRequest $request, Team $current_team, Project $project)
    {
        $project->update($request->safe()->all());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Project updated successfully.',
        ]);

        return to_route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authorize('delete', ['project'])]
    public function destroy(Team $current_team, Project $project)
    {
        $project->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Project deleted successfully.',
        ]);

        return to_route('projects.index');
    }
}
