<?php

namespace App\Http\Controllers;

use App\Models\ViolationReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'total_reports' => ViolationReport::count(),
            'by_status' => ViolationReport::query()
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
            'top_locations' => ViolationReport::query()
                ->select('location', DB::raw('count(*) as total'))
                ->groupBy('location')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ]);
    }
}
