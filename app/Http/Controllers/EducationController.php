<?php

namespace App\Http\Controllers;

use App\Models\EducationContent;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $query = EducationContent::where('is_published', true);

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->has('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        return response()->json($query->orderBy('urutan')->get());
    }

    public function show($id)
    {
        return response()->json(EducationContent::findOrFail($id));
    }

    public function submitQuiz(Request $request)
    {
        $validated = $request->validate([
            'content_id' => 'required|exists:education_contents,id',
            'answers' => 'required|array',
        ]);

        $content = EducationContent::findOrFail($validated['content_id']);
        $quizData = $content->quiz_data ?? [];
        $correct = 0;
        $total = count($quizData);

        foreach ($quizData as $i => $question) {
            if (isset($validated['answers'][$i]) && $validated['answers'][$i] === ($question['correct'] ?? null)) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100) : 0;

        return response()->json([
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'passed' => $score >= 70,
        ]);
    }
}
