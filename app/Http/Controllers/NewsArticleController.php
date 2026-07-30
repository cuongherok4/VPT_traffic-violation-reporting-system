<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsArticleRequest;
use App\Http\Requests\UpdateNewsArticleRequest;
use App\Models\NewsArticle;
use App\Services\MediaStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $articles = NewsArticle::query()
            ->when(! $request->boolean('include_drafts'), fn ($q) => $q->whereNotNull('published_at'))
            ->latest('published_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($articles);
    }

    public function store(StoreNewsArticleRequest $request, MediaStorage $media): JsonResponse
    {
        $payload = $request->safe()->except('image');
        $payload['slug'] ??= Str::slug($payload['title']);

        if ($request->hasFile('image')) {
            $file = $media->putNewsImage($request->file('image'));
            $payload['image_path'] = $file['path'];
            $payload['image_url'] = $file['url'];
        }

        $article = NewsArticle::query()->create($payload);

        return response()->json($article, 201);
    }

    public function show(NewsArticle $newsArticle): JsonResponse
    {
        return response()->json($newsArticle);
    }

    public function update(UpdateNewsArticleRequest $request, NewsArticle $newsArticle, MediaStorage $media): JsonResponse
    {
        $payload = $request->safe()->except('image');

        if (array_key_exists('title', $payload) && ! array_key_exists('slug', $payload)) {
            $payload['slug'] = Str::slug($payload['title']);
        }

        if ($request->hasFile('image')) {
            $file = $media->putNewsImage($request->file('image'));
            $payload['image_path'] = $file['path'];
            $payload['image_url'] = $file['url'];
        }

        $newsArticle->update($payload);

        return response()->json($newsArticle->fresh());
    }

    public function destroy(NewsArticle $newsArticle): JsonResponse
    {
        $newsArticle->delete();

        return response()->json(status: 204);
    }
}
