<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class AdminProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->query('q').'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        $products = $query->latest('id')->paginate(10)->withQueryString();
        $categories = ProductCategory::query()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = ProductCategory::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request, MediaStorage $media): RedirectResponse
    {
        $payload = $request->safe()->except('image');
        $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['name']);

        try {
            if ($request->hasFile('image')) {
                $file = $media->putProductImage($request->file('image'));
                $payload['image_path'] = $file['path'];
                $payload['image_url'] = $file['url'];
            }
        } catch (Throwable) {
            return back()->withErrors(['image' => 'Không upload được ảnh lên AWS S3. Vui lòng kiểm tra bucket, region và IAM policy.'])->withInput();
        }

        Product::query()->create($payload);

        return redirect()->route('admin.products.index')->with('status', 'Đã thêm sản phẩm mới thành công.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product, MediaStorage $media): RedirectResponse
    {
        $payload = $request->safe()->except('image');

        if (array_key_exists('slug', $payload) || array_key_exists('name', $payload)) {
            $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['name'], $product);
        }

        try {
            if ($request->hasFile('image')) {
                $file = $media->putProductImage($request->file('image'));
                $payload['image_path'] = $file['path'];
                $payload['image_url'] = $file['url'];
            }
        } catch (Throwable) {
            return back()->withErrors(['image' => 'Không upload được ảnh lên AWS S3. Vui lòng kiểm tra bucket, region và IAM policy.'])->withInput();
        }

        $product->update($payload);

        return redirect()->route('admin.products.index')->with('status', 'Đã cập nhật thông tin sản phẩm.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Đã xóa sản phẩm thành công.');
    }

    private function uniqueSlug(string $value, ?Product $ignore = null): string
    {
        $baseSlug = Str::slug($value) ?: Str::random(8);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
            ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }
}
