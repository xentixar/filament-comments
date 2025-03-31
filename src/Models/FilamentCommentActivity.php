<?php

namespace Xentixar\FilamentComment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xentixar\FilamentComment\Enums\FilamentCommentActivityType;

class FilamentCommentActivity extends Model
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('filament-comments.activity.table', 'filament_comment_activities'));
    }

    protected $fillable = [
        'user_id',
        'comment_id',
        'activity_type',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'comment_id' => 'integer',
        'activity_type' => FilamentCommentActivityType::class,
    ];

    public function user()
    {
        return $this->belongsTo(config('filament-comments.user.model'));
    }

    public function comment()
    {
        return $this->belongsTo(FilamentComment::class, 'comment_id');
    }
}
