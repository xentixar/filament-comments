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

    public int $offset = 0;
    public int $limit = 5;
    public bool $showMore = false;
    public bool $showLess = false;

    public $listeners = [
        'refreshComments' => '$refresh',
        'commentAdded' => 'reloadComments',
    ];

    /**
     * Refresh the comments list.
     *
     * @return void
     */
    public function reloadComments(): void
    {
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > $this->limit;
        $this->showLess = $this->offset > 0;
    }

    /**
     * Get the comments for the record.
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->record->comments()->where('parent_id', null)->with('user')->latest()->get();
    }

    /**
     * Load more comments.
     *
     * @return void
     */
    public function loadMore(): void
    {
        $this->offset += $this->limit;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > ($this->offset + $this->limit);
        $this->showLess = $this->offset > 0;
    }

    /**
     * Load less comments.
     *
     * @return void
     */
    public function loadLess(): void
    {
        $this->offset -= $this->limit;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > ($this->offset + $this->limit);
        $this->showLess = $this->offset > 0;
    }

    public function mount(Commentable $record): void
    {
        $this->record = $record;
        $totalComments = $this->getComments();
        $this->comments = $totalComments->slice($this->offset, $this->limit);
        $this->showMore = $totalComments->count() > $this->limit;
        $this->showLess = $this->offset > 0;
    }

    public function render(): View
    {
        return view('filament-comments::livewire.list-comments'); //@phpstan-ignore-line
    }
}
