<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
