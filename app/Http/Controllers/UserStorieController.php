<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStories\StoreUserStorieRequest;
use App\Http\Requests\UserStories\UpdateUserStorieRequest;
use App\Models\Project;
use App\Models\Team;
use App\Models\UserStorie;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserStorieController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserStorieRequest $request, Team $current_team, Project $project)
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
    #[Authorize('view', ['userStorie'])]
    public function show(Team $current_team, Project $project, UserStorie $userStorie)
    {
        return Inertia::render('user-stories/show', [
            'project' => $project,
            'user_storie' => $userStorie,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Authorize('update', ['userStorie'])]
    public function edit(Team $current_team, Project $project, UserStorie $userStorie)
    {
        return Inertia::render('user-stories/edit', [
            'project' => $project,
            'user_storie' => $userStorie,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', ['userStorie'])]
    public function update(UpdateUserStorieRequest $request, Team $current_team, Project $project, UserStorie $userStorie)
    {
        $userStorie->update($request->safe()->all());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'User story updated successfully.',
        ]);

        return to_route('projects.show', [$current_team, $project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authorize('delete', ['userStorie'])]
    public function destroy(Team $current_team, Project $project, UserStorie $userStorie)
    {
        $userStorie->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'User story deleted successfully.',
        ]);

        return to_route('projects.show', [$current_team, $project]);
    }
}
