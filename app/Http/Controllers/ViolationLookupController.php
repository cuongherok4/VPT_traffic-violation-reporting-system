<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViolationLookupRequest;
use App\Models\ViolationReport;
use Illuminate\Http\JsonResponse;

class ViolationLookupController extends Controller
{
    public function __invoke(ViolationLookupRequest $request): JsonResponse
    {
        $reports = ViolationReport::query()
            ->with(['fineReceipt:id,violation_report_id,amount,payment_status,due_at', 'reporter:id,name,email,phone'])
            ->when($request->filled('report_id'), fn ($q) => $q->whereKey($request->integer('report_id')))
            ->when($request->filled('license_plate'), fn ($q) => $q->where('license_plate', $request->input('license_plate')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('email'), fn ($q) => $q->whereHas('reporter', fn ($user) => $user->where('email', $request->input('email'))))
            ->latest('violated_at')
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $reports->map(fn (ViolationReport $report) => $this->serialize($report, $request->user()?->id)),
        ]);
    }

    private function serialize(ViolationReport $report, ?int $viewerId): array
    {
        $isOwner = $viewerId && $report->reporter_id === $viewerId;

        $payload = [
            'id' => $report->id,
            'license_plate' => $report->license_plate,
            'violation_type' => $report->violation_type,
            'location' => $report->location,
            'violated_at' => $report->violated_at,
            'status' => $report->status,
            'fine_amount' => $report->fine_amount,
            'payment_status' => $report->fineReceipt?->payment_status,
        ];

        if ($isOwner) {
            $payload['description'] = $report->description;
            $payload['evidence_url'] = $report->evidence_url;
            $payload['fine_receipt'] = $report->fineReceipt;
            $payload['reporter'] = $report->reporter;
        }

        return $payload;
    }
}
