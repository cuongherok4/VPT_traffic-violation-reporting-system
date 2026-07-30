<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatisticsFilterRequest;
use App\Models\ViolationReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportStatisticsController extends Controller
{
    public function overview(StatisticsFilterRequest $request): JsonResponse
    {
        $query = $this->filteredReports($request);

        return response()->json([
            'total_reports' => (clone $query)->count(),
            'total_fines' => (int) (clone $query)->sum('fine_amount'),
            'pending_reports' => (clone $query)->where('status', 'pending')->count(),
            'verified_reports' => (clone $query)->where('status', 'verified')->count(),
            'resolved_reports' => (clone $query)->where('status', 'resolved')->count(),
            'rejected_reports' => (clone $query)->where('status', 'rejected')->count(),
        ]);
    }

    public function byLocation(StatisticsFilterRequest $request): JsonResponse
    {
        $locations = $this->filteredReports($request)
            ->select('location', DB::raw('count(*) as total'), DB::raw('sum(fine_amount) as total_fines'))
            ->groupBy('location')
            ->orderByDesc('total')
            ->limit($request->limit())
            ->get();

        return response()->json(['data' => $locations]);
    }

    public function byStatus(StatisticsFilterRequest $request): JsonResponse
    {
        $statuses = $this->filteredReports($request)
            ->select('status', DB::raw('count(*) as total'), DB::raw('sum(fine_amount) as total_fines'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        return response()->json(['data' => $statuses]);
    }

    public function byUser(StatisticsFilterRequest $request): JsonResponse
    {
        $users = $this->filteredReports($request)
            ->leftJoin('users', 'users.id', '=', 'violation_reports.reporter_id')
            ->select(
                'violation_reports.reporter_id',
                'users.name',
                'users.email',
                DB::raw('count(*) as total'),
                DB::raw("sum(case when violation_reports.status = 'pending' then 1 else 0 end) as pending"),
                DB::raw("sum(case when violation_reports.status = 'verified' then 1 else 0 end) as verified"),
                DB::raw("sum(case when violation_reports.status = 'resolved' then 1 else 0 end) as resolved"),
                DB::raw("sum(case when violation_reports.status = 'rejected' then 1 else 0 end) as rejected"),
                DB::raw('sum(violation_reports.fine_amount) as total_fines'),
            )
            ->groupBy('violation_reports.reporter_id', 'users.name', 'users.email')
            ->orderByDesc('total')
            ->limit($request->limit())
            ->get();

        return response()->json(['data' => $users]);
    }

    public function trend(StatisticsFilterRequest $request): JsonResponse
    {
        $trend = $this->filteredReports($request)
            ->select(DB::raw('date(violated_at) as date'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('date(violated_at)'))
            ->orderBy('date')
            ->get();

        return response()->json(['data' => $trend]);
    }

    private function filteredReports(StatisticsFilterRequest $request): Builder
    {
        return ViolationReport::query()
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('violated_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('violated_at', '<=', $request->date('date_to')));
    }
}
