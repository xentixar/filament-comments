<?php

namespace Xentixar\FilamentComment\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\View\View;
use Livewire\Component;
use Xentixar\FilamentComment\Contracts\Commentable;

class AddComment extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    public ?array $data = [
        'body' => null,
    ];

    public Commentable $record;

    public ?bool $isOpen = false;

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
    
    public function create()
    {
        $this->validate();

        $this->record->comments()->create([
            'body' => $this->data['body'],
            'user_id' => auth()->id(),
        ]);

        $this->reset(['data', 'isOpen']);
        $this->dispatch('commentAdded');
        Notification::make()
            ->title('Comment added')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('filament-comments::livewire.add-comment'); //@phpstan-ignore-line
    }
}
