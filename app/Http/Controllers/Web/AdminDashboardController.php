<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ViolationReport;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'summary' => [
                'total' => ViolationReport::query()->count(),
                'pending' => ViolationReport::query()->where('status', 'pending')->count(),
                'verified' => ViolationReport::query()->where('status', 'verified')->count(),
                'resolved' => ViolationReport::query()->where('status', 'resolved')->count(),
            ],
            'topLocations' => ViolationReport::query()
                ->select('location', DB::raw('count(*) as total'))
                ->groupBy('location')
                ->orderByDesc('total')
                ->limit(6)
                ->get(),
            'recentReports' => ViolationReport::query()->latest()->limit(8)->get(),
        ]);
    }
}
