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
    /*
    |--------------------------------------------------------------------------
    | Mention Column
    |--------------------------------------------------------------------------
    | The column that will be used to mention the user
    |
    */
    'mention_column' => 'username',

    /*
    |--------------------------------------------------------------------------
    | Send Notifications
    |--------------------------------------------------------------------------
    | Whether to send notifications when a user is mentioned
    |
    */
    'send_notifications' => true,

    /*
    |--------------------------------------------------------------------------
    | Mention Notification Title
    |--------------------------------------------------------------------------
    | The title of the notification that will be sent when a user is mentioned
    */
    'mention_notification_title' => 'mentioned in a comment!'
];
