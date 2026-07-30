<?php

namespace App\Http\Controllers\Web;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\ViolationReport;
use App\Services\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = ViolationReport::query()
            ->with('reporter:id,name,email')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('license_plate'), fn ($q) => $q->where('license_plate', 'like', '%'.$request->input('license_plate').'%'))
            ->latest('violated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'statuses' => ReportStatus::values(),
        ]);
    }

    public function show(ViolationReport $report): View
    {
        return view('admin.reports.show', [
            'report' => $report->load(['reporter:id,name,email', 'reviewer:id,name,email', 'fineReceipt']),
            'statuses' => ReportStatus::values(),
            'violationTypes' => config('traffic_violations.types', []),
        ]);
    }

    public function update(Request $request, ViolationReport $report, MediaStorage $media): RedirectResponse
    {
        $payload = $request->validate([
            'status' => ['required', Rule::in(ReportStatus::values())],
            'fine_amount' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'description' => ['required', 'string', 'max:2000'],
            'evidence' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        unset($payload['evidence']);

        try {
            if ($request->hasFile('evidence')) {
                $file = $media->putEvidence($request->file('evidence'));
                $payload['evidence_path'] = $file['path'];
                $payload['evidence_url'] = $file['url'];
            }
        } catch (Throwable) {
            return back()->withErrors(['evidence' => 'Không upload được ảnh bằng chứng. Vui lòng thử lại.'])->withInput();
        }

        $report->update([
            ...$payload,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Da cap nhat bao cao.');
    }
}
