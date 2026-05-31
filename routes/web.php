<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Controllers\UserStorieController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::inertia('dashboard', 'dashboard')->name('dashboard');

        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('projects', [ProjectController::class, 'store'])->name('projects.store')->middleware([HandlePrecognitiveRequests::class]);
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update')->middleware([HandlePrecognitiveRequests::class]);
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        Route::post('projects/{project}/user-stories', [UserStorieController::class, 'store'])->name('projects.user-stories.store')->middleware([HandlePrecognitiveRequests::class]);
        Route::get('projects/{project}/user-stories/{userStorie}', [UserStorieController::class, 'show'])->name('projects.user-stories.show');
        Route::get('projects/{project}/user-stories/{userStorie}/edit', [UserStorieController::class, 'edit'])->name('projects.user-stories.edit');
        Route::put('projects/{project}/user-stories/{userStorie}', [UserStorieController::class, 'update'])->name('projects.user-stories.update')->middleware([HandlePrecognitiveRequests::class]);
        Route::delete('projects/{project}/user-stories/{userStorie}', [UserStorieController::class, 'destroy'])->name('projects.user-stories.destroy');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
});

require __DIR__.'/settings.php';
