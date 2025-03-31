<?php

namespace Xentixar\FilamentComment\Models\Traits;

use Xentixar\FilamentComment\Models\FilamentComment;

trait HasFilamentComment
{
    public function comments()
    {
        return $this->morphMany(FilamentComment::class, 'commentable');
    }
}