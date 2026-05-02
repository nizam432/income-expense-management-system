<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class GeminiController extends Controller
{
    public function ask(Request $request)
    {
        $prompt = $request->query('q'); // query parameter থেকে ইনপুট নেওয়া হচ্ছে

        if (!$prompt || trim($prompt) === '') {
            return response()->json(['answer' => '❌ কোনো প্রশ্ন পাওয়া যায়নি।']);
        }

        try {
            $response = Gemini::generateContent($prompt);
            $answer = $response->text() ?? '🤖 কোনো উত্তর পাওয়া যায়নি।';
            return response()->json(['answer' => $answer]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
