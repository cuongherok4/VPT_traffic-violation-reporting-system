<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['items.product:id,name,price,image_url'])
            ->when($request->user()->role !== 'admin', fn ($q) => $q->where('user_id', $request->user()->id))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = DB::transaction(function () use ($request) {
            $items = collect($request->validated('items'));
            $products = Product::query()
                ->whereIn('id', $items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $orderItems = $items->map(function (array $item) use ($products) {
                $product = $products->get($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => ["Product {$product->id} does not have enough stock."],
                    ]);
                }

                return [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                ];
            });

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'total_amount' => $orderItems->sum(fn ($item) => $item['unit_price'] * $item['quantity']),
                'payment_method' => $request->input('payment_method'),
                'status' => 'pending',
            ]);

            $orderItems->each(function (array $item) use ($order) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            });

            return $order;
        });

        return response()->json($order->load('items.product:id,name,price,image_url'), 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_if($request->user()->role !== 'admin' && $order->user_id !== $request->user()->id, 403);

        return response()->json($order->load(['user:id,name,email', 'items.product:id,name,price,image_url']));
    }
}
