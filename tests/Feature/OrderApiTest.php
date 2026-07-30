<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_order_with_server_calculated_total(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Mu bao hiem',
            'slug' => 'mu-bao-hiem',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Mu bao hiem 3/4 dau',
            'slug' => 'mu-bao-hiem-3-4-dau',
            'stock' => 10,
            'price' => 350000,
        ]);

        Sanctum::actingAs(User::factory()->create(['role' => 'citizen']));

        $this->postJson('/api/orders', [
            'payment_method' => 'bank_transfer',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ])->assertCreated()
            ->assertJsonPath('total_amount', 700000)
            ->assertJsonPath('items.0.unit_price', 350000)
            ->assertJsonPath('items.0.quantity', 2);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }

    public function test_order_fails_when_stock_is_not_enough(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Ao mua',
            'slug' => 'ao-mua',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Ao mua phan quang',
            'slug' => 'ao-mua-phan-quang',
            'stock' => 1,
            'price' => 180000,
        ]);

        Sanctum::actingAs(User::factory()->create(['role' => 'citizen']));

        $this->postJson('/api/orders', [
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ])->assertUnprocessable();
    }
}
