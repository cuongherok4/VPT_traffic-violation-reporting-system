<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ViolationReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LookupController extends Controller
{
    public function index(Request $request): View
    {
        $hasLookup = $request->filled('report_id') || $request->filled('license_plate');

        $reports = ViolationReport::query()
            ->with('fineReceipt:id,violation_report_id,payment_status,due_at')
            ->when($request->filled('report_id'), fn ($q) => $q->whereKey($request->integer('report_id')))
            ->when($request->filled('license_plate'), fn ($q) => $q->where('license_plate', $request->input('license_plate')))
            ->latest('violated_at')
            ->limit($hasLookup ? 20 : 8)
            ->get();

        return view('lookup.index', [
            'reports' => $reports,
            'hasLookup' => $hasLookup,
        ]);
    }
}
