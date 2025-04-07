<div>
    <form class="mt-6 space-y-4" wire:submit="create">
        {{ $this->form }}
        <x-filament::button type="submit">
            Add Comment
        </x-filament::button>
        <x-filament::button color="danger" type="button" wire:click="$set('isOpen', false)">
            Cancel
        </x-filament::button>
    </form>
</div>
