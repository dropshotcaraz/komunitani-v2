<x-app-layout>
    <div class="py-8 rounded-xl shadow-xl px-16 h-[620px] flex flex-col bg-[#F7F0CF]">
        <div class="flex flex-col shadow-xl flex-row w-auto flex-grow"></div>

        <!-- Chat Area -->
        <div class="flex rounded-2xl shadow-xl flex-col w-full h-full flex-grow bg-[#FFFFFF]">
            <div class="flex rounded-2xl items-center justify-between p-4 bg-[#618805] border-b">
                <div>
                    <p class="font-bold text-[#FFFFFF]">ChatBotani</p>
                    <p class="text-sm text-gray-300">Active now</p>
                </div>
            </div>
            <div class="flex-grow p-4 overflow-y-auto" id="chat" style="max-height: calc(100vh - 200px);">
                <div class="space-y-4">
                    <!-- Chat messages will be appended here -->
                </div>
                <div id="loading" class="hidden text-center my-2">
                    <span>Loading...</span>
                    <div class="loader"></div>
                </div>
            </div>
            <div class="p-4 rounded-2xl border-t bg-[#FFFFFF]">
                <form id="ask" class="flex">
                    <input type="text" placeholder="Write a message..." class="w-full p-3 border rounded-full focus:ring-2 focus:ring-[#314502] outline-none" id="question" name="question" required>
                    <button type="submit" class="ml-2 bg-[#434028] hover:bg-[#314502] text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">Send</button>
                </form>
                <div class="text-red-500 text-sm mt-2" id="question_help"></div>
            </div>
        </div>
    </div>

    <style>
        .loader {
            border: 4px solid #f3f3f3; /* Light grey */
            border-top: 4px solid #618805; /* Accent color */
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .dot {
            height: 10px;
            width: 10px;
            margin: 0 3px;
            border-radius: 50%;
            background-color: #618805;
            animation: bounce 0.6s infinite alternate;
        }

        @keyframes bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        /* Chat Bubble Styles */
        .user-message {
            background-color: #434028;
            color: #FFFFFF;
            border-radius: 15px 15px 0 15px;
            animation: fadeInRight 0.3s ease-in-out;
        }

        .bot-response {
            background-color: #F7F0CF;
            color: #314502;
            border-radius: 15px 15px 15px 0;
            animation: fadeInLeft 0.3s ease-in-out;
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>

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

            let userMessage = $('#question').val(); // Get the user input
            if (!userMessage.trim()) return; // Avoid empty submissions

            // Immediately append user's message to chat
            let userMessageHtml = `<div class="flex justify-end mb-2">
                                        <div class="user-message p-3 max-w-[70%]">${userMessage}</div>
                                   </div>`;
            $('#chat').append(userMessageHtml);
            $('#chat').scrollTop($('#chat')[0].scrollHeight); // Auto-scroll to the bottom
            $('#question').val(''); // Clear the input text

            // Show loading and typing indicators
            $('#loading').removeClass('hidden');
            $('#chat').append('<div class="typing-indicator"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>');

            $.ajax({
                url: baseUrl + '/question',
                type: 'POST',
                data: { question: userMessage },
                success: function(data) {
                    $('#loading').addClass('hidden'); // Hide loading indicator
                    $('.typing-indicator').remove(); // Remove typing indicator

                    let botResponse = `<div class="flex justify-start mb-2">
                                            <div class="bot-response p-3 max-w-[70%]">${data.answer}</div>
                                       </div>`;
                    $('#chat').append(botResponse);
                    $('#chat').scrollTop($('#chat')[0].scrollHeight); // Auto-scroll to the bottom
                },
                error: function(reject) {
                    $('#loading').addClass('hidden'); // Hide loading indicator
                    $('.typing-indicator').remove(); // Remove typing indicator
                    if (reject.status === 422) {
                        let errors = $.parseJSON(reject.responseText);
                        $.each(errors.errors, function(key, value) {
                            $('#' + key + '_help').text(value[0]);
                        });
                    }
                }
            });
        });
    </script>
</x-app-layout>
