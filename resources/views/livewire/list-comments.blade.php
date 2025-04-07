<div>
    @if ($comments->isNotEmpty())
        @foreach ($comments as $comment)
            @livewire('comment', ['comment' => $comment, 'pagination' => true], key($comment->id))
        @endforeach
        <div class="flex justify-start gap-2 items-center">
            @if ($showLess)
                <button type="button" class="group relative inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-indigo-600 rounded-md shadow-sm hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out" wire:click="loadLess">
                    <span class="me-1">Show Less</span>
                    <x-filament::icon icon="heroicon-o-chevron-up" class="w-4 h-4 inline transition-transform duration-200 group-hover:-translate-y-0.5" wire:loading.remove wire:target="loadLess" />
                </button>
            @endif
            @if ($showMore)
                <button type="button" class="group relative inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-pink-500 rounded-md shadow-sm hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-all duration-200 ease-in-out" wire:click="loadMore">
                    <span class="me-1">Show More</span>
                    <x-filament::icon icon="heroicon-o-chevron-down" class="w-4 h-4 inline transition-transform duration-200 group-hover:translate-y-0.5" wire:loading.remove wire:target="loadMore" />
                </button>
            @endif
        </div>
    @endif
    @if ($comments->isEmpty())
        <p class="text-center">No comments available.</p>
    @endif
    @livewire('add-comment', ['record' => $record])
</div>
