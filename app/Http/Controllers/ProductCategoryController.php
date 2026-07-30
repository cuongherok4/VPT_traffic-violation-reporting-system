<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = ProductCategory::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate($request->integer('per_page', 20));

        return response()->json($categories);
    }

    public function store(StoreProductCategoryRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['slug'] ??= Str::slug($payload['name']);

        $category = ProductCategory::query()->create($payload);

        return response()->json($category, 201);
    }

    public function show(ProductCategory $category): JsonResponse
    {
        return response()->json($category->load('products'));
    }
}
