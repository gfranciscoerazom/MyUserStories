<?php

namespace App\Enums;

enum UserStorieStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public static function values(): array
    {
        return array_map(static fn (self $status) => $status->value, self::cases());
    }
}
