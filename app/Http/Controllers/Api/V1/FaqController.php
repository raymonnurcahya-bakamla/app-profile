<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FaqController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Faq::query()
            ->active()
            ->with('topic');

        if ($request->filled('topic')) {
            $topic = $request->string('topic');
            $query->whereHas('topic', function ($q) use ($topic) {
                $q->where('slug', $topic)->orWhere('id', $topic);
            });
        }

        if ($request->filled('search')) {
            $search = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                    ->orWhere('answer', 'like', $search);
            });
        }

        $faqs = $query->get();

        return FaqResource::collection($faqs);
    }

    public function show(int $id): FaqResource
    {
        $faq = Faq::query()
            ->active()
            ->with('topic')
            ->findOrFail($id);

        return new FaqResource($faq);
    }
}
