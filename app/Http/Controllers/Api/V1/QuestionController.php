<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Question::query()
            ->where('status', 'answered')
            ->whereHas('answers', fn ($q) => $q->where('status', 'published'))
            ->with([
                'topic',
                'latestAnswer' => fn ($q) => $q->where('status', 'published')->with('user'),
            ]);

        if ($request->filled('topic')) {
            $topic = $request->string('topic');
            $query->whereHas('topic', function ($q) use ($topic) {
                $q->where('slug', $topic)->orWhere('id', $topic);
            });
        }

        if ($request->filled('search')) {
            $search = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', $search)
                    ->orWhere('body', 'like', $search);
            });
        }

        $perPage = min((int) $request->input('per_page', 10), 50);
        $questions = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return QuestionResource::collection($questions);
    }

    public function show(int $id): QuestionResource
    {
        $question = Question::query()
            ->where('status', 'answered')
            ->with([
                'topic',
                'latestAnswer' => fn ($q) => $q->where('status', 'published')->with('user'),
            ])
            ->findOrFail($id);

        return new QuestionResource($question);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['required', 'exists:question_topics,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
        ]);

        $question = Question::create([
            ...$validated,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        $question->load('topic');

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim. Tim kami akan segera menindaklanjuti dan mengabari Anda melalui email.',
            'data' => new QuestionResource($question),
        ], 201);
    }
}
