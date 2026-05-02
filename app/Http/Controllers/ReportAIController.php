<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Report;

class ReportAIController extends Controller
{
    public function index()
    {
        return view('report-ai');
    }

    public function analyze(Request $request)
    {
        $question = $request->input('question');
        $reports = Report::select('date', 'sales', 'profit', 'expense')
                         ->latest()->take(30)->get();
        $data = $reports->toArray();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini', // চাইলে gpt-5 ব্যবহার করতে পারো
            'messages' => [
                ['role' => 'system', 'content' => 'তুমি একজন স্মার্ট রিপোর্ট বিশ্লেষণকারী সহকারী।'],
                ['role' => 'user', 'content' => 'এই রিপোর্ট ডেটা বিশ্লেষণ করো: ' . json_encode($data, JSON_UNESCAPED_UNICODE)],
                ['role' => 'user', 'content' => $question],
            ],
        ]);

        $answer = $response->json()['choices'][0]['message']['content'] ?? 'দুঃখিত, কোনো উত্তর পাওয়া যায়নি।';

        return response()->json(['answer' => $answer]);
    }
}
