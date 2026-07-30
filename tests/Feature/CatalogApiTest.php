<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_browse_products(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Mu bao hiem',
            'slug' => 'mu-bao-hiem',
        ]);

        Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Mu bao hiem 3/4 dau',
            'slug' => 'mu-bao-hiem-3-4-dau',
            'stock' => 10,
            'price' => 350000,
        ]);

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Mu bao hiem 3/4 dau');
    }

    public function test_only_admin_can_create_product(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Ao mua',
            'slug' => 'ao-mua',
        ]);

        $payload = [
            'category_id' => $category->id,
            'name' => 'Ao mua phan quang',
            'stock' => 5,
            'price' => 180000,
        ];

        $this->postJson('/api/products', $payload)->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create(['role' => 'citizen']));

        $this->postJson('/api/products', $payload)->assertForbidden();

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->postJson('/api/products', $payload)
            ->assertCreated()
            ->assertJsonPath('name', 'Ao mua phan quang')
            ->assertJsonPath('slug', 'ao-mua-phan-quang');
    }
}
