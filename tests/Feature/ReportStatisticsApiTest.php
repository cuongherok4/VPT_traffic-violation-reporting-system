<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ViolationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReportStatisticsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_statistics_routes_are_admin_only(): void
    {
        $this->getJson('/api/statistics/overview')->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create(['role' => 'citizen']));

        $this->getJson('/api/statistics/overview')->assertForbidden();
    }

    public function test_admin_can_view_overview_and_status_statistics(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->makeReport(status: 'pending', fineAmount: 0);
        $this->makeReport(status: 'verified', fineAmount: 300000);
        $this->makeReport(status: 'resolved', fineAmount: 200000);

        $this->getJson('/api/statistics/overview')
            ->assertOk()
            ->assertJsonPath('total_reports', 3)
            ->assertJsonPath('total_fines', 500000)
            ->assertJsonPath('pending_reports', 1)
            ->assertJsonPath('verified_reports', 1)
            ->assertJsonPath('resolved_reports', 1);

        $this->getJson('/api/statistics/statuses')
            ->assertOk()
            ->assertJsonFragment(['status' => 'pending', 'total' => 1])
            ->assertJsonFragment(['status' => 'verified', 'total' => 1]);
    }

    public function test_admin_can_view_top_locations(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->makeReport(location: 'Hoan Kiem, Ha Noi');
        $this->makeReport(location: 'Hoan Kiem, Ha Noi');
        $this->makeReport(location: 'Cau Giay, Ha Noi');

        $this->getJson('/api/statistics/locations?limit=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.location', 'Hoan Kiem, Ha Noi')
            ->assertJsonPath('data.0.total', 2);
    }

    public function test_admin_can_view_status_statistics_by_user(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $citizen = User::factory()->create(['role' => 'citizen', 'email' => 'citizen@example.com']);
        $this->makeReport(reporter: $citizen, status: 'pending');
        $this->makeReport(reporter: $citizen, status: 'verified', fineAmount: 300000);

        $this->getJson('/api/statistics/users')
            ->assertOk()
            ->assertJsonPath('data.0.email', 'citizen@example.com')
            ->assertJsonPath('data.0.total', 2)
            ->assertJsonPath('data.0.pending', 1)
            ->assertJsonPath('data.0.verified', 1)
            ->assertJsonPath('data.0.total_fines', 300000);
    }

    public function test_statistics_can_be_filtered_by_violation_date(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->makeReport(violatedAt: now()->subDays(10));
        $this->makeReport(violatedAt: now()->subDay());

        $this->getJson('/api/statistics/overview?date_from='.now()->subDays(2)->toDateString())
            ->assertOk()
            ->assertJsonPath('total_reports', 1);

        $this->getJson('/api/statistics/trend')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    private function makeReport(
        ?User $reporter = null,
        string $location = 'Hoan Kiem, Ha Noi',
        string $status = 'pending',
        int $fineAmount = 0,
        mixed $violatedAt = null,
    ): ViolationReport {
        return ViolationReport::query()->create([
            'reporter_id' => $reporter?->id,
            'license_plate' => fake()->bothify('??A-#####'),
            'location' => $location,
            'violation_type' => 'Vuot den do',
            'description' => 'Vehicle crossed red light.',
            'violated_at' => $violatedAt ?? now(),
            'status' => $status,
            'fine_amount' => $fineAmount,
        ]);
    }
}
