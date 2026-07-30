<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ViolationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ViolationLookupApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_lookup_by_license_plate_returns_safe_fields_only(): void
    {
        $owner = User::factory()->create(['role' => 'citizen']);
        $report = $this->reportFor($owner);

        $this->getJson('/api/violations/lookup?license_plate=29A-12345')
            ->assertOk()
            ->assertJsonPath('data.0.id', $report->id)
            ->assertJsonMissingPath('data.0.description')
            ->assertJsonMissingPath('data.0.evidence_url')
            ->assertJsonMissingPath('data.0.reporter.email');
    }

    public function test_owner_lookup_returns_private_fields_for_own_report(): void
    {
        $owner = User::factory()->create(['role' => 'citizen']);
        $report = $this->reportFor($owner);

        Sanctum::actingAs($owner);

        $this->getJson("/api/violations/lookup?report_id={$report->id}")
            ->assertOk()
            ->assertJsonPath('data.0.description', 'Vehicle crossed red light.')
            ->assertJsonPath('data.0.reporter.email', $owner->email);
    }

    public function test_email_lookup_requires_authentication(): void
    {
        $owner = User::factory()->create(['role' => 'citizen']);
        $this->reportFor($owner);

        $this->getJson("/api/violations/lookup?email={$owner->email}")
            ->assertUnprocessable();

        Sanctum::actingAs($owner);

        $this->getJson("/api/violations/lookup?email={$owner->email}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_lookup_requires_at_least_one_lookup_key(): void
    {
        $this->getJson('/api/violations/lookup')
            ->assertUnprocessable();
    }

    private function reportFor(User $user): ViolationReport
    {
        return ViolationReport::query()->create([
            'reporter_id' => $user->id,
            'license_plate' => '29A-12345',
            'location' => 'Hoan Kiem, Ha Noi',
            'violation_type' => 'Vuot den do',
            'description' => 'Vehicle crossed red light.',
            'violated_at' => now()->subHour(),
            'status' => 'pending',
            'evidence_url' => 'https://example.com/private-evidence.jpg',
        ]);
    }
}
