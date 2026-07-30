<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ViolationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'license_plate',
        'location',
        'violation_type',
        'description',
        'violated_at',
        'evidence_path',
        'evidence_url',
        'status',
        'fine_amount',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'violated_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'fine_amount' => 'integer',
            'status' => ReportStatus::class,
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function fineReceipt(): HasOne
    {
        return $this->hasOne(FineReceipt::class);
    }
}
