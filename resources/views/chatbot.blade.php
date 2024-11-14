<x-app-layout>
    <div class="py-12 px-16">
        <div class="flex flex-col md:flex-row w-auto">
            <!-- Contacts Sidebar -->
            <!-- <div class="md:flex flex-col w-full md:w-1/4 bg-white p-4 border-r">
                <input type="text" placeholder="Search Messages" class="mb-4 p-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                <div class="space-y-4">
                    <div class="border-b pb-3 cursor-pointer">
                        <p class="font-semibold">ChatBotani</p>
                        <p class="text-sm text-gray-600">Start a new chat with Gemini</p>
                    </div>
                </div>
            </div> -->

            <!-- Chat Area -->
            <div class="flex flex-col w-full h-full flex-grow bg-gray-50">
                <div class="flex items-center justify-between p-4 bg-gray-100 border-b">
                    <div>
                        <p class="font-bold">ChatBotani</p>
                        <p class="text-sm text-gray-500">Active now</p>
                    </div>
                </div>
                <div class="flex-grow p-4 overflow-y-auto" id="chat" style="max-height: calc(100vh - 200px);">
                    <div class="space-y-4">
                        <!-- Chat messages will be appended here -->
                    </div>
                </div>
                <div class="p-4 border-t bg-white">
                    <form id="ask" class="flex">
                        <input type="text" placeholder="Write a message..." class="w-full p-3 border rounded-full focus:ring-2 focus:ring-green-500 outline-none" id="question" name="question" required>
                        <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">Send</button>
                    </form>
                    <div class="text-red-500 text-sm mt-2" id="question_help"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#chat').html('');
        });

        let baseUrl = {!! json_encode(url('/')) !!};

        $('#ask').submit(function(event) {
            event.preventDefault();
            let form = $('#ask')[0];
            let formData = new FormData(form);

            $.ajax({
                url: baseUrl + '/question',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    refresh();

                    let userMessage = `<div class="flex justify-end mb-2">
                                          <div class="bg-blue-500 text-white p-3 rounded-lg max-w-[70%]">${data.question}</div>
                                       </div>`;
                    let botResponse = `<div class="flex justify-start mb-2">
                                          <div class="bg-gray-200 p-3 rounded-lg max-w-[70%]">${data.answer}</div>
                                       </div>`;

                    $('#chat').append(userMessage);
                    $('#chat').append(botResponse);
                    $('#chat').scrollTop($('#chat')[0].scrollHeight); // Auto-scroll to the bottom
                },
                error: function(reject) {
                    refresh();
                    if (reject.status === 422) {
                        let errors = $.parseJSON(reject.responseText);
                        $.each(errors.errors, function(key, value) {
                            $('#' + key + '_help').text(value[0]);
                        });
                    }
                }
            });
        });

        function refresh() {
            $('#question_help').text('');
        }
    </script>
</x-app-layout>
