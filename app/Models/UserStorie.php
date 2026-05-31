<?php

namespace App\Models;

use App\Enums\UserStorieStatus;
use Database\Factories\UserStorieFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['story', 'status', 'project_id', 'user_id'])]
class UserStorie extends Model
{
    /** @use HasFactory<UserStorieFactory> */
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
            'status' => UserStorieStatus::class,
        ];
    }
}
