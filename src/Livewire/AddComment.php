<?php

namespace Xentixar\FilamentComment\Livewire;

use App\Models\User;
use DOMDocument;
use DOMXPath;
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
use Xentixar\FilamentComment\Contracts\Commentable;
use Illuminate\Support\Str;

class AddComment extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public ?array $data = [
        'body' => null,
    ];

    public Commentable $record;

    public function mount(Commentable $record): void
    {
        $this->record = $record;
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
            ])->statePath('data')
            ->columns(1);
    }

    public function create(): void
    {
        $this->validate();

        $body = $this->parseMention($this->data['body']);

        $this->record->comments()->create([
            'body' => $body,
            'user_id' => Auth::id(),
        ]);

        $this->reset(['data']);
        $this->dispatch('commentAdded');
        Notification::make()
            ->title('Comment added')
            ->success()
            ->send();
    }

    private function parseMention(string $body): string
    {
        $mention_column = config('filament-comments.mention_column');

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

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

    public function render(): View
    {
        return view('filament-comments::livewire.add-comment'); // @phpstan-ignore-line
    }
}
