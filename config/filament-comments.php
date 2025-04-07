<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Comment Table
    |--------------------------------------------------------------------------
    | The table that will be used to store the comments
    |
    */
    'comment_table' => 'comments',

    /*
    |--------------------------------------------------------------------------
    | Comment Activity Table
    |--------------------------------------------------------------------------
    | The table that will be used to store the comment activities
    |
    */
    'comment_activity_table' => 'comment_activities',

    /*
    |--------------------------------------------------------------------------
    | User Table
    |--------------------------------------------------------------------------
    | The table that will be used to bind the comment to a user
    |
    */
    'user_table' => 'users',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    | The model that will be used to bind the comment to a user
    |
    */
    'user_model' => \App\Models\User::class,

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
