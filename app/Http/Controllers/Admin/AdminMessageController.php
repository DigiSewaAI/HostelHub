<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageThread;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        $threads = MessageThread::with(['participants.user', 'latestMessage'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('messages', function ($q) use ($search) {
                    $q->where('body', 'like', "%{$search}%");
                })->orWhere('subject', 'like', "%{$search}%");
            })
            ->orderBy('last_message_at', 'desc')
            ->paginate(15);

        return view('admin.network.messages.index', compact('threads'));
    }

    public function show(MessageThread $thread)
    {
        $thread->load(['participants.user', 'messages.sender']);
        return view('admin.network.messages.show', compact('thread'));
    }

    // Optional: block a user from sending messages
    public function blockSender(Request $request, MessageThread $thread)
    {
        // This could mark a user as blocked (add field to users table)
        // For now, we just soft-delete the thread as a simple measure
        $thread->delete();

        return redirect()->route('admin.network.messages.index')
            ->with('success', 'Message thread removed.');
    }
}
