<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class ChatbotController extends Controller
{
    protected $chatbot;

    public function __construct(ChatbotService $chatbot)
    {
        $this->chatbot = $chatbot;
    }

    public function index()
    {
        $user = Auth::user();
        return view('chatbot.index', compact('user'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        try {
            $user = Auth::user();

            $result = $this->chatbot->answer(
                $user,
                $request->message
            );

            return response()->json([
                'success' => true,
                'reply' => $result
            ]);

        } catch (Exception $e) {
            Log::error('Gemini Chatbot Error: ' . $e->getMessage());

            $errorMsg = config('app.debug')
                ? ('Lỗi: ' . $e->getMessage())
                : 'Chatbot hiện không thể trả lời. Vui lòng thử lại sau.';

            return response()->json([
                'success' => false,
                'message' => $errorMsg
            ], 500);
        }
    }
}
