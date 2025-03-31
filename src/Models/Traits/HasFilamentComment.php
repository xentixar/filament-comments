<?php

namespace Xentixar\FilamentComment\Models\Traits;

trait HasFilamentComment
{
    public function comments()
    {
        return $this->morphMany(config('filament-comments.comment.model'), 'commentable');
    }
}