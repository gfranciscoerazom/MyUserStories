<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStories\StoreUserStoryRequest;
use App\Http\Requests\UserStories\UpdateUserStoryRequest;
use App\Models\Project;
use App\Models\Team;
use App\Models\UserStory;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserStoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserStoryRequest $request, Team $current_team, Project $project)
    {
        DB::transaction(fn () => $project->userStories()->create(
            $request->safe()->merge([
                'user_id' => Auth::id(),
            ])->all()
        ));

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'User story created successfully.',
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    #[Authorize('view', ['userStory'])]
    public function show(Team $current_team, Project $project, UserStory $userStory)
    {
        return Inertia::render('user-stories/show', [
            'project' => $project,
            'user_story' => $userStory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Authorize('update', ['userStory'])]
    public function edit(Team $current_team, Project $project, UserStory $userStory)
    {
        return Inertia::render('user-stories/edit', [
            'project' => $project,
            'user_story' => $userStory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', ['userStory'])]
    public function update(UpdateUserStoryRequest $request, Team $current_team, Project $project, UserStory $userStory)
    {
        $userStory->update($request->safe()->all());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'User story updated successfully.',
        ]);

        return to_route('projects.show', [$current_team, $project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authorize('delete', ['userStory'])]
    public function destroy(Team $current_team, Project $project, UserStory $userStory)
    {
        $userStory->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'User story deleted successfully.',
        ]);

        return to_route('projects.show', [$current_team, $project]);
    }
}
