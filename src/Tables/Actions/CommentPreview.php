<?php

namespace Xentixar\FilamentComment\Tables\Actions;

use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;

class CommentPreview extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-comments::filament-comments.comment-preview.label'))
            ->tooltip(__('filament-comments::filament-comments.comment-preview.description'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('info')
            ->modal()
            ->slideOver()
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->modalHeading(__('filament-comments::filament-comments.comment-preview.label'))
            ->modalWidth(MaxWidth::FiveExtraLarge)
            ->modalContent(function () {
                return view('filament-comments::components.comment-preview', [ // @phpstan-ignore-line
                    'record' => $this->getRecord(),
                ]);
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'addComment';
    }
}
