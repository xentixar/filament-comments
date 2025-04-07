<?php

namespace Xentixar\FilamentComment\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Xentixar\FilamentComment\Contracts\Commentable;

class ListComments extends Component
{
    public Collection $comments;

    public Commentable $record;

    public int $limit = 5;

    public bool $showMore = false;

    public bool $showLess = false;
    
    public int $currentLimit = 5;

    public $listeners = [
        'refreshComments' => '$refresh',
        'commentAdded' => 'reloadComments',
    ];

    /**
     * Refresh the comments list.
     */
    public function reloadComments(): void
    {
        $totalComments = $this->getComments();
        $this->comments = $totalComments->take($this->currentLimit);
        $this->showMore = $totalComments->count() > $this->currentLimit;
        $this->showLess = $this->currentLimit > $this->limit;
    }

    /**
     * Get the comments for the record.
     */
    public function getComments(): Collection
    {
        return $this->record->comments()->where('parent_id', null)->with('user')->latest()->get();
    }

    /**
     * Load more comments.
     */
    public function loadMore(): void
    {
        $this->currentLimit += $this->limit;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->take($this->currentLimit);
        $this->showMore = $totalComments->count() > $this->currentLimit;
        $this->showLess = $this->currentLimit > $this->limit;
    }

    /**
     * Show less comments.
     */
    public function loadLess(): void
    {
        $this->currentLimit = $this->limit;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->take($this->currentLimit);
        $this->showMore = $totalComments->count() > $this->currentLimit;
        $this->showLess = false;
    }

    public function mount(Commentable $record): void
    {
        $this->record = $record;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->take($this->currentLimit);
        $this->showMore = $totalComments->count() > $this->currentLimit;
        $this->showLess = false;
    }

    public function render(): View
    {
        return view('filament-comments::livewire.list-comments'); // @phpstan-ignore-line
    }
}
