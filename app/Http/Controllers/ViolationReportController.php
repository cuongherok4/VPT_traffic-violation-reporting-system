<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreViolationReportRequest;
use App\Http\Requests\UpdateReportStatusRequest;
use App\Models\ViolationReport;
use App\Services\MediaStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViolationReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reports = ViolationReport::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('license_plate'), fn ($q) => $q->where('license_plate', 'like', '%'.$request->input('license_plate').'%'))
            ->latest('violated_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($reports);
    }

    public function store(StoreViolationReportRequest $request, MediaStorage $media): JsonResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('evidence')) {
            $file = $media->putEvidence($request->file('evidence'));
            $payload['evidence_path'] = $file['path'];
            $payload['evidence_url'] = $file['url'];
        }

        $payload['reporter_id'] = $request->user()?->id;
        $payload['status'] = ReportStatus::Pending;

        $report = ViolationReport::create($payload);

        return response()->json($report, 201);
    }

    public function show(ViolationReport $report): JsonResponse
    {
        return response()->json($report->load(['reporter:id,name,email', 'reviewer:id,name,email']));
    }

    public function updateStatus(UpdateReportStatusRequest $request, ViolationReport $report): JsonResponse
    {
        $report->update([
            'status' => $request->enum('status', ReportStatus::class),
            'fine_amount' => $request->integer('fine_amount', $report->fine_amount),
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return response()->json($report->fresh());
    }
}
