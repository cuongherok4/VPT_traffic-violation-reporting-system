<?php

namespace Database\Seeders;

use App\Enums\ReportStatus;
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
            ['name' => 'Mu bao hiem', 'slug' => 'mu-bao-hiem', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ao mua', 'slug' => 'ao-mua', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bao hiem xe', 'slug' => 'bao-hiem-xe', 'created_at' => now(), 'updated_at' => now()],
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
