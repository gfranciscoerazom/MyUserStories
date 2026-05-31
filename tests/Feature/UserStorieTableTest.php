<?php

use Illuminate\Support\Facades\Schema;

test('user stories table has expected columns', function () {
    expect(Schema::hasColumns('user_stories', [
        'id',
        'story',
        'status',
        'project_id',
        'user_id',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});
