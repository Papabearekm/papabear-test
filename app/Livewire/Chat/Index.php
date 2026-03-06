<?php

namespace App\Livewire\Chat;

use App\Events\ChatEvent;
use App\Models\ChatRooms;
use App\Models\Conversions;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $chats = [];
    public $selectedChat = null;
    public $messages = [];
    public $newMessage = '';
    public $searchUsers = '';
    public $searchResults = [];
    public $newChatMode = false;

    public string $chatSearch = '';

    public function updatedChatSearch()   // <-- re-run when input changes
    {
        $this->loadChats();
    }

    public function mount()
    {
        $this->loadChats();
    }

    public function loadChats()
    {
        $userId = 1; // use auth()->id() in real code
        $term   = trim($this->chatSearch);

        $this->chats = DB::table('chat_rooms')
        ->select(
            'a.first_name as sender_first_name','a.id as sender_id',
            'b.first_name as receiver_name','b.id as receiver_id',
            'a.last_name as sender_last_name','a.cover as sender_cover','a.type as sender_type',
            'b.last_name as receiver_last_name','b.cover as receiver_cover','b.type as receiver_type',
            'chat_rooms.last_message','chat_rooms.last_message_type',
            'chat_rooms.updated_at','chat_rooms.id as message_id'
        )
        ->join('users as a', 'chat_rooms.sender_id', '=', 'a.id')
        ->join('users as b', 'chat_rooms.receiver_id', '=', 'b.id')

        // 👇 group user condition so it doesn’t interfere with search
        ->where(function ($q) use ($userId) {
            $q->where('chat_rooms.sender_id', $userId)
            ->orWhere('chat_rooms.receiver_id', $userId);
        })

        // 👇 apply search to both sides + last_message
        ->when($term !== '', function ($q) use ($term) {
            $like = "%{$term}%";
            $q->where(function ($qq) use ($like) {
                $qq->where('a.first_name', 'like', $like)
                ->orWhere('a.last_name', 'like', $like)
                ->orWhere('b.first_name', 'like', $like)
                ->orWhere('b.last_name', 'like', $like);
            });
        })
        ->orderBy('chat_rooms.updated_at', 'desc')
        ->get()
        ->toArray();

        // keep your last_message_time attachment + resort
        foreach ($this->chats as $chat) {
            $last_message = Conversions::where('room_id', $chat->message_id)
                ->orderBy('id', 'desc')
                ->first();
            $chat->last_message_time = $last_message ? $last_message->updated_at : $chat->updated_at;
        }

        $this->chats = collect($this->chats)
            ->sortByDesc(fn($c) => \Carbon\Carbon::parse($c->last_message_time))
            ->values()
            ->all();
    }

    public function selectChat($chatId)
    {
        $this->selectedChat = $chatId;
        $this->loadMessages($chatId);
        $this->newChatMode = false;
    }

    public function loadMessages($chatId)
    {
        $this->messages = Conversions::where('room_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) {
                $sender = User::find($message->sender_id);
                $message->sender_name = $sender ? $sender->first_name . ' ' . $sender->last_name : 'Unknown';
                $message->sender_cover = $sender ? $sender->cover : '';
                return $message;
            });
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage)) || !$this->selectedChat) {
            return;
        }

        $message = trim($this->newMessage);
        
        Conversions::create([
            'room_id' => $this->selectedChat,
            'sender_id' => 1,
            'message_type' => 0, // 1 = text message
            'message' => $message,
            'status' => 1,
            'reported' => 0
        ]);

        // Update chat room's last message
        ChatRooms::where('id', $this->selectedChat)->update([
            'last_message' => null,
            'last_message_type' => 0
        ]);
        $room = ChatRooms::find($this->selectedChat);
        $sender_id   = 1;
        $reciever_id = (int) ($room->receiver_id == $sender_id
                                ? $room->sender_id
                                : $room->receiver_id);
        event(new ChatEvent($sender_id, $reciever_id));
        $this->newMessage = '';
        $this->loadMessages($this->selectedChat);
        $this->loadChats(); // Refresh chat list to update last message
        
        // Dispatch event for real-time updates (if using broadcasting)
        $this->dispatch('message-sent');
    }

    public function searchUsers()
    {
        if (strlen($this->searchUsers) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = User::where(function($query) {
            $query->where('first_name', 'like', '%' . $this->searchUsers . '%')
                  ->orWhere('last_name', 'like', '%' . $this->searchUsers . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUsers . '%');
        })
        ->where('id', '!=', 1) // Exclude current user
        ->limit(10)
        ->get();
    }

    public function startNewChat($userId = null)
    {
        $this->newChatMode = true;
        $this->selectedChat = null;
        $this->messages = [];
        if ($userId) {
            $this->searchUsers = User::find($userId)->first_name . ' ' . User::find($userId)->last_name;
        }
    }

    public function createNewChat($receiverId)
    {
        $userId = 1;

        // Check if chat room already exists
        $existingChat = ChatRooms::where(function($query) use ($userId, $receiverId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function($query) use ($userId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $userId);
        })->first();

        if ($existingChat) {
            $this->selectedChat = $existingChat->id;
            $this->loadMessages($existingChat->id);
        } else {
            // Create new chat room
            $chatRoom = ChatRooms::create([
                'sender_id' => $userId,
                'receiver_id' => $receiverId,
                'status' => 1,
                'last_message' => '',
                'last_message_type' => 0
            ]);

            $this->selectedChat = $chatRoom->id;
            $this->messages = collect([]);
        }

        $this->newChatMode = false;
        $this->searchUsers = '';
        $this->searchResults = [];
        $this->loadChats();
    }

    public function clearNewChat()
    {
        $this->newChatMode = false;
        $this->searchUsers = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.chat.index')->extends('layouts.master');
    }
}
