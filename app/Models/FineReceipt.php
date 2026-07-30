<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FineReceipt extends Model
{
    protected $fillable = [
        'violation_report_id',
        'issued_by',
        'amount',
        'violation_summary',
        'payment_status',
        'issued_at',
        'due_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(ViolationReport::class, 'violation_report_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }
}
