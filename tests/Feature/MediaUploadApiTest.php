<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MediaUploadApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['filesystems.cloud' => 's3']);
        Storage::fake('s3');
    }

    public function test_report_evidence_is_stored_on_s3_disk(): void
    {
        $response = $this->postJson('/api/reports', [
            'license_plate' => '29A-12345',
            'location' => 'Hoan Kiem, Ha Noi',
            'violation_type' => 'Vuot den do',
            'description' => 'Vehicle crossed red light.',
            'violated_at' => now()->subHour()->toDateTimeString(),
            'evidence' => UploadedFile::fake()->image('evidence.jpg'),
        ])->assertCreated();

        $path = $response->json('evidence_path');

        $this->assertStringStartsWith('evidence/', $path);
        Storage::disk('s3')->assertExists($path);
        $this->assertDatabaseHas('violation_reports', ['evidence_path' => $path]);
    }

    public function test_product_image_is_stored_on_s3_disk(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $category = ProductCategory::query()->create([
            'name' => 'Mu bao hiem',
            'slug' => 'mu-bao-hiem',
        ]);

        $response = $this->postJson('/api/products', [
            'category_id' => $category->id,
            'name' => 'Mu bao hiem 3/4 dau',
            'stock' => 10,
            'price' => 350000,
            'image' => UploadedFile::fake()->image('product.webp'),
        ])->assertCreated();

        $path = $response->json('image_path');

        $this->assertStringStartsWith('products/', $path);
        Storage::disk('s3')->assertExists($path);
        $this->assertDatabaseHas('products', ['image_path' => $path]);
    }

    public function test_news_image_is_stored_on_s3_disk(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $response = $this->postJson('/api/news-articles', [
            'title' => 'Xu ly vi pham qua camera',
            'content' => 'Noi dung tin tuc.',
            'published_at' => now()->toDateTimeString(),
            'image' => UploadedFile::fake()->image('news.png'),
        ])->assertCreated();

        $path = $response->json('image_path');

        $this->assertStringStartsWith('news/', $path);
        Storage::disk('s3')->assertExists($path);
        $this->assertDatabaseHas('news_articles', ['image_path' => $path]);
    }
}
