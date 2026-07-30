<?php

namespace App\Http\Controllers\Web;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreViolationReportRequest;
use App\Models\ViolationReport;
use App\Services\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CitizenReportController extends Controller
{
    public function index(Request $request): View
    {
        $baseQuery = ViolationReport::query()->where('reporter_id', $request->user()->id);

        $reports = (clone $baseQuery)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('license_plate'), fn ($q) => $q->where('license_plate', 'like', '%'.$request->input('license_plate').'%'))
            ->latest('violated_at')
            ->paginate(10)
            ->withQueryString();

        return view('citizen.reports.index', [
            'reports' => $reports,
            'statuses' => ReportStatus::values(),
            'summary' => [
                'total' => (clone $baseQuery)->count(),
                'pending' => (clone $baseQuery)->where('status', ReportStatus::Pending)->count(),
                'verified' => (clone $baseQuery)->where('status', ReportStatus::Verified)->count(),
                'resolved' => (clone $baseQuery)->where('status', ReportStatus::Resolved)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('citizen.reports.create', [
            'violationTypes' => config('traffic_violations.types', []),
        ]);
    }

    public function store(StoreViolationReportRequest $request, MediaStorage $media): RedirectResponse
    {
        $payload = $request->validated();
        $storedMedia = null;

        try {
            if ($request->hasFile('evidence')) {
                $storedMedia = $media->putEvidence($request->file('evidence'));
                $payload['evidence_path'] = $storedMedia['path'];
                $payload['evidence_url'] = $storedMedia['url'];
            }
        } catch (Throwable) {
            return back()->withErrors(['evidence' => 'Không upload được ảnh lên AWS S3. Vui lòng thử lại sau hoặc kiểm tra cấu hình storage.'])->withInput();
        }

        $payload['reporter_id'] = $request->user()->id;
        $payload['status'] = ReportStatus::Pending;

        try {
            $report = DB::transaction(fn () => ViolationReport::query()->create($payload));
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('citizen.reports.show', $report)->with('status', 'Bao cao da duoc gui thanh cong.');
    }

    public function show(Request $request, ViolationReport $report): View
    {
        abort_if($report->reporter_id !== $request->user()->id, 403);

        return view('citizen.reports.show', ['report' => $report->load('fineReceipt')]);
    }
}
