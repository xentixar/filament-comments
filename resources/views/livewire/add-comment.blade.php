<div class="mt-6">
    <form class="space-y-4">
        {{ $this->form }}
        <x-filament::button class="mt-3" wire:click="create">
            Add Comment
        </x-filament::button>
    </form>
</div>
