<div>
    @foreach ($comments as $comment)
        @livewire('comment', ['comment' => $comment], key($comment->id))
    @endforeach
    {{-- @if ($comments->hasMorePages())
        <div class="flex justify-center mt-4">
            <x-filament::button wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore">
                {{ __('Load more') }}
            </x-filament::button>
        </div>
    @endif
    @if ($comments->isEmpty())
        <div class="text-center mt-4">
            <p class="text-gray-500">{{ __('No comments yet.') }}</p>
        </div>
    @endif
    <div wire:loading class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-50">
        <x-filament::loading-indicator />
    </div> --}}
    @livewire('add-comment', ['record' => $record])
</div>
