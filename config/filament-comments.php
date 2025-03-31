<?php

return [
    'comment' => [
        // The model that will be used to store the comments
        'model' => \Xentixar\FilamentComment\Models\FilamentComment::class,

        // The table name that will be used to store the comments
        'table' => 'filament_comments',
    ],
    'activity' => [
        // The model that will be used to store the comment activities
        'model' => \Xentixar\FilamentComment\Models\FilamentCommentActivity::class,

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
