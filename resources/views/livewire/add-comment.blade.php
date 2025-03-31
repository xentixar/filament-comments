<div>
    @if ($isOpen)
        <form class="mt-6 space-y-4" wire:submit="create">
            {{ $this->form }}
            <x-filament::button type="submit">
                Reply
            </x-filament::button>
            <x-filament::button color="danger" type="button" wire:click="$set('replyingTo', null)">
                Cancel
            </x-filament::button>
        </form>
    @else
        <x-filament::button wire:click="$set('isOpen', true)" class="mt-6">
            {{ __('Add a comment') }}
        </x-filament::button>
    @endif
</div>
