<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category:id,name,slug')
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->input('q').'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', [
            'products' => $products,
            'categories' => ProductCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('shop.show', ['product' => $product->load('category:id,name,slug')]);
    }

    public function order(Request $request, Product $product): RedirectResponse
    {
        $payload = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'payment_method' => ['required', 'string', 'max:40'],
        ]);

        DB::transaction(function () use ($request, $product, $payload) {
            $product = Product::query()->lockForUpdate()->findOrFail($product->id);

            if ($product->stock < $payload['quantity']) {
                throw ValidationException::withMessages(['quantity' => 'San pham khong du ton kho.']);
            }

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'total_amount' => $product->price * $payload['quantity'],
                'payment_method' => $payload['payment_method'],
                'status' => 'pending',
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'unit_price' => $product->price,
                'quantity' => $payload['quantity'],
            ]);

            $product->decrement('stock', $payload['quantity']);
        });

        return redirect()->route('shop.index')->with('status', 'Don hang da duoc tao.');
    }
}
