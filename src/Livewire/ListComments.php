<?php

namespace Xentixar\FilamentComment\Livewire;

use Livewire\Component;

class ListComments extends Component
{
    public $comments;
    public $record;

    public $listeners = [
        'refreshComments' => '$refresh',
        'commentAdded' => 'getComments',
    ];

    public function getComments()
    {
        $this->comments = $this->record->comments()->where('parent_id', null)->with('user')->latest()->get();
    }

    public function mount($record)
    {
        $this->record = $record;
        $this->comments = $record->comments()->where('parent_id', null)->with('user')->latest()->get();
    }

    public function render()
    {
        return view('filament-comments::livewire.list-comments');
    }
}
