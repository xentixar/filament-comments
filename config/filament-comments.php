<?php

return [
    'comment' => [
        // The table name that will be used to store the comments
        'table' => 'filament_comments',
    ],
    'activity' => [
        // The table name that will be used to store the comment activities
        'table' => 'filament_comment_activities',
    ],
    'user' => [
        // The model that will be used to store the users
        'model' => \App\Models\User::class,

        // The table name that will be used to store the users
        'table' => 'users',
    ],
];
