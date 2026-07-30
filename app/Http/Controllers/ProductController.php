<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\MediaStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        if ($request->hasFile('image')) {
            $file = $media->putProductImage($request->file('image'));
            $payload['image_path'] = $file['path'];
            $payload['image_url'] = $file['url'];
        }

        $product = Product::query()->create($payload);

        return response()->json($product->load('category:id,name,slug'), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load('category:id,name,slug'));
    }

    public function update(UpdateProductRequest $request, Product $product, MediaStorage $media): JsonResponse
    {
        $payload = $request->safe()->except('image');

        if (array_key_exists('name', $payload) && ! array_key_exists('slug', $payload)) {
            $payload['slug'] = Str::slug($payload['name']);
        }

        if ($request->hasFile('image')) {
            $file = $media->putProductImage($request->file('image'));
            $payload['image_path'] = $file['path'];
            $payload['image_url'] = $file['url'];
        }

        $product->update($payload);

        return response()->json($product->fresh()->load('category:id,name,slug'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(status: 204);
    }
}
