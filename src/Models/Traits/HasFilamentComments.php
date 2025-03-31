<?php

namespace Xentixar\FilamentComment\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Xentixar\FilamentComment\Models\FilamentComment;

trait HasFilamentComments
{
    /**
     * Get the comments for the model.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(FilamentComment::class, 'commentable');
    }
}
