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
                    <div class="flex gap-3 items-center">
                        {{ $this->likeAction() }}
                        {{ $this->dislikeAction() }}
                        {{ $this->replyAction() }}
                        {{ $this->editAction() }}
                        {{ $this->deleteAction() }}
                    </div>
                </div>

                @if ($this->replyingTo === $comment->id || $this->editing === $comment->id)
                    <form class="mt-6 space-y-4 pl-6" wire:submit="{{ $this->editing ? 'update' : 'create' }}">
                        {{ $this->form }}
                        <x-filament::button type="submit">
                            Reply
                        </x-filament::button>
                        <x-filament::button color="danger" type="button" wire:click="cancel">
                            Cancel
                        </x-filament::button>
                    </form>
                @endif

                @if ($hasReplies)
                    @if ($comment->replies->count())
                        <div class="mt-6 space-y-4 pl-6">
                            @foreach ($replies as $reply)
                                @livewire('comment', ['comment' => $reply, 'hasReplies' => false, 'pagination' => false], key($reply->id))
                            @endforeach
                            
                            @if ($pagination)
                                <div class="flex justify-start gap-2 items-center mt-4">
                                    @if ($showLess)
                                        <button type="button" class="group relative inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-secondary-600 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-md shadow-sm hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out" wire:click="loadLess">
                                            <span class="me-1">Show Less</span>
                                            <x-filament::icon icon="heroicon-o-chevron-up" class="w-4 h-4 inline transition-transform duration-200 group-hover:-translate-y-0.5" wire:loading.remove wire:target="loadLess" />
                                        </button>
                                    @endif
                                    @if ($showMore)
                                        <button type="button" class="group relative inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-secondary-600 bg-gradient-to-r from-purple-500 to-pink-500 rounded-md shadow-sm hover:from-purple-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-all duration-200 ease-in-out" wire:click="loadMore">
                                            <span class="me-1">Show More</span>
                                            <x-filament::icon icon="heroicon-o-chevron-down" class="w-4 h-4 inline transition-transform duration-200 group-hover:translate-y-0.5" wire:loading.remove wire:target="loadMore" />
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <x-filament-actions::modals />
</div>
