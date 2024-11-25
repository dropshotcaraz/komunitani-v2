<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessagesApiController extends Controller
{
    public function index(): JsonResponse
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

        return response()->json([
            'users' => $users
        ]);
    }

    public function show(mixed $receiverId): JsonResponse
    {
        // $users = User::query()
        //     ->whereNot('id', auth()->id())
        //     ->addSelect([
        //         'lastMessage' => Message::query()
        //             ->select('message')
        //             ->orWhere(function ($q) {
        //                 $q->whereColumn('sender_id', 'users.id');
        //                 $q->where('receiver_id', auth()->id());
        //             })
        //             ->orWhere(function ($q) {
        //                 $q->whereColumn('receiver_id', 'users.id');
        //                 $q->where('sender_id', auth()->id());
        //             })
        //             ->latest()
        //             ->take(1),
        //         'lastMessageCreatedAt' => Message::query()
        //             ->select('created_at')
        //             ->orWhere(function ($q) {
        //                 $q->whereColumn('sender_id', 'users.id');
        //                 $q->where('receiver_id', auth()->id());
        //             })
        //             ->orWhere(function ($q) {
        //                 $q->whereColumn('receiver_id', 'users.id');
        //                 $q->where('sender_id', auth()->id());
        //             })
        //             ->latest()
        //             ->take(1),
        //     ])
        //     ->orderByDesc('lastMessageCreatedAt')
        //     ->get();

        $receiverUser = User::findOrFail($receiverId);

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

        return response()->json([
            'receiver_user' => $receiverUser,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, mixed $receiverId): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'message' => $validated['message']
        ]);

        return response()->json([
            'message' => $message,
            'status' => 'success'
        ], 201);
    }
}