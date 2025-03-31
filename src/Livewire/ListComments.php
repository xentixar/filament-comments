<?php

namespace Xentixar\FilamentComment\Livewire;

use Livewire\Component;

class ListComments extends Component
{
    public $comments;
    public $record;

    public $offset = 0;
    public $limit = 5;
    public $showMore = false;
    public $showLess = false;

    public $listeners = [
        'refreshComments' => '$refresh',
        'commentAdded' => 'getComments',
    ];

    public function getComments()
    {
        return $this->record->comments()->where('parent_id', null)->with('user')->latest()->get();
    }

    public function mount($record)
    {
        $this->record = $record;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > $this->limit;
        $this->showLess = $this->offset > 0;
    }

    public function loadMore()
    {
        $this->offset += $this->limit;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > ($this->offset + $this->limit);
        $this->showLess = $this->offset > 0;
    }
    
    public function loadLess()
    {
        $this->offset -= $this->limit;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > ($this->offset + $this->limit);
        $this->showLess = $this->offset > 0;
    }

    public function render()
    {
        return view('filament-comments::livewire.list-comments');
    }
}
