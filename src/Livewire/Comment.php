<?php

namespace Xentixar\FilamentComment\Livewire;

use App\Models\User;
use DOMDocument;
use DOMXPath;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Xentixar\FilamentComment\Enums\FilamentCommentActivityType;
use Xentixar\FilamentComment\Models\FilamentComment;
use Illuminate\Support\Str;

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
            return 'https://ui-avatars.com/api/?background=000&color=fff&name=' . str_replace(' ', '+', $user->name);
        }
    }

    public function render(): View
    {
        return view('filament-comments::livewire.comment'); // @phpstan-ignore-line
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('body')
                    ->disableGrammarly()
                    ->hiddenLabel(true)
                    ->autofocus()
                    ->placeholder('Write a comment...')
                    ->label('Content')
                    ->required(),
            ])
            ->model(FilamentComment::class)
            ->statePath('data')
            ->columns(1);
    }

    public function create(): void
    {
        $this->form->validate(); // @phpstan-ignore-line

        $comment = $this->comment;

        $body = $this->appendMentionToBody($this->data['body']);

        $comment->replies()->create([
            'user_id' => Auth::id(),
            'body' => $body,
            'parent_id' => $this->replyingTo,
            'commentable_id' => $comment->commentable_id,
            'commentable_type' => $comment->commentable_type,
        ]);

        Notification::make()
            ->title('Comment added')
            ->success()
            ->send();

        if (config('filament-comments.send_notifications')) {
            $this->mentionUser($body);
        }

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

    private function appendMentionToBody(string $body): string
    {
        $mention_column = config('filament-comments.mention_column');
        $mention = "@{$this->comment->user->{$mention_column}}";

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $pTags = $dom->getElementsByTagName('p');
        if ($pTags->length > 0) {
            $firstP = $pTags->item(0);

            $uTag = $dom->createElement('u', $mention);
            $uTag->setAttribute('class', 'text-primary-500');

            $space = $dom->createTextNode(' ');
            $firstP->insertBefore($space, $firstP->firstChild);
            $firstP->insertBefore($uTag, $space);
        }

        $xpath = new DOMXPath($dom);
        foreach ($xpath->query('//text()') as $textNode) {
            if (preg_match_all('/@[\w.]+/', $textNode->nodeValue, $matches)) {
                $parent = $textNode->parentNode;
                $newFragment = $dom->createDocumentFragment();
                $parts = preg_split('/(@[\w.]+)/', $textNode->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE);

                foreach ($parts as $part) {
                    if (preg_match('/^@[\w.]+$/', $part)) {
                        $partWithoutAt = str_replace('@', '', $part);
                        $user = User::query()->where($mention_column, $partWithoutAt)->first();

                        if ($user) {
                            $u = $dom->createElement('u', $part);
                            $u->setAttribute('class', 'text-primary-500');
                            $newFragment->appendChild($u);
                        } else {
                            $newFragment->appendChild($dom->createTextNode($part));
                        }
                    } else {
                        $newFragment->appendChild($dom->createTextNode($part));
                    }
                }

                $parent->replaceChild($newFragment, $textNode);
            }
        }

        $html = $dom->saveHTML();
        $start = strpos($html, '<body>') + 6;
        $end = strpos($html, '</body>');

        return trim(substr($html, $start, $end - $start));
    }

    private function mentionUser(string $body): void
    {
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $textContent = $dom->textContent;

        preg_match_all('/@([\w.]+)/', $textContent, $matches);

        $usernames = $matches[1];

        $mentions = [];

        $authUser = Auth::user();

        $mention_column = config('filament-comments.mention_column');

        foreach ($usernames as $username) {
            $user = User::query()->where($mention_column, $username)->first();

            if ($user && $user->id !== $authUser->id) {
                $mentions[] = $user;
            }
        }

        $mentions = collect($mentions)->unique();

        $mention_notification_title = config('filament-comments.mention_notification_title');

        Notification::make()
            ->title("{$authUser->name} {$mention_notification_title}")
            ->body(Str::limit($this->comment->body, 200))
            ->success()
            ->sendToDatabase($mentions);
    }
}
