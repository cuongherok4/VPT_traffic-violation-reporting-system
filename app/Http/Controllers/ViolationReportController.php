<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreViolationReportRequest;
use App\Http\Requests\UpdateReportStatusRequest;
use App\Models\ViolationReport;
use App\Services\MediaStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        $storedMedia = null;

        if ($request->hasFile('evidence')) {
            $storedMedia = $media->putEvidence($request->file('evidence'));
            $payload += $storedMedia->toColumns('evidence_path', 'evidence_url');
        }

        $payload['reporter_id'] = $request->user()?->id;
        $payload['status'] = ReportStatus::Pending;

        try {
            $report = DB::transaction(fn () => ViolationReport::query()->create($payload));
        } catch (Throwable $exception) {
            $media->delete($storedMedia);

            throw $exception;
        }

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
