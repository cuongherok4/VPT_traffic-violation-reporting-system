<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use App\Models\ViolationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_lookup_page_renders(): void
    {
        ViolationReport::query()->create([
            'license_plate' => '29A-12345',
            'location' => 'Hoan Kiem, Ha Noi',
            'violation_type' => 'Vuot den do',
            'description' => 'Demo lookup data.',
            'violated_at' => now()->subDay(),
            'status' => ReportStatus::Verified,
            'fine_amount' => 300000,
        ]);

        $this->get(route('lookup.index'))
            ->assertOk()
            ->assertSee('Tra cứu Vi phạm Giao thông')
            ->assertSee('Vi phạm mới ghi nhận')
            ->assertSee('29A-12345');
    }

    public function test_guest_is_redirected_from_citizen_report_form(): void
    {
        $this->get(route('citizen.reports.create'))
            ->assertRedirect(route('login'));
    }

    public function test_citizen_report_form_shows_violation_fine_suggestions(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('citizen.reports.create'))
            ->assertOk()
            ->assertSee('Chạy quá tốc độ')
            ->assertSee('Mức phạt gợi ý', false);
    }

    public function test_citizen_can_create_report_from_web_form(): void
    {
        $citizen = User::factory()->create();

        $response = $this->actingAs($citizen)->post(route('citizen.reports.store'), [
            'license_plate' => '30F-12345',
            'location' => 'Cau Giay, Ha Noi',
            'violation_type' => 'Di sai lan',
            'description' => 'Xe di vao lan uu tien trong gio cao diem.',
            'violated_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ]);

        $report = ViolationReport::query()->first();

        $response->assertRedirect(route('citizen.reports.show', $report));
        $this->assertSame($citizen->id, $report->reporter_id);
        $this->assertSame(ReportStatus::Pending, $report->status);
    }

    public function test_admin_dashboard_requires_admin_role(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Bảng Điều Khiển Xử Lý Vi Phạm')
            ->assertSee('Quản lý sản phẩm');
    }

    public function test_admin_can_replace_report_evidence_image(): void
    {
        config(['filesystems.cloud' => 'public']);
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $report = ViolationReport::query()->create([
            'license_plate' => '36B-11223',
            'location' => 'Quoc lo 1A, Thanh Hoa',
            'violation_type' => 'Vuot qua toc do',
            'description' => 'Xe vuot qua toc do tren quoc lo.',
            'violated_at' => now()->subHour(),
            'status' => ReportStatus::Pending,
            'fine_amount' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reports.show', $report))
            ->assertOk()
            ->assertSee('Mức phạt theo hành vi', false)
            ->assertSee('Áp dụng mức gợi ý');

        $this->actingAs($admin)
            ->patch(route('admin.reports.update', $report), [
                'status' => ReportStatus::Verified->value,
                'fine_amount' => 1200000,
                'description' => 'Xe vuot qua toc do toi da cho phep, muc phat 1.200.000 VND.',
                'evidence' => UploadedFile::fake()->image('speeding.jpg'),
            ])
            ->assertRedirect();

        $report->refresh();

        $this->assertSame(ReportStatus::Verified, $report->status);
        $this->assertSame(1200000, $report->fine_amount);
        $this->assertSame('Xe vuot qua toc do toi da cho phep, muc phat 1.200.000 VND.', $report->description);
        $this->assertNotNull($report->evidence_path);
        $this->assertStringStartsWith('/storage/evidence/', $report->evidence_url);
    }

    public function test_admin_can_manage_products_from_web(): void
    {
        config(['filesystems.cloud' => 'public']);
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $category = ProductCategory::query()->create(['name' => 'Bao hiem xe', 'slug' => 'bao-hiem-xe']);

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Quản Lý Sản Phẩm An Toàn');

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Bao hiem xe may',
                'description' => 'Bao hiem trach nhiem dan su chinh hang.',
                'stock' => 10,
                'price' => 66000,
                'image' => UploadedFile::fake()->image('product.jpg'),
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->firstOrFail();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $category->id,
            'name' => 'Bao hiem xe may',
            'stock' => 10,
            'price' => 66000,
        ]);
        $this->assertNotNull($product->image_path);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Bao hiem xe may cao cap',
                'description' => 'Cap nhat goi bao hiem.',
                'stock' => 8,
                'price' => 88000,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Bao hiem xe may cao cap',
            'stock' => 8,
            'price' => 88000,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_authenticated_user_can_order_product_from_shop(): void
    {
        $category = ProductCategory::query()->create(['name' => 'Mu bao hiem', 'slug' => 'mu-bao-hiem']);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Mu bao hiem dat chuan',
            'slug' => 'mu-bao-hiem-dat-chuan',
            'description' => 'San pham dat chuan.',
            'stock' => 5,
            'price' => 250000,
        ]);

        $this->actingAs(User::factory()->create())
            ->post(route('shop.order', $product), [
                'quantity' => 2,
                'payment_method' => 'cod',
            ])
            ->assertRedirect(route('shop.index'));

        $this->assertDatabaseHas('orders', ['total_amount' => 500000]);
        $this->assertSame(3, $product->refresh()->stock);
    }
}
