<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function getInterviewDateAttribute(): ?string
    {
        if (! empty($this->attributes['interview_date'])) {
            return $this->attributes['interview_date'];
        }

        return $this->scheduled_at?->format('Y-m-d');
    }

    public function getInterviewTimeAttribute(): ?string
    {
        if (! empty($this->attributes['interview_time'])) {
            return $this->attributes['interview_time'];
        }

        return $this->scheduled_at?->format('H:i');
    }
}
