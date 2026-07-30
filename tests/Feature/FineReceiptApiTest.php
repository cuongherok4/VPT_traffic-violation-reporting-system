<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\ViolationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FineReceiptApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_issue_fine_receipt_for_report(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);
        $report = $this->reportFor($citizen);

        $payload = [
            'amount' => 300000,
            'violation_summary' => 'Vuot den do tai giao lo.',
            'issued_at' => now()->toDateTimeString(),
            'due_at' => now()->addDays(10)->toDateTimeString(),
        ];

        $this->postJson("/api/reports/{$report->id}/fine-receipt", $payload)
            ->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create(['role' => 'citizen']));

        $this->postJson("/api/reports/{$report->id}/fine-receipt", $payload)
            ->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $this->postJson("/api/reports/{$report->id}/fine-receipt", $payload)
            ->assertCreated()
            ->assertJsonPath('amount', 300000)
            ->assertJsonPath('payment_status', 'unpaid');

        $this->assertDatabaseHas('violation_reports', [
            'id' => $report->id,
            'status' => 'verified',
            'fine_amount' => 300000,
            'reviewed_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $citizen->id,
            'violation_report_id' => $report->id,
            'type' => 'fine_receipt_issued',
        ]);
    }

    public function test_report_can_have_only_one_fine_receipt(): void
    {
        $report = $this->reportFor(User::factory()->create(['role' => 'citizen']));
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $payload = [
            'amount' => 300000,
            'violation_summary' => 'Vuot den do tai giao lo.',
        ];

        $this->postJson("/api/reports/{$report->id}/fine-receipt", $payload)
            ->assertCreated();

        $this->postJson("/api/reports/{$report->id}/fine-receipt", $payload)
            ->assertUnprocessable();
    }

    public function test_admin_can_update_fine_receipt_payment_status(): void
    {
        $report = $this->reportFor(User::factory()->create(['role' => 'citizen']));
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $receiptId = $this->postJson("/api/reports/{$report->id}/fine-receipt", [
            'amount' => 300000,
            'violation_summary' => 'Vuot den do tai giao lo.',
        ])->json('id');

        $this->patchJson("/api/fine-receipts/{$receiptId}", [
            'payment_status' => 'paid',
            'amount' => 250000,
        ])->assertOk()
            ->assertJsonPath('payment_status', 'paid')
            ->assertJsonPath('amount', 250000);

        $this->assertDatabaseHas('violation_reports', [
            'id' => $report->id,
            'fine_amount' => 250000,
        ]);
    }

    public function test_user_can_read_only_own_notifications(): void
    {
        $owner = User::factory()->create(['role' => 'citizen']);
        $other = User::factory()->create(['role' => 'citizen']);
        $notification = UserNotification::query()->create([
            'user_id' => $owner->id,
            'type' => 'fine_receipt_issued',
            'title' => 'Bien lai phat',
            'message' => 'Thong bao nop phat.',
        ]);

        Sanctum::actingAs($owner);

        $this->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonPath('data.0.id', $notification->id);

        $this->patchJson("/api/notifications/{$notification->id}/read")
            ->assertOk()
            ->assertJsonPath('read_at', fn ($value) => filled($value));

        Sanctum::actingAs($other);

        $this->patchJson("/api/notifications/{$notification->id}/read")
            ->assertForbidden();
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
        ]);
    }
}
