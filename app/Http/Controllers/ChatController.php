<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat(Request $request, GeminiService $gemini)
    {
        $validated = $request->validate([
            'conversation' => 'required|array',
            'conversation.*.role' => 'required|string',
            'conversation.*.text' => 'required|string',
            'context' => 'nullable|string',
        ]);

        $systemPrompt = 'Kamu adalah CatinGuard Edu-Bot, asisten kesehatan pranikah Indonesia yang ramah dan informatif. Tugasmu membantu calon pengantin (Catin) memahami risiko kesehatan reproduksi, khususnya terkait Rhesus dan Thalasemia. Jawab dengan bahasa Indonesia yang mudah dipahami awam. Jangan memberikan diagnosis medis final. Selalu sarankan konsultasi ke dokter untuk kepastian. Gunakan nada yang suportif dan tidak menghakimi.';

        if (!empty($validated['context'])) {
            $systemPrompt .= "\n\nKonteks tambahan: " . $validated['context'];
        }

        $output = $gemini->chat($validated['conversation'], $systemPrompt, 0.5);

        return response()->json(['output' => $output]);
    }
}