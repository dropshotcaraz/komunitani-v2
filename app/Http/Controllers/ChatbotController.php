<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

class ChatbotController extends Controller
{
    public function view()
    {
        return view('chatbot');
    }
    public function index(Request $request)
    {
        $request->validate([
            'question' => 'required',
        ]);
        $question = $request->question;
        $client = new Client(env('GEMINI_API_KEY'));

        $response = $client->geminiPro()->generateContent(
            new TextPart($question),
        );

        $answer = $response->text();
        return response()->json(['question' => $question, 'answer' => $answer]);
    }
}
