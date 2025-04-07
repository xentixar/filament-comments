<div>
    <div class="divide-y divide-gray-300/50 my-4">
        <div class="flex items-start gap-2">
            <div class="flex-shrink-0">
                <img src="{{ $this->getAvatarUrl() }}" alt="{{ $comment->user->name }}"
                    class="w-10 h-10 rounded-full object-cover">
            </div>

            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold dark:text-gray-200 text-gray-900">{{ $comment->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                </div>

                <p class="mt-1 text-gray-600 leading-relaxed">{!! str($comment->body)->sanitizeHtml() !!}</p>
                <div class="mt-2 flex items-center gap-3 text-sm text-gray-600">
                    <div class="flex gap-3">
                        {{ $this->likeAction() }}
                        {{ $this->dislikeAction() }}
                        {{ $this->replyAction() }}
                    </div>
                </div>

                @if ($this->replyingTo === $comment->id)
                    <form class="mt-6 space-y-4 pl-6" wire:submit="create">
                        {{ $this->form }}
                        <x-filament::button type="submit">
                            Reply
                        </x-filament::button>
                        <x-filament::button color="danger" type="button" wire:click="$set('replyingTo', null)">
                            Cancel
                        </x-filament::button>
                    </form>
                @endif

                @if ($hasReplies)
                    @if ($comment->replies->count())
                        <div class="mt-6 space-y-4 pl-6">
                            @foreach ($replies as $reply)
                                @livewire('comment', ['comment' => $reply, 'hasReplies' => false], key($reply->id))
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <x-filament-actions::modals />
</div>
