<?php

namespace Xentixar\FilamentComment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xentixar\FilamentComment\Enums\FilamentCommentActivityType;

/**
 * @property int $id
 * @property int $user_id
 * @property int $comment_id
 * @property FilamentCommentActivityType $activity_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class FilamentCommentActivity extends Model
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('filament-comments.comment_activity_table', 'comment_activities'));
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filament-comments.user_model', \App\Models\User::class));
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(FilamentComment::class, 'comment_id');
    }
}
