<?php

namespace App\Models;

use App\Enums\UserStoryStatus;
use Database\Factories\UserStoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['story', 'status', 'project_id', 'user_id'])]
class UserStory extends Model
{
    /** @use HasFactory<UserStoryFactory> */
    use HasFactory;

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'status' => UserStoryStatus::class,
        ];
    }
}
