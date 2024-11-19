<x-app-layout>
    <div class="bg-gray-100">
        <div class="flex border h-[calc(100vh-81px)] border-gray-200">
            <!-- Sidebar -->
            <div class="w-1/4 bg-white border-r max-h-screen">
                <div class="p-4 max-h-screen">
                    <h2 class="text-xl font-semibold mb-4">Chats</h2>
                    <div class="space-y-4 overflow-y-auto h-[calc(100vh-160px)]">
                        @foreach ($users as $user)
                            <div onclick="window.location='{{ url('/messages/' . $user->id) }}'"
                                class="flex items-center space-x-4 p-3 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="https://via.placeholder.com/40" alt="Alice Johnson"
                                    class="w-10 h-10 rounded-full">
                                <div class="flex justify-end">
                                    <div>
                                        <p class="font-medium">{{ str(string: $user->name)->headline() }}</p>
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
                <!-- No Chat Selected View -->
                <div id="noChatSelected" class="flex-1 flex items-center justify-center bg-gray-50">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No chat selected</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by selecting a chat from the sidebar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
