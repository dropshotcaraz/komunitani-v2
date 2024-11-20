<x-app-layout>
    <div class="bg-gray-100">
        <div class="flex h-[calc(100vh-81px)] border border-gray-200">
            <!-- Sidebar -->
            <div class="w-1/4 bg-white border-r max-h-screen">
                <div class="p-4 max-h-screen">
                    <h2 class="text-xl font-semibold mb-4">Chats</h2>
                    <div class="space-y-4 overflow-y-auto h-[calc(100vh-160px)]">
                        @foreach ($users as $user)
                            <div onclick="window.location='{{ url('/messages/' . $user->id) }}'"
                                @class([
                                    'flex items-center space-x-4 p-3 hover:bg-gray-100 rounded-lg cursor-pointer',
                                    'bg-gray-100' => $user->id === $receiverUser->id,
                                ])>
                                <img src="https://xsgames.co/randomusers/avatar.php?g=pixel" alt="Alice Johnson"
                                    class="w-10 h-10 rounded-full">
                                <div class="flex justify-end">
                                    <div>
                                        <p class="font-medium">{{ str($user->name)->headline() }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ str($user?->lastMessage ?? 'Belum ada pesan')->limit(20) }}</p>
                                    </div>
                                    <!-- <div class="">
                                    <p class="text-sm text-gray-500">{{ $user?->latestReceiverMessage?->created_at->diffForHumans() ?? '-' }} </p>
                                </div> -->
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col">
                <!-- Chat Header -->
                <div class="bg-white p-4 border-b">
                    <h2 class="text-xl font-semibold">
                        <a href="/profile/view/{{ $receiverUser->id }}" class="hover:text-[#6FA843] transition">
                            {{ str($receiverUser->name)->headline() }}
                        </a>
                    </h2>
                </div>

                <!-- Messages -->
                <div class="flex-1 p-4 overflow-y-auto space-y-4 h-[calc(100vh)]">
                    @forelse ($messages as $message)
                        @if ($message->sender_id == auth()->id())
                            <div class="flex items-start justify-end">
                                <div class="bg-blue-500 text-white p-3 rounded-lg max-w-xs">
                                    <p>{{ $message->message }}</p>
                                    <p class="text-xs text-blue-100 mt-1">{{ $message->created_at->isoFormat('HH:mm') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start">
                                <img src="https://xsgames.co/randomusers/avatar.php?g=pixel"
                                    alt="{{ str($receiverUser->name)->headline() }}" class="w-8 h-8 rounded-full mr-2">
                                <div class="bg-green-200 text-black p-3 rounded-lg max-w-xs">
                                    <p>{{ $message->message }}</p>
                                    <p class="text-xs text-black mt-1">
                                        {{ $message->created_at->isoFormat('HH:mm') }}</p>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <p class="text-center text-gray-500">No messages yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="bg-white p-4 border-t">
                    <form action="" method="post">
                        @csrf
                        <div class="flex items-center">
                            <input required type="text" name="message" placeholder="Type a message..."
                                class="flex-1 px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit"
                                class="bg-[#434028] text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
