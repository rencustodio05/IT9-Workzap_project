<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'status',
        'hired_at',
        'fired_at',
    ];

    protected $casts = [
        'hired_at' => 'datetime',
        'fired_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function interview(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function getInterviewAttribute(): ?\App\Models\Interview
    {
        if ($this->relationLoaded('interview')) {
            return $this->getRelation('interview')->first();
        }

        if ($this->relationLoaded('interviews')) {
            return $this->getRelation('interviews')->first();
        }

        return $this->interviews()->latest('scheduled_at')->first();
    }
}
