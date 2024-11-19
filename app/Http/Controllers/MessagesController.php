<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    //
    public function __invoke(): View
    {
        $users = User::query()
            ->whereNot('id', auth()->id())
            ->addSelect([
                'lastMessage' => Message::query()
                    ->select('message')
                    ->orWhere(function ($q) {
                        $q->whereColumn('sender_id', 'users.id');
                        $q->where('receiver_id', auth()->id());
                    })
                    ->orWhere(function ($q) {
                        $q->whereColumn('receiver_id', 'users.id');
                        $q->where('sender_id', auth()->id());
                    })
                    ->latest()
                    ->take(1),
                'lastMessageCreatedAt' => Message::query()
                    ->select('created_at')
                    ->orWhere(function ($q) {
                        $q->whereColumn('sender_id', 'users.id');
                        $q->where('receiver_id', auth()->id());
                    })
                    ->orWhere(function ($q) {
                        $q->whereColumn('receiver_id', 'users.id');
                        $q->where('sender_id', auth()->id());
                    })
                    ->latest()
                    ->take(1),
            ])
            ->orderByDesc('lastMessageCreatedAt')
            ->get();
        // ->sortByDesc(function ($user) {
        //     return $user->latestReceiverMessage?->created_at?->timestamp ?? 0;
        // });

        return view('messages', [
            'users' => $users
        ]);
    }

    public function show(mixed $receiverId)
    {
        $users = User::query()
            ->whereNot('id', auth()->id())
            ->addSelect([
                'lastMessage' => Message::query()
                    ->select('message')
                    ->orWhere(function ($q) {
                        $q->whereColumn('sender_id', 'users.id');
                        $q->where('receiver_id', auth()->id());
                    })
                    ->orWhere(function ($q) {
                        $q->whereColumn('receiver_id', 'users.id');
                        $q->where('sender_id', auth()->id());
                    })
                    ->latest()
                    ->take(1),
                'lastMessageCreatedAt' => Message::query()
                    ->select('created_at')
                    ->orWhere(function ($q) {
                        $q->whereColumn('sender_id', 'users.id');
                        $q->where('receiver_id', auth()->id());
                    })
                    ->orWhere(function ($q) {
                        $q->whereColumn('receiver_id', 'users.id');
                        $q->where('sender_id', auth()->id());
                    })
                    ->latest()
                    ->take(1),
            ])
            ->orderByDesc('lastMessageCreatedAt')
            ->get();

        $receiverUser = User::find($receiverId);

        $messages = Message::query()
            ->orWhere(function ($q) use ($receiverId) {
                $q->where('sender_id', auth()->id())
                    ->where('receiver_id', $receiverId);
            })->orWhere(function ($q) use ($receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', [
            'users' => $users,
            'receiverId' => $receiverId,
            'receiverUser' => $receiverUser,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, mixed $receiverId)
    {
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'message' => $request->message
        ]);

        return redirect(url()->current());
    }
}
