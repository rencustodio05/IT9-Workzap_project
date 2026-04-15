<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'employer_id',
        'jobseeker_id',
        'job_id',
        'interview_date',
        'interview_time',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'interview_date' => 'date:Y-m-d',
        'scheduled_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function jobseeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jobseeker_id');
    }
}
