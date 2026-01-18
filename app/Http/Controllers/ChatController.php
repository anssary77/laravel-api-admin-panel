<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\ChatMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ChatController extends Controller
{
    /**
     * Display a listing of conversations or the chat interface.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin sees list of users who have sent messages
            $users = User::where('role', 'user')->get();
            return view('admin.chat.index', compact('users'));
        }
        
        // User sees chat with support (admins)
        return view('user.chat.index');
    }

    /**
     * Get messages for a specific conversation.
     */
    public function getMessages($userId = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            if (!$userId) return response()->json([]);
            
            $messages = ChatMessage::where(function($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', Auth::id());
            })->orWhere(function($q) use ($userId) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')->get();
            
            // Mark as read
            ChatMessage::where('sender_id', $userId)->where('receiver_id', Auth::id())->update(['is_read' => true]);
            
            return response()->json($messages);
        }
        
        // User side: messages between user and ANY admin
        // For simplicity, we'll just show all messages where sender or receiver is this user
        // and the other party is an admin.
        $messages = ChatMessage::where(function($q) use ($user) {
            $q->where('sender_id', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        // Mark as read
        ChatMessage::where('receiver_id', $user->id)->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'nullable|uuid|exists:users,id'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $receiverId = $request->receiver_id;

        if ($user->role === 'user') {
            // If user sending, receiver is an admin. We'll pick the first admin for now
            // or just use a generic "admin" receiver ID if we had one.
            // Let's find an admin.
            $admin = User::where('role', 'admin')->first();
            $receiverId = $admin->id;
        }

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        // Notify the receiver
        $receiver = User::find($receiverId);
        if ($receiver) {
            $receiver->notify(new ChatMessageNotification($message));
        }

        return response()->json($message);
    }
}
