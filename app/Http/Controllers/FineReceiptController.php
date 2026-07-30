<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreFineReceiptRequest;
use App\Http\Requests\UpdateFineReceiptRequest;
use App\Models\FineReceipt;
use App\Models\UserNotification;
use App\Models\ViolationReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FineReceiptController extends Controller
{
    public function store(StoreFineReceiptRequest $request, ViolationReport $report): JsonResponse
    {
        if ($report->fineReceipt()->exists()) {
            throw ValidationException::withMessages([
                'report' => ['This report already has a fine receipt.'],
            ]);
        }

        $receipt = DB::transaction(function () use ($request, $report) {
            $receipt = FineReceipt::query()->create([
                ...$request->validated(),
                'violation_report_id' => $report->id,
                'issued_by' => $request->user()->id,
                'payment_status' => 'unpaid',
                'issued_at' => $request->date('issued_at') ?? now(),
            ]);

            $report->update([
                'status' => ReportStatus::Verified,
                'fine_amount' => $receipt->amount,
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);

            $this->notifyReporter($report, $receipt);

            return $receipt;
        });

        return response()->json($receipt->load(['report', 'issuer:id,name,email']), 201);
    }

    public function show(FineReceipt $fineReceipt): JsonResponse
    {
        return response()->json($fineReceipt->load(['report', 'issuer:id,name,email']));
    }

    public function update(UpdateFineReceiptRequest $request, FineReceipt $fineReceipt): JsonResponse
    {
        $fineReceipt->update($request->validated());

        if ($request->has('amount')) {
            $fineReceipt->report()->update(['fine_amount' => $fineReceipt->amount]);
        }

        return response()->json($fineReceipt->fresh()->load(['report', 'issuer:id,name,email']));
    }

    private function notifyReporter(ViolationReport $report, FineReceipt $receipt): void
    {
        if (! $report->reporter_id) {
            return;
        }

        UserNotification::query()->create([
            'user_id' => $report->reporter_id,
            'violation_report_id' => $report->id,
            'fine_receipt_id' => $receipt->id,
            'type' => 'fine_receipt_issued',
            'title' => 'Bien lai phat da duoc tao',
            'message' => "Bao cao #{$report->id} da duoc xac minh voi muc phat ".number_format($receipt->amount).' VND.',
        ]);
    }
}
