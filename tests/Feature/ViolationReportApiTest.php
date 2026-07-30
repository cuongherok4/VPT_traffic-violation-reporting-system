<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ViolationReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_create_violation_report(): void
    {
        $response = $this->postJson('/api/reports', [
            'license_plate' => '29A-12345',
            'location' => 'Hoan Kiem, Ha Noi',
            'violation_type' => 'Vuot den do',
            'description' => 'Vehicle crossed red light during rush hour.',
            'violated_at' => now()->subHour()->toDateTimeString(),
        ]);

        $response->assertCreated()
            ->assertJsonPath('license_plate', '29A-12345')
            ->assertJsonPath('status', 'pending');

        $this->assertDatabaseHas('violation_reports', [
            'license_plate' => '29A-12345',
            'status' => 'pending',
        ]);
    }

    public function test_dashboard_returns_report_summary(): void
    {
        $this->postJson('/api/reports', [
            'license_plate' => '30A-67890',
            'location' => 'Cau Giay, Ha Noi',
            'violation_type' => 'Di nguoc chieu',
            'description' => 'Vehicle moved against traffic direction.',
            'violated_at' => now()->subHour()->toDateTimeString(),
        ]);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('total_reports', 1)
            ->assertJsonPath('by_status.pending', 1);
    }

    public function test_only_admin_can_update_report_status(): void
    {
        $reportId = $this->postJson('/api/reports', [
            'license_plate' => '30A-67890',
            'location' => 'Cau Giay, Ha Noi',
            'violation_type' => 'Di nguoc chieu',
            'description' => 'Vehicle moved against traffic direction.',
            'violated_at' => now()->subHour()->toDateTimeString(),
        ])->json('id');

        $this->patchJson("/api/reports/{$reportId}/status", [
            'status' => 'verified',
            'fine_amount' => 300000,
        ])->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create(['role' => 'citizen']));

        $this->patchJson("/api/reports/{$reportId}/status", [
            'status' => 'verified',
            'fine_amount' => 300000,
        ])->assertForbidden();

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->patchJson("/api/reports/{$reportId}/status", [
            'status' => 'verified',
            'fine_amount' => 300000,
        ])->assertOk()
            ->assertJsonPath('status', 'verified')
            ->assertJsonPath('fine_amount', 300000);
    }
}
