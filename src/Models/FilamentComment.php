<?php

namespace Xentixar\FilamentComment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(config('filament-comments.user.model', \App\Models\User::class));
    }

    public function parent()
    {
        return $this->belongsTo(FilamentComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(FilamentComment::class, 'parent_id');
    }

    public function activities()
    {
        return $this->hasMany(FilamentCommentActivity::class, 'comment_id');
    }

    public function addActivity($type)
    {
        $activities = $this->activities();

        if($this->getActivityType() !== $type) {
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

    public function getActivityType()
    {
        return $this->activities()->where('user_id', auth()->id())->first()?->activity_type->value ?? '';
    }
}
