<?php

namespace Xentixar\FilamentComment\Tables\Actions;

use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;

class CommentPreview extends Action
{
    // protected string $view = 'filament-comments::tables.actions.add-comment';

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
            ->modalContent(function(){
                return view('filament-comments::components.comment-preview', [
                    'record' => $this->getRecord(),
                ]);
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'addComment';
    }

    // public function getCommentable(): mixed
    // {
    //     return $this->getRecord();
    // }

    // public function getCommentableType(): string
    // {
    //     return $this->getRecord()::class;
    // }

    // public function getCommentableId(): int
    // {
    //     return $this->getRecord()->getKey();
    // }

    // public function getCommentableRoute(): string
    // {
    //     return $this->getRecord()::getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    // }
}
