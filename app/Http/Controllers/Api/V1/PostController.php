<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::query()
            ->published()
            ->with(['category', 'user']);

        if ($request->filled('category')) {
            $category = $request->string('category');
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('id', $category);
            });
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        if ($request->filled('search')) {
            $search = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('summary', 'like', $search);
            });
        }

        $perPage = min((int) $request->input('per_page', 10), 50);
        $posts = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return PostResource::collection($posts);
    }

    public function show(string $slug): PostResource
    {
        $post = Post::query()
            ->published()
            ->with(['category', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('view_count');

        return new PostResource($post);
    }

    public function featured(): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->published()
            ->featured()
            ->with(['category', 'user'])
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return PostResource::collection($posts);
    }
}
