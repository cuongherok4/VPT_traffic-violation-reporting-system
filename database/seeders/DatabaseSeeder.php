<?php

namespace Database\Seeders;

use App\Enums\ReportStatus;
use App\Models\NewsArticle;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use App\Models\ViolationReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@vpt.local',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $citizen = User::query()->create([
            'name' => 'Citizen Demo',
            'email' => 'citizen@vpt.local',
            'role' => 'citizen',
            'password' => Hash::make('password'),
        ]);

        ProductCategory::query()->insert([
            ['id' => 1, 'name' => 'Mu bao hiem', 'slug' => 'mu-bao-hiem', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Ao mua', 'slug' => 'ao-mua', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Bao hiem xe', 'slug' => 'bao-hiem-xe', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Product::query()->insert([
            [
                'category_id' => 1,
                'name' => 'Mu bao hiem 3/4 dau',
                'slug' => 'mu-bao-hiem-3-4-dau',
                'description' => 'Mu bao hiem dat chuan cho di chuyen hang ngay.',
                'stock' => 20,
                'price' => 350000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Ao mua phan quang',
                'slug' => 'ao-mua-phan-quang',
                'description' => 'Ao mua co dai phan quang tang kha nang nhan dien ban dem.',
                'stock' => 15,
                'price' => 180000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        NewsArticle::query()->create([
            'title' => 'Tang cuong xu ly vi pham qua camera',
            'slug' => 'tang-cuong-xu-ly-vi-pham-qua-camera',
            'content' => 'He thong tiep nhan va xu ly bao cao vi pham giao thong giup nang cao y thuc tham gia giao thong.',
            'published_at' => now(),
        ]);

        ViolationReport::query()->create([
            'reporter_id' => $citizen->id,
            'license_plate' => '29A-12345',
            'location' => 'Hoan Kiem, Ha Noi',
            'violation_type' => 'Vuot den do',
            'description' => 'Xe may vuot den do tai nga tu vao gio cao diem.',
            'violated_at' => now()->subDay(),
            'status' => ReportStatus::Verified,
            'fine_amount' => 300000,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);
    }
}
