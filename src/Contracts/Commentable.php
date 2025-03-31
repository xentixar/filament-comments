<?php

namespace Xentixar\FilamentComment\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Commentable
{
    /**
     * Get the comments for the model.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany;
}