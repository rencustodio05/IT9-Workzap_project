<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerSubscription extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public const PLAN_MONTHLY = 'monthly';
    public const PLAN_QUARTERLY = 'quarterly';
    public const PLAN_YEARLY = 'yearly';

    protected $fillable = [
        'employer_id',
        'plan_type',
        'price',
        'start_date',
        'end_date',
        'status',
        'payment_status',
        'admin_approved_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }
}
