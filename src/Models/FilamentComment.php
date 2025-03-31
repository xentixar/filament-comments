<?php

namespace Xentixar\FilamentComment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $body
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class FilamentComment extends Model
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();

        $this->setTable(config('filament-comments.comment.table', 'filament_comments'));
    }

    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'body',
        'parent_id',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filament-comments.user.model', \App\Models\User::class));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(FilamentComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(FilamentComment::class, 'parent_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(FilamentCommentActivity::class, 'comment_id');
    }

    public function addActivity(string $type): bool
    {
        $activities = $this->activities();

        if ($this->getActivityType() !== $type) {
            $activities->where('user_id', auth()->id())->delete();
            $activities->create([
                'user_id' => auth()->id(),
                'activity_type' => $type,
            ]);

            return true;
        }
        $activities->where('user_id', auth()->id())->delete();

        return false;
    }

    public function getActivityType(): string
    {
        return $this->activities()->where('user_id', auth()->id())->first()?->activity_type->value ?? '';
    }
}
