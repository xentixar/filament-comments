<div>
    @if ($comments->isNotEmpty())
        @foreach ($comments as $comment)
            @livewire('comment', ['comment' => $comment], key($comment->id))
        @endforeach
        <div class="flex justify-end gap-2 items-center">
            @if ($showLess)
                <x-filament::button outlined color="info" size="sm" class="mt-4" wire:click="loadLess">
                    <x-filament::icon icon="heroicon-o-arrow-left" class="w-4 h-4 ml-1 inline" wire:loading.remove
                        wire:target="loadLess" />
                    <span>Previous</span>
                </x-filament::button>
            @endif
            @if ($showMore)
                <x-filament::button outlined color="success" size="sm" class="mt-4" wire:click="loadMore">
                    <span>Next</span>
                    <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4 ml-1 inline" wire:loading.remove
                        wire:target="loadMore" />
                </x-filament::button>
            @endif
        </div>
    @endif
    @if ($comments->isEmpty())
        <p class="text-center">No comments available.</p>
    @endif
    @livewire('add-comment', ['record' => $record])
</div>
