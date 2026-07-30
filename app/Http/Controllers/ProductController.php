<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\MediaStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with('category:id,name,slug')
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->input('q').'%'))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($products);
    }

    public function store(StoreProductRequest $request, MediaStorage $media): JsonResponse
    {
        $payload = $request->safe()->except('image');
        $payload['slug'] ??= Str::slug($payload['name']);
        $storedMedia = null;

        if ($request->hasFile('image')) {
            $storedMedia = $media->putProductImage($request->file('image'));
            $payload += $storedMedia->toColumns('image_path', 'image_url');
        }

        try {
            $product = DB::transaction(fn () => Product::query()->create($payload));
        } catch (Throwable $exception) {
            $media->delete($storedMedia);

            throw $exception;
        }

        return response()->json($product->load('category:id,name,slug'), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load('category:id,name,slug'));
    }

    public function update(UpdateProductRequest $request, Product $product, MediaStorage $media): JsonResponse
    {
        $payload = $request->safe()->except('image');
        $storedMedia = null;

        if (array_key_exists('name', $payload) && ! array_key_exists('slug', $payload)) {
            $payload['slug'] = Str::slug($payload['name']);
        }

        if ($request->hasFile('image')) {
            $storedMedia = $media->putProductImage($request->file('image'));
            $payload += $storedMedia->toColumns('image_path', 'image_url');
        }

        try {
            DB::transaction(fn () => $product->update($payload));
        } catch (Throwable $exception) {
            $media->delete($storedMedia);

            throw $exception;
        }

        return response()->json($product->fresh()->load('category:id,name,slug'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(status: 204);
    }
}
