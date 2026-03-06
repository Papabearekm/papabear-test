<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Chat</h4>
                {{-- @if(!$newChatMode && !$selectedChat)
                    <button class="btn btn-primary" wire:click="startNewChat">
                        <i class="fa fa-plus"></i> New Chat
                    </button>
                @endif --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Chat List Sidebar -->
                        <div class="col-md-4">
                            <div class="border-end">
                                <!-- Search for New Chat or User Search -->
                                {{-- @if($newChatMode)
                                    <div class="p-3 border-bottom">
                                        <div class="mb-3">
                                            <label class="form-label">Start New Chat</label>
                                            <div class="input-group">
                                                <input type="text" 
                                                       wire:model="searchUsers" 
                                                       wire:keyup="searchUsers" 
                                                       class="form-control" 
                                                       placeholder="Search users...">
                                                <button class="btn btn-outline-secondary" wire:click="clearNewChat">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        @if(count($searchResults) > 0)
                                            <div class="search-results" style="max-height: 300px; overflow-y: auto;">
                                                @foreach($searchResults as $user)
                                                    <div class="d-flex align-items-center p-2 search-result-item" 
                                                         style="cursor: pointer; border-bottom: 1px solid #eee;"
                                                         wire:click="createNewChat({{ $user->id }})">
                                                        <img src="{{ $user->cover ? asset('uploads/' . $user->cover) : asset('assets/images/dummy.jpeg') }}" 
                                                             alt="avatar" 
                                                             class="rounded-circle me-2" 
                                                             width="40" height="40">
                                                        <div>
                                                            <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif --}}

                                <div class="p-3 border-bottom">
                                    <input type="text"
                                        class="form-control"
                                        placeholder="Search chats by name"
                                        wire:model.live="chatSearch">
                                </div>
                                <!-- Existing Chats List -->
                                <div class="chats-list" style="height: 500px; overflow-y: auto;">
                                    @forelse($chats as $chat)
                                        <div class="chat-item d-flex align-items-center p-3 {{ $selectedChat == $chat->message_id ? 'bg-light' : '' }}" 
                                             style="cursor: pointer; border-bottom: 1px solid #eee;"
                                             wire:click="selectChat({{ $chat->message_id }})">
                                            @php
                                                $isReceiver = $chat->sender_id == 1;
                                                $otherUser = $isReceiver ? 
                                                    (object)['id' => $chat->receiver_id, 'name' => $chat->receiver_name, 'last_name' => $chat->receiver_last_name, 'cover' => $chat->receiver_cover, 'type' => $chat->receiver_type] :
                                                    (object)['id' => $chat->sender_id, 'name' => $chat->sender_first_name, 'last_name' => $chat->sender_last_name, 'cover' => $chat->sender_cover, 'type' => $chat->sender_type];
                                            @endphp

                                            <img src="{{ $otherUser->cover ? Storage::disk('spaces')->url($otherUser->cover) : asset('assets/images/dummy.jpeg') }}" 
                                                 alt="avatar" 
                                                 class="rounded-circle me-3" 
                                                 width="50" height="50">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $otherUser->name }} {{ $otherUser->last_name }} [{{$otherUser->type == 'user' ? 'User' : 'Partner'}}]</h6>
                                                        @if($chat->last_message)
                                                            <p class="mb-1 text-muted small" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                {{ $chat->last_message }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($chat->last_message_time)->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-3 text-center text-muted">
                                            <i class="fa fa-comments fa-3x mb-3"></i>
                                            <p>No chats yet</p>
                                            <button class="btn btn-primary" wire:click="startNewChat">Start a conversation</button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages Area -->
                        <div class="col-md-8">
                            @if($selectedChat)
                                <div class="d-flex flex-column" style="height: 500px;">
                                    <!-- Messages Area -->
                                    <div class="messages-area flex-grow-1 p-3" 
                                         style="overflow-y: auto; border: 1px solid #eee;">
                                        @forelse($messages as $message)
                                            <div class="message d-flex mb-3 {{ $message->sender_id == 1 ? 'justify-content-end' : 'justify-content-start' }}">
                                                <div class="{{ $message->sender_id == 1 ? 'text-end' : 'text-start' }}">
                                                    @if($message->sender_id != 1)
                                                        <img src="{{ $message->sender_cover ? Storage::disk('spaces')->url($message->sender_cover) : asset('assets/images/dummy.jpeg') }}" 
                                                             alt="avatar" 
                                                             class="rounded-circle me-2" 
                                                             width="30" height="30">
                                                    @endif
                                                    
                                                    <div class="message-bubble p-3 rounded"
                                                        style="background-color: {{ $message->sender_id == 1 ? '#007bff' : '#e9ecef' }}; color: {{ $message->sender_id == 1 ? 'white' : 'black' }};">
                                                    <div class="message-text">{{ $message->message }}</div>
                                                    <small class="message-time d-block mt-1" style="opacity:.7;">
                                                        {{ \Carbon\Carbon::parse($message->created_at)->setTimezone('Asia/Kolkata')->format('H:i') }}
                                                    </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted mt-5">
                                                <i class="fa fa-comment-dots fa-3x mb-3"></i>
                                                <p>No messages in this conversation</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Message Input -->
                                    <div class="message-input p-3 border-top">
                                        <div class="input-group">
                                            <input type="text" 
                                                   wire:model="newMessage" 
                                                   wire:keydown.enter="sendMessage"
                                                   class="form-control" 
                                                   placeholder="Type a message...">
                                            <button class="btn btn-primary" 
                                                    wire:click="sendMessage"
                                                    @if(empty($newMessage)) disabled @endif>
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @elseif(!$newChatMode)
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center text-muted">
                                        <i class="fa fa-comments fa-4x mb-4"></i>
                                        <h5>Welcome to Chat</h5>
                                        <p>Select a conversation</p>
                                        {{-- <button class="btn btn-primary" wire:click="startNewChat">
                                            <i class="fa fa-plus"></i> Start New Chat
                                        </button> --}}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center text-muted">
                                        <i class="fa fa-search fa-4x mb-4"></i>
                                        <h5>Find a User</h5>
                                        <p>Search for users to start a conversation</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto scroll to bottom on page load
            const messagesArea = document.querySelector('.messages-area');
            if (messagesArea) {
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }

            // Auto scroll on Livewire updates
            Livewire.hook('morph.updated', ({ component }) => {
                const messagesArea = document.querySelector('.messages-area');
                if (messagesArea) {
                    setTimeout(() => {
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }, 100);
                }
            });

            // Auto scroll on component property update
            window.addEventListener('loadMessages', function(e) {
                const messagesArea = document.querySelector('.messages-area');
                if (messagesArea) {
                    setTimeout(() => {
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }, 50);
                }
            });

            // Handle message sending and auto-scroll
            const messageInput = document.querySelector('input[wire\\:model="newMessage"]');
            if (messageInput) {
                messageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        const sendButton = document.querySelector('button[wire\\:click="sendMessage"]');
                        if (sendButton) {
                            setTimeout(() => {
                                const messagesArea = document.querySelector('.messages-area');
                                if (messagesArea) {
                                    messagesArea.scrollTop = messagesArea.scrollHeight;
                                }
                            }, 200);
                        }
                    }
                });
            }
        });
    </script>
    @endpush

    <style>
        .chat-item:hover {
            background-color: #f8f9fa !important;
        }
        
        .search-result-item:hover {
            background-color: #f8f9fa !important;
        }
        
        .message-bubble {
            display: break-word;
        }
        
        .messages-area {
            scrollbar-width: thin;
            scrollbar-color: #ccc #f8f9fa;
        }
        
        .messages-area::-webkit-scrollbar {
            width: 6px;
        }
        
        .messages-area::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        
        .messages-area::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        .chats-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .chats-list::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        
        .chats-list::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .message-text {
            word-break: normal;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }
    </style>
</div>
