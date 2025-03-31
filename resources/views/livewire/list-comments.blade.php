<div>
    @foreach ($comments as $comment)
        @livewire('comment', ['comment' => $comment], key($comment->id))
    @endforeach
    @if($showMore)
        <span wire:click="loadMore" class="text-xs cursor-pointer">
            Load More...
        </span>
    @endif
    @if($showLess)
        <span wire:click="loadLess" class="text-xs cursor-pointer">
            Show Less...
        </span>
    @endif
    @if($comments->isEmpty())
        <p class="text-center">No comments available.</p>
    @endif
    @livewire('add-comment', ['record' => $record])
</div>
