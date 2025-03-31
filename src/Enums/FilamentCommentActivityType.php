<?php

namespace Xentixar\FilamentComment\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum FilamentCommentActivityType: string implements HasColor, HasLabel, HasIcon
{
    case LIKED = 'liked';
    case DISLIKED = 'disliked';

    public function getColor(): string
    {
        return match ($this) {
            self::LIKED => 'success',
            self::DISLIKED => 'danger',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::LIKED => 'Liked',
            self::DISLIKED => 'Disliked',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::LIKED => 'heroicon-o-thumb-up',
            self::DISLIKED => 'heroicon-o-thumb-down',
        };
    }

    public function is(string $activity): bool
    {
        return $this->value === $activity;
    }
}