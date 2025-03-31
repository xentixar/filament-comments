<?php

namespace Xentixar\FilamentComment\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\View\View;
use Livewire\Component;
use Xentixar\FilamentComment\Enums\FilamentCommentActivityType;
use Xentixar\FilamentComment\Models\FilamentComment;

class Comment extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public ?array $data = [
        'body' => null,
    ];

    public ?int $replyingTo = null;

    public FilamentComment $comment;

    public function mount(FilamentComment $comment): void
    {
        $this->comment = $comment;
    }

    public function getAvatarUrl(): string
    {
        $user = $this->comment->user()->first();

        if (method_exists($user, 'getFilamentAvatarUrl')) {
            return $user->getFilamentAvatarUrl();
        } else {
            return "https://ui-avatars.com/api/?background=000&color=fff&name=" . str_replace(" ", "+", $user->name);
        }
    }

    public function render(): View
    {
        return view('filament-comments::livewire.comment'); //@phpstan-ignore-line
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('body')
                    ->disableGrammarly()
                    ->hiddenLabel(true)
                    ->placeholder('Write a comment...')
                    ->label('Content')
                    ->required(),
            ])
            ->model(FilamentComment::class)
            ->statePath('data')
            ->columns(1);
    }

    public function create(){
        $this->form->validate(); //@phpstan-ignore-line

        $comment = $this->comment;

        $comment->replies()->create([
            'user_id' => auth()->id(),
            'body' => $this->data['body'],
            'parent_id' => $this->replyingTo,
            'commentable_id' => $comment->commentable_id,
            'commentable_type' => $comment->commentable_type,
        ]);

        Notification::make()
            ->title('Comment added')
            ->success()
            ->send();

        $this->reset(['data', 'replyingTo']);
    }

    public function likeAction(): Action
    {
        return Action::make('Like')
            ->link()
            ->label(fn() => $this->comment->activities()->where('activity_type', FilamentCommentActivityType::LIKED->value)->count())
            ->color(fn() => $this->comment->getActivityType() === FilamentCommentActivityType::LIKED->value ? 'success' : 'secondary')
            ->icon('heroicon-s-hand-thumb-up')
            ->action(function () {
                $comment = $this->comment;

                if ($comment->addActivity(FilamentCommentActivityType::LIKED->value)) {
                    Notification::make()
                        ->title('You liked this comment')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('You unliked this comment')
                        ->success()
                        ->send();
                }
            });
    }

    public function dislikeAction(): Action
    {
        return Action::make('Dislike')
            ->link()
            ->label(fn() => $this->comment->activities()->where('activity_type', FilamentCommentActivityType::DISLIKED->value)->count())
            ->color(fn() => $this->comment->getActivityType() === FilamentCommentActivityType::DISLIKED->value ? 'danger' : 'secondary')
            ->icon('heroicon-s-hand-thumb-down')
            ->action(function () {
                $comment = $this->comment;

                if ($comment->addActivity(FilamentCommentActivityType::DISLIKED->value)) {
                    Notification::make()
                        ->title('You disliked this comment')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('You undisliked this comment')
                        ->success()
                        ->send();
                }
            });
    }

    public function replyAction(): Action
    {
        return Action::make('Reply')
            ->link()
            ->tooltip('Reply')
            ->hiddenLabel(true)
            ->icon('heroicon-o-chat-bubble-bottom-center')
            ->action(fn() => $this->replyingTo = $this->comment->id);
    }
}
